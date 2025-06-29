<?php

namespace App\Http\Controllers;

use App\Models\DailyRevenue;
use App\Models\Laporan;
use App\Models\sewas;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::all();
        return view("page.superadmin.laporan.index", compact("laporan"));
    }

    public function manage($id = null)
    {
        $data = $id ? Laporan::findOrFail( $id ) : new Laporan ();
        $users = User::whereRelation('roles', 'role', 'mitra')
                    ->with('userTossa')
                    ->orderBy('username')
                    ->get();

        return view("page.superadmin.laporan.manage", compact("data","users"));
    }

  public function store(Request $request)
{
    $request->validate([
        'id_user' => 'required|exists:users,id',
        'bonus' => 'required|numeric',
    ]);

    $userId = $request->id_user;
    $today = Carbon::today();

    // Ambil user dan validasi tossa_id tidak null
    $user = User::findOrFail($userId);

    if (!$user->id_tossa) {
        return back()->withErrors(['id_user' => 'User belum memiliki Tossa ID.']);
    }

    $tossaId = $user->id_tossa;

    // Ambil semua data revenue hari ini berdasarkan user
    $revenues = DailyRevenue::with('product')
        ->where('id_user', $userId)
        ->whereDate('date', $today)
        ->get();

    // Kelompokkan laba berdasarkan kategori
    $labaSayur = $revenues->where('product.jenis', 'sayur')->sum('revenue');
    $labaBuah = $revenues->where('product.jenis', 'buah')->sum('revenue');
    $labaGaringan = $revenues->where('product.jenis', 'garingan')->sum('revenue');

    // Ambil passive income dari sewas table
    $pasiveIncome = sewas::where('user_id', $userId)
        ->whereDate('created_at', $today)
        ->value('hargaSewa') ?? 0;

    // Hitung total laba bahan baku dan keseluruhan
    $totalLabaBahanBaku = $labaSayur + $labaBuah + $labaGaringan;
    $totalLabaKeseluruhan = $totalLabaBahanBaku + $pasiveIncome;


    // Simpan laporan
    Laporan::create([
        'id_user' => $userId,
        'tossa_id' => $tossaId,
        'labaSayur' => $labaSayur,
        'labaBuah' => $labaBuah,
        'labaGaringan' => $labaGaringan,
        'bonus' => $request->bonus,
        'passiveIncome' => $pasiveIncome,
        'totalLabaBahanBaku' => $totalLabaBahanBaku,
        'totalLabaKeseluruhan' => $totalLabaKeseluruhan,
    ]);

    return redirect()->route('admin.laporan.index')->with('success', 'Laporan berhasil disimpan.');
}

}
