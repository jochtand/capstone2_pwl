<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RuanganController extends Controller
{
    // Tampilkan semua ruangan
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/ruangan');
        $ruangans = $response->json()['data'] ?? [];
        return view('admin.ruangan', compact('ruangans'));
    }

    // Tambah ruangan baru
    public function store(Request $request)
    {
        $response = Http::post('http://localhost:3000/api/ruangan', [
            'nama_ruangan' => $request->nama_ruangan
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Ruangan laboratorium baru berhasil ditambahkan!');
        }
        return back()->with('error', 'Gagal menambahkan ruangan.');
    }

    // Hapus ruangan
    public function destroy($id)
    {
        $response = Http::delete('http://localhost:3000/api/ruangan/' . $id);
        
        if ($response->successful()) {
            return back()->with('success', 'Ruangan berhasil dihapus dari sistem.');
        }
        return back()->with('error', 'Gagal menghapus ruangan. Pastikan tidak ada inventaris yang masih terikat di ruangan ini.');
    }
    // Update ruangan
    public function update(Request $request, $id)
    {
        $response = Http::put('http://localhost:3000/api/ruangan/' . $id, [
            'nama_ruangan' => $request->nama_ruangan
        ]);

        if ($response->successful()) {
            return back()->with('success', 'Data ruangan berhasil diperbarui!');
        }
        return back()->with('error', 'Gagal memperbarui data ruangan.');
    }
}