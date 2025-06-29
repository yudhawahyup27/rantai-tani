<?php

namespace App\Http\Controllers;

use App\Models\DailyRevenue;
use App\Models\LaporanKeuangan;
use App\Models\newStock;
use App\Models\sewas;
use App\Models\Stocks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanKeuanganMitra extends Controller
{
    public function index (){
        $data = LaporanKeuangan::with('pic','tossa')->get();

        return view('page.mitra.laporan.index', compact('data'));
    }

    public function manage($id = null)
    {
        $data = $id ? LaporanKeuangan::findOrFail($id) : new LaporanKeuangan();
       $users = User::where('id_role', 2)->get();
        return view('page.mitra.laporan.manage', compact('data', 'users'));
    }




public function store(Request $request)
{
    $request->validate([
        'id_user' => 'required|exists:users,id',
        'ravenue' => 'required|numeric',
        'pengeluaran' => 'required|numeric',
        'gajikaryawan' => 'required|numeric',
        'note' => 'nullable|string|max:255',
    ]);

    $user = User::findOrFail($request->id_user);
        $userId = $request->id_user;
    $tossaId = $user->id_tossa;
    $today = now()->startOfDay();

    $laporanHariIni = DailyRevenue::where('id_user',  $userId )
        ->whereDate('created_at', $today)
        ->with('product')
        ->get();

    $stokDibawa = 0;
    $lakuterjual = 0;

    foreach ($laporanHariIni as $laporan) {
        $stokDibawa += $laporan->stock_start * $laporan->product->price_sell;
        $lakuterjual += $laporan->sold_quantity * $laporan->product->price_sell;
    }

    // dd(  $userId);
    $sewa = sewas::where('user_id',  $userId )
        ->whereDate('created_at', $today)
        ->value('hargaSewa') ;


        $labakotor = $request->ravenue - ($request->pengeluaran + $request->gajikaryawan + $lakuterjual);
    $labaBersih = $labakotor;
    $grosMargin = $request->ravenue > 0 ? ($labakotor / $request->ravenue) * 100 : 0;
    $margin = $lakuterjual - $request->ravenue;

    LaporanKeuangan::create([
        'tossa_id' => $tossaId,
        'id_Pic' =>  $userId ,
        'ravenue' => $request->ravenue,
        'pengeluaran' => $request->pengeluaran,
        'gajikaryawan' => $request->gajikaryawan,
        'daganganBaru' => $lakuterjual,
        'labakotor' => $labakotor,
        'margin' => $margin,
        'sewaTossa' => $sewa,
        'labaDibawa' => $stokDibawa,
        'daganganlakuterjual' => $lakuterjual,
        'grosMargin' => $grosMargin,
        'labaBersih' => $labaBersih,
        'note' => $request->note,
    ]);

    return redirect()->route('mitra.laporan.index')->with('success', 'Laporan berhasil disimpan.');
}



public function update(Request $request, $id)
{
    $request->validate([
        'id_user' => 'required|exists:users,id',
        'ravenue' => 'required|numeric',
        'pengeluaran' => 'required|numeric',
        'gajikaryawan' => 'required|numeric',
        'note' => 'nullable|string|max:255',
    ]);

    $laporan = LaporanKeuangan::findOrFail($id);
    $user = User::findOrFail($request->id_user);
    $tossaId = $user->id_tossa;
    $today = now()->startOfDay();

    $laporanHariIni = DailyRevenue::where('id_user', $user->id)
        ->whereDate('created_at', $today)
        ->with('product')
        ->get();

    $stokDibawa = 0;
    $lakuterjual = 0;

    foreach ($laporanHariIni as $laporanData) {
        $stokDibawa += $laporanData->stock_start * $laporanData->product->price_sell;
        $lakuterjual += $laporanData->sold_quantity * $laporanData->product->price_sell;
    }

    $sewa = sewas::where('user_id', $user->id)
        ->whereDate('created_at', $today)
        ->value('hargaSewa') ?? 0;

    $labakotor = $request->ravenue - ($request->pengeluaran + $request->gajikaryawan + $lakuterjual);
    $labaBersih = $labakotor;
    $grosMargin = $request->ravenue > 0 ? ($labakotor / $request->ravenue) * 100 : 0;
    $margin = $lakuterjual - $request->ravenue;

    $laporan->update([
        'tossa_id' => $tossaId,
        'id_Pic' => $user->id,
        'ravenue' => $request->ravenue,
        'pengeluaran' => $request->pengeluaran,
        'gajikaryawan' => $request->gajikaryawan,
        'daganganBaru' => $lakuterjual,
        'labakotor' => $labakotor,
        'margin' => $margin,
        'sewaTossa' => $sewa,
        'labaDibawa' => $stokDibawa,
        'daganganlakuterjual' => $lakuterjual,
        'grosMargin' => $grosMargin,
        'labaBersih' => $labaBersih,
        'note' => $request->note,
    ]);

    return redirect()->route('mitra.laporan.index')->with('success', 'Laporan berhasil diperbarui.');
}


}
