<?php

namespace App\Http\Controllers;

use App\Models\Tossa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SupplyNetworkController extends Controller
{

    public function index (Request $request){
        $search = $request->input('search');
        $perPage = $request->input('per_page', 5); // Default 10 data per halaman
        $sort = $request->input('sort', 'asc'); // Default ascending

        // Query Supply Network dengan filter, pencarian, dan paginasi
        $data = Tossa::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%$search%");
            })
            ->orderBy('created_at', $sort)
            ->paginate($perPage);

        return view('page.superadmin.SupplyNetwork.index', compact('data'));
    }

    public function manage ($id= null){
        $data = $id ? Tossa::findOrFail($id) : new Tossa();

        return view('page.superadmin.SupplyNetwork.manage', compact('data'));
    }
    public function store(Request $request){
        $request->validate([
            'name' => 'required|unique:tossas,name',
        ],[
            'name.unique' => 'Nama Supply Network sudah terdaftar.',
            'name.required' => 'Nama Supply Network harus diisi.',
        ]);

        Tossa::create([
            'name' => $request->name,
        ]);
        return redirect()->route('admin.supply')->with('success', 'Supply Network berhasil ditambahkan');
    }

    public function update(Request $request, $id){
        $tossa = Tossa::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('tossas', 'name')->ignore($tossa->id),
            ],
        ], [
            'name.required' => 'Nama Supply Network harus diisi.',
            'name.unique' => 'Nama Supply Network sudah terdaftar.',
        ]);

        $tossa->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.supply')->with('success', 'Supply Network berhasil diubah');
    }

    public function destroy($id) {
        Tossa::findOrFail($id)->delete();
        return redirect()->route('admin.supply')->with('success', 'Supply Network berhasil dihapus');
    }
}
