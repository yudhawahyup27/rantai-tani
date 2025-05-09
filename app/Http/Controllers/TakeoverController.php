<?php

namespace App\Http\Controllers;

use App\Models\Investor;
use App\Models\Mastersaham;
use App\Models\Takeover;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TakeoverController extends Controller
{
    public function index(Request $request)
    {
        $data = Takeover::with(['investor', 'fromUser', 'toUser', 'tossa'])->get();
        return view('page.superadmin.investor.takeover.index', compact('data'));
    }

    public function manage($id = null)
    {
        $data = $id ? Takeover::findOrFail($id) : new Takeover();

        // Ambil investor aktif
        $investors = Investor::with('user', 'tossa')->get();

        // Ambil user sebagai tujuan takeover
        $users = User::where('id_role', '3')->get();

        return view('page.superadmin.investor.takeover.manage', compact('data', 'investors', 'users'));
    }

    public function store(Request $request)
    {
        // Log untuk debugging
        Log::info('Data request takeover:', $request->all());

        $validated = $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'to_user_id' => 'required|exists:users,id',
            'perlot' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $investorLama = Investor::findOrFail($validated['investor_id']);

            // Cek validasi jumlah lot
            if ($validated['perlot'] > $investorLama->perlot) {
                return back()->withErrors(['perlot' => 'Jumlah lot melebihi kepemilikan investor.']);
            }

            // Cek agar tidak mengirim ke diri sendiri
            if ($investorLama->user_id == $validated['to_user_id']) {
                return back()->withErrors(['to_user_id' => 'Penerima tidak boleh investor yang sama.']);
            }

            // Ambil harga dari master saham
            $masterSaham = Mastersaham::where('id', $investorLama->tossa_id)->firstOrFail();
            $harga_perlot = $masterSaham->harga;
            $total = $harga_perlot * $validated['perlot'];
            $deviden = $masterSaham->persentase;
            $deviden_total = ($total * ($deviden / 100))/12;

            // Kurangi lot dari investor lama
            $investorLama->perlot -= $validated['perlot'];
            $investorLama->save();

            // Tambahkan lot ke investor baru atau buat baru
            $investorBaru = Investor::where('user_id', $validated['to_user_id'])
                ->where('tossa_id', $investorLama->tossa_id)
                ->first();

            if ($investorBaru) {
                $investorBaru->perlot += $validated['perlot'];
                $investorBaru->save();
            } else {
                $investorBaru = Investor::create([
                    'user_id' => $validated['to_user_id'],
                    'tossa_id' => $investorLama->tossa_id,
                    'perlot' => $validated['perlot'],
                    'total' =>  $total,
                    'Deviden' =>          $deviden_total ,
                ]);
            }

            // Simpan data takeover
            Takeover::create([
                'investor_id'     => $investorLama->id,
                'from_user_id'    => $investorLama->user_id,
                'to_user_id'      => $validated['to_user_id'],
                'tossa_id'        => $investorLama->tossa_id,
                'perlot'          => $validated['perlot'],
                'harga_takeover'  => $harga_perlot,
                'total'           => $total,
            ]);

            DB::commit();
            return redirect()->route('admin.takeover')->with('success', 'Takeover berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Takeover error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan saat proses takeover: ' . $e->getMessage()]);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'investor_id' => 'required|exists:investors,id',
            'to_user_id' => 'required|exists:users,id',
            'perlot' => 'required|numeric|min:1',
        ]);

        DB::beginTransaction();

        try {
            $takeover = Takeover::findOrFail($id);

            // Ambil investor lama (pemilik asal saham sebelum di-takeover)
            $investorFrom = Investor::findOrFail($request->investor_id);

            // Ambil investor penerima
            $investorTo = Investor::firstOrNew([
                'user_id' => $request->to_user_id,
                'tossa_id' => $takeover->tossa_id,
            ]);

            $selisihLot = $request->perlot - $takeover->perlot; // + jika nambah, - jika ngurang

            // Kalau ada tambahan lot, cek apakah pemilik asal cukup sahamnya
            if ($selisihLot > 0 && $investorFrom->perlot < $selisihLot) {
                return back()->with('error', 'Lot investor tidak cukup untuk ditambah ' . $selisihLot . ' lot.');
            }

            // Update investor
            $investorFrom->perlot -= $selisihLot;
            $investorFrom->save();

            $investorTo->perlot += $selisihLot;
            $investorTo->save();

            // Update data takeover
            $takeover->update([
                'investor_id' => $request->investor_id,
                'from_user_id' => $investorFrom->user_id,
                'to_user_id' => $request->to_user_id,
                'perlot' => $request->perlot,
                'total' => $request->perlot * $takeover->harga_takeover, // asumsikan harga tetap
            ]);

            DB::commit();

            return redirect()->route('admin.takeover')->with('success', 'Takeover berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update takeover: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $takeover = Takeover::findOrFail($id);

            // Ambil investor awal (pengirim)
            $investorFrom = Investor::findOrFail($takeover->investor_id);

            // Ambil investor penerima
            $investorTo = Investor::where('user_id', $takeover->to_user_id)
                ->where('tossa_id', $takeover->tossa_id)
                ->first();

            if (!$investorTo) {
                return back()->withErrors(['error' => 'Investor penerima tidak ditemukan.']);
            }

            // Cek apakah investor penerima memiliki cukup lot untuk dikembalikan
            if ($investorTo->perlot < $takeover->perlot) {
                return back()->withErrors(['error' => 'Investor penerima tidak memiliki cukup lot untuk dikembalikan.']);
            }

            // Update mastersaham - tambahkan lot kembali
            $master = MasterSaham::where('tossa_id', $takeover->tossa_id)->first();
            if ($master) {
                $master->sahamtersedia += $takeover->perlot;
                $master->save();
            }

            // Kurangi lot dari investor penerima
            $investorTo->perlot -= $takeover->perlot;

            if ($investorTo->perlot <= 0) {
                $investorTo->delete(); // Hapus investor penerima
            } else {
                $investorTo->save();
            }

            // Tambahkan lot ke investor pengirim
            // $investorFrom->perlot += $takeover->perlot;
            // $investorFrom->save();

            // Hapus data takeover
            $takeover->delete();

            DB::commit();

            return redirect()->route('admin.takeover')->with('success', 'Takeover berhasil dihapus. Lot dikembalikan ke investor awal dan master saham.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Terjadi kesalahan saat menghapus takeover: ' . $e->getMessage()]);
        }
    }


}
