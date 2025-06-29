<?php

namespace App\Http\Controllers;

use App\Models\investor;
use App\Models\Mastersaham;
use App\Models\Tossa;
use App\Models\User;
use Illuminate\Http\Request;

class InvestorController extends Controller
{
    // Menampilkan daftar investor
    public function index()
    {
        $data = investor::with(['user', 'tossa'])->get();
        return view('page.superadmin.investor.index', compact('data'));
    }

    // Menampilkan form tambah atau edit investor
    public function manage($id = null)
    {
        $data = $id ? investor::findOrFail($id) : new Investor();

        // Ambil user dengan role investor
        $user = User::where('id_role', '3')->pluck('username', 'id');

        // Get Tossa data with relationship to Mastersaham
        $tossa = Mastersaham::with('tossa')->get()->pluck('tossa.name', 'id');

        // Create a mapping of Mastersaham IDs to their persentase values
        $devidens = Mastersaham::pluck('persentase', 'id')->toArray();

        // Tambahkan detail harga dan persentase untuk perhitungan JavaScript
        $tossaDetail = Mastersaham::get()->keyBy('id')->map(function ($item) {
            return [
                'harga' => $item->harga,
                'persentase' => $item->persentase,
            ];
        });

        return view('page.superadmin.investor.manage', compact('data', 'user', 'tossa', 'devidens', 'tossaDetail'));
    }


    // Menyimpan data investor baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|exists:users,id',
            'tossa' => 'required|exists:mastersahams,id',
            'lot' => 'required|numeric|min:1',
        ]);

        $saham = Mastersaham::findOrFail($request->tossa);

        // Cek jika saham tersedia cukup
        if ($saham->sahamtersedia < $request->lot) {
            return back()->withErrors(['lot' => 'Saham yang tersedia tidak mencukupi.']);
        }

        $hargaPerLot = $saham->harga;
        $persentase = $saham->persentase;

        $newTotal = $request->lot * $hargaPerLot;
        $newDeviden = ($newTotal * ($persentase / 100)) / 12;

        // Cek apakah kombinasi user_id dan tossa_id sudah ada
        $existingInvestor = investor::where('user_id', $request->name)
            ->where('tossa_id', $request->tossa)
            ->first();

        if ($existingInvestor) {
            // Update data investor yang sudah ada
            $existingInvestor->perlot += $request->lot;
            $existingInvestor->total += $newTotal;
            $existingInvestor->Deviden = ($existingInvestor->total * ($persentase / 100)) / 12;
            $existingInvestor->save();
        } else {
            // Simpan investor baru
            investor::create([
                'user_id' => $request->name,
                'tossa_id' => $request->tossa,
                'perlot' => $request->lot,
                'Deviden' => $newDeviden,
                'total' => $newTotal,
            ]);
        }

        // Update saham tersedia dan terjual
        $saham->sahamtersedia -= $request->lot;
        $saham->sahamterjual += $request->lot;
        $saham->save();

        return redirect()->route('admin.investor')->with('success', 'Investor berhasil ditambahkan.');
    }




    // Memperbarui data investor
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'tossa' => 'required|string|max:255',
            'lot' => 'required|numeric|min:1',
            'Deviden' => 'required|numeric|min:1',
        ]);

        $investor = investor::findOrFail($id);

        $lotLama = $investor->perlot;
        $tossaLama = $investor->tossa_id;

        // Restore saham lama dulu (sahamtersedia ditambah, sahamterjual dikurangi)
        $sahamLama = Mastersaham::find($tossaLama);
        if ($sahamLama) {
            $sahamLama->sahamtersedia += $lotLama;
            $sahamLama->sahamterjual -= $lotLama;
            $sahamLama->save();
        }

        // Cek saham baru apakah cukup
        $sahamBaru = Mastersaham::findOrFail($request->tossa);
        if ($sahamBaru->sahamtersedia < $request->lot) {
            return back()->withErrors(['lot' => 'Saham yang tersedia tidak mencukupi.']);
        }


        // Cek jika saham tersedia cukup

        $hargaPerLot =    $sahamBaru->harga;
        $persentase =    $sahamBaru->persentase;
        $total = $request->lot * $hargaPerLot;
        // Update data investor
        $investor->user_id = $request->name;
        $investor->tossa_id = $request->tossa;
        $investor->perlot = $request->lot;
        $investor->Deviden = $request->Deviden;
        $investor->total =  $total;
        $investor->save();

        // Kurangi saham tersedia dan tambah saham terjual untuk tossa baru
        $sahamBaru->sahamtersedia -= $request->lot;
        $sahamBaru->sahamterjual += $request->lot;
        $sahamBaru->save();

        return redirect()->route('admin.investor')->with('success', 'Investor berhasil diperbarui.');
    }



    // Menghapus data investor
    public function destroy($id)
    {
        $investor = investor::findOrFail($id);

        // Ambil informasi terkait saham sebelum dihapus
        $lot = $investor->perlot;
        $tossaId = $investor->tossa_id;

        // Update data di master saham
        $saham = Mastersaham::find($tossaId);
        if ($saham) {
            $saham->sahamtersedia += $lot;
            $saham->sahamterjual -= $lot;

            // Pastikan tidak minus
            if ($saham->sahamterjual < 0) {
                $saham->sahamterjual = 0;
            }

            $saham->save();
        }

        // Hapus data investor
        $investor->delete();

        return redirect()->route('admin.investor')->with('success', 'Investor berhasil dihapus.');
    }

}
