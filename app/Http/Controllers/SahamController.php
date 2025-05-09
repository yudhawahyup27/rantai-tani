<?php

namespace App\Http\Controllers;

use App\Models\Mastersaham;
use App\Models\Tossa;
use Illuminate\Http\Request;

class SahamController extends Controller
{
    public function index()
    {
        $data = Mastersaham::all();
        return view('page.superadmin.saham.index', compact('data'));
    }

    public function manage($id = null)
    {
        $data = $id ? Mastersaham::find($id) : new Mastersaham();
        $tossa = Tossa::all();
        return view('page.superadmin.saham.manage', compact('tossa', 'data'));
    }

    public function store(Request $request)
{
    $request->validate([
        'tossa_id' => 'required|unique:mastersahams,tossa_id',
        'totallot' => 'required|numeric',
        'harga' => 'required|numeric',
    ],[
        'tossa_id.unique' => 'Nama Supply Network sudah terdaftar.',
        'tossa_id.required' => 'Supply Chain wajib dipilih.',
        'totallot.required' => 'Jumlah lot wajib diisi.',
        'harga.required' => 'Harga wajib diisi.',
    ]);


    $total = $request->totallot * $request->harga;


    Mastersaham::create([
        'tossa_id' => $request->tossa_id,
        'totallot' => $request->totallot,
        'sahamtersedia' =>   $request->totallot,
        'sahamterjual' =>  0,
        'persentase' => $request->persentase,
        'harga' => $request->harga,
        'total' => $total,
    ]);

    return redirect()->route('admin.saham')->with('success', 'Data saham berhasil disimpan.');
}

public function update(Request $request, $id)
{
    $saham = Mastersaham::findOrFail($id);


    $total =  $request->totallot * $request->harga;

    // Perbaikan logika sisa saham:
    // Jumlah saham yang ada SEKARANG dikurangi saham yang sudah masuk (dari database)
    $sisaSaham = $saham->totallot - $saham->sahamterjual;

    $saham->update([
        'tossa_id' => $request->tossa_id,
        'totallot' => $request->totallot,
        'harga' => $request->harga,
        'sahamtersedia' => $sisaSaham,
        'persentase' => $request->persentase,
        'total' => $total,
    ]);

    return redirect()->route('admin.saham')->with('success', 'Data saham berhasil diupdate.');
}

public function destroy ($id){
    $saham = Mastersaham::findOrFail($id);
    $saham->delete();
    return redirect()->route('admin.saham')->with('success', 'Data saham berhasil
    dihapus.');
}
}
