<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/users');
        $users = $response->json()['data'] ?? [];
        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $response = Http::post('http://localhost:3000/api/users', [
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role
        ]);

        if ($response->successful()) {
            return redirect('/admin/users')->with('success', 'Pengguna baru berhasil ditambahkan.');
        }
        return back()->with('error', 'Gagal menambahkan pengguna.');
    }

    public function destroy($id)
    {
        $response = Http::delete('http://localhost:3000/api/users/' . $id);
        
        if ($response->successful()) {
            return redirect('/admin/users')->with('success', 'Pengguna berhasil dihapus.');
        }
        return back()->with('error', 'Gagal menghapus pengguna.');
    }
    public function update(Request $request, $id)
    {
        $response = Http::put('http://localhost:3000/api/users/' . $id, [
            'nama' => $request->nama,
            'email' => $request->email,
            'role' => $request->role
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Data pengguna berhasil diperbarui!');
        }
        return back()->with('error', 'Gagal memperbarui data pengguna.');
    }
}