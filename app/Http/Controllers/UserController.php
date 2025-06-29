<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Tossa;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function userpage() {
        $users = User::with(['role', 'userTossa', 'workShift'])->get();
        return view('page.superadmin.User.index', compact('users'));
    }

    // Menampilkan form create/edit user
    public function manage($id = null) {
       $data = $id ? User::findOrFail($id) : new User();
        $roles = Role::all();
        $tossas = Tossa::all();
        $shifts = Shift::all();
        return view('page.superadmin.user.manage', compact('data', 'roles', 'tossas', 'shifts'));
    }

    // Menyimpan user baru
    public function store(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'id_tossa' => 'nullable|exists:tossas,id',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_role' => 'required|exists:roles,id',
        ], [
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min:6' => 'Password Minim 6 karakter',
            'username.required' => 'Username wajib diisi',
            'id_role.required' => 'Role wajib diisi',
        ]);

        User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'id_tossa' => $request->id_tossa,
            'telepon' => $request->telepon,
            'id_shift' => $request->id_shift,
            'id_role' => $request->id_role,
        ]);

        return redirect()->route('admin.user')->with('success', 'User berhasil ditambahkan'); // Ganti nama rute jika diperlukan
    }

    public function update(Request $request, $id) {
        $user = User::findOrFail($id);

        $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
            'id_tossa' => 'nullable|exists:tossas,id',
            'id_shift' => 'nullable|exists:shifts,id',
            'id_role' => 'required|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->update([
            'username' => $request->username,
            'telepon' => $request->telepon,
            'id_tossa' => $request->id_tossa,
            'id_shift' => $request->id_shift,
            'id_role' => $request->id_role,
        ]);

        return redirect()->route('admin.user')->with('success', 'User berhasil diperbarui'); // Ganti nama rute jika diperlukan
    }

    // Menghapus user
    public function destroy($id) {
        User::findOrFail($id)->delete();
        return redirect()->route('admin.user')->with('success', 'User berhasil dihapus');
    }
}
