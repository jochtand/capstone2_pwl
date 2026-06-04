<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StafAdminController extends Controller
{
    // 1. Tampilkan daftar draf yang sudah di-ACC Kaprodi (Finalized)
    public function index() {
        $drafts = Http::get('http://localhost:3000/api/draft-pengadaan')->json()['data'] ?? [];
        $drafts = array_filter($drafts, function($d) {
            return $d['status'] == 'Finalized';
        });
        return view('staf_admin.pengadaan', compact('drafts'));
    }

    // 2. Lihat detail draf & bawa data ruangan untuk dipilih saat menerima barang
    public function detail($id) {
        $draft = Http::get('http://localhost:3000/api/draft-pengadaan/' . $id)->json()['data'] ?? [];
        $ruangan = Http::get('http://localhost:3000/api/ruangan')->json()['data'] ?? [];
        return view('staf_admin.detail', compact('draft', 'ruangan'));
    }

    // 3. Proses terima barang (Kirim ke Node.js)
    public function terimaBarang(Request $request, $id) {
        $response = Http::put('http://localhost:3000/api/staf-admin/barang/' . $id . '/terima', $request->all());
        
        if ($response->successful()) {
            return back()->with('success', 'Barang berhasil diterima, dilabeli, dan otomatis masuk ke Ruangan!');
        }
        
        $errorMessage = $response->json('error') ?? 'Error ' . $response->status();
        return back()->with('error', 'Gagal memproses barang: ' . $errorMessage);
    }

    // 4. Menu Cek Barang & BHP untuk Sidebar
    public function inventaris() {
        $inventaris = Http::get('http://localhost:3000/api/inventaris')->json()['data'] ?? [];
        $bhp = Http::get('http://localhost:3000/api/bhp')->json()['data'] ?? [];
        return view('staf_admin.inventaris', compact('inventaris', 'bhp'));
    }
}