<?php
namespace App\Http\Controllers;

use App\Models\sewas;
use App\Models\User;
use Illuminate\Http\Request;

class SewaController extends Controller
{
    public function index()
    {
         $data = sewas::with(['user.userTossa'])->get();
    return view('page.superadmin.sewa.index', compact('data'));
    }

    public function manage($id = null)
    {
        $data = $id ? sewas::findOrFail($id) : new sewas();

        $users = User::whereRelation('roles', 'role', 'mitra')
                    ->with('userTossa')
                    ->orderBy('username')
                    ->get();

        return view('page.superadmin.sewa.manage', compact('data', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hargaSewa' => 'required|numeric|min:0'
        ]);

        sewas::create($request->only('user_id', 'hargaSewa'));

        return redirect()->route('admin.sewa.index')->with('success', 'Data sewa berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'hargaSewa' => 'required|numeric|min:0'
        ]);

        $sewa = sewas::findOrFail($id);
        $sewa->update($request->only('user_id', 'hargaSewa'));

        return redirect()->route('admin.sewa.index')->with('success', 'Data sewa berhasil diupdate');
    }

    public function destroy($id)
    {
        $sewa = sewas::findOrFail($id);
        $sewa->delete();

        return redirect()->route('admin.sewa.index')->with('success', 'Data sewa berhasil dihapus');
    }
}
