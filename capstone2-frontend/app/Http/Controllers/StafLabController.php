<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StafLabController extends Controller
{
    // 1. Tampilkan Halaman Utama (Inventaris & BHP)
    public function inventaris() {
        $inventaris = Http::get('http://localhost:3000/api/inventaris')->json()['data'] ?? [];
        $bhp = Http::get('http://localhost:3000/api/bhp')->json()['data'] ?? [];
        $ruangan = Http::get('http://localhost:3000/api/ruangan')->json()['data'] ?? [];
        
        return view('staf_lab.inventaris', compact('inventaris', 'bhp', 'ruangan'));
    }

    // 2. Tambah & Edit Aset (Tanpa Fitur Delete)
    public function storeInventaris(Request $request) {
        $response = Http::post('http://localhost:3000/api/staf-lab/inventaris', $request->all());
        return $response->successful() ? back()->with('success', 'Aset baru berhasil ditambahkan!') : back()->with('error', 'Gagal menambahkan aset.');
    }
    
    public function updateInventaris(Request $request, $id) {
        $response = Http::put('http://localhost:3000/api/staf-lab/inventaris/' . $id, $request->all());
        return $response->successful() ? back()->with('success', 'Data aset berhasil diperbarui!') : back()->with('error', 'Gagal update aset.');
    }

    // 3. Fitur Replace Barang Rusak
    public function replaceInventaris(Request $request, $id) {
        // Sesuaikan URL-nya menjadi /replace-inventaris/
        $response = Http::put('http://localhost:3000/api/staf-lab/replace-inventaris/' . $id, [
            'idBaru' => $request->idBaru
        ]);
        
        if ($response->successful()) {
            return back()->with('success', 'Berhasil! Barang rusak telah diafkirkan dan diganti dengan barang baru.');
        }

        // Tampilkan pesan error ASLI dari Node.js agar kita tidak tebak-tebakan lagi
        $errorMessage = $response->json('error') ?? $response->status() . ' - ' . $response->body();
        return back()->with('error', 'Gagal memproses replace: ' . $errorMessage);
    }

    // 4. Tambah & Edit BHP (Barang Habis Pakai)
    public function storeBhp(Request $request) {
        $response = Http::post('http://localhost:3000/api/staf-lab/bhp', $request->all());
        return $response->successful() ? back()->with('success', 'Stok BHP baru berhasil ditambahkan!') : back()->with('error', 'Gagal menambahkan BHP.');
    }

    public function updateBhp(Request $request, $id) {
        $response = Http::put('http://localhost:3000/api/staf-lab/bhp/' . $id, $request->all());
        return $response->successful() ? back()->with('success', 'Stok BHP berhasil diperbarui!') : back()->with('error', 'Gagal update BHP.');
    }
}