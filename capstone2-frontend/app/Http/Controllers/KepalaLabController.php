<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class KepalaLabController extends Controller
{
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/draft-pengadaan');
        $drafts = $response->json()['data'] ?? [];
        return view('kepala_lab.draft', compact('drafts'));
    }

    // Menampilkan form tambah draf
    public function create()
    {
        return view('kepala_lab.create');
    }

    // Memproses data form ke Node.js
    public function store(Request $request)
    {
        // 1. Susun data detail barang (Looping array dari form HTML)
        $items = [];
        if ($request->has('nama_barang')) {
            foreach ($request->nama_barang as $index => $nama) {
                $items[] = [
                    'nama_barang' => $nama,
                    'harga' => $request->harga[$index],
                    'jumlah' => $request->jumlah[$index],
                    'link_pembelian' => $request->link_pembelian[$index] ?? null,
                    'inventaris_diganti_id' => $request->inventaris_diganti_id[$index] ?? null,
                ];
            }
        }

        // 2. Siapkan Payload (Data Utama + Detail)
        $payload = [
            'kepala_lab_id' => Session::get('user.id'), // Ambil ID pembuat dari session
            'tahun' => $request->tahun,
            'tgl_pengajuan' => now()->format('Y-m-d'),
            'items' => $items
        ];

        // 3. Tembak API Node.js
        $response = Http::post('http://localhost:3000/api/draft-pengadaan', $payload);

        if ($response->successful()) {
            return redirect('/kepala-lab/draft')->with('success', 'Draf berhasil disimpan!');
        }

        return back()->with('error', 'Gagal menyimpan draf: ' . $response->body());
    }
    // Menampilkan detail draf
    public function show($id)
    {
        // Tembak API detail Node.js
        $response = Http::get('http://localhost:3000/api/draft-pengadaan/' . $id);
        
        if ($response->successful() && $response->json()['status'] == 'success') {
            $draft = $response->json()['data'];
            return view('kepala_lab.detail', compact('draft'));
        }

        // Jika gagal atau ID tidak ada, kembalikan ke halaman daftar
        return redirect('/kepala-lab/draft')->with('error', 'Detail draf tidak ditemukan.');
    }
    // Memproses pengiriman draf ke Kaprodi (Ubah status ke Locked)
    public function submit($id)
    {
        // Tembak API PUT di Node.js
        $response = Http::put('http://localhost:3000/api/draft-pengadaan/' . $id . '/submit');
        
        if ($response->successful() && $response->json()['status'] == 'success') {
            return redirect('/kepala-lab/draft')->with('success', 'Berhasil! Draf telah dikunci dan dikirim ke Kaprodi.');
        }

        return back()->with('error', 'Gagal mengirim draf.');
    }
    // Fungsi Update Barang
    public function updateItem(Request $request, $id) {
        $response = Http::put('http://localhost:3000/api/detail-pengadaan/' . $id, $request->all());
        if ($response->successful()) return back()->with('success', 'Barang berhasil diubah.');
        return back()->with('error', 'Gagal mengubah barang.');
    }

    // Fungsi Hapus Barang
    public function destroyItem($id) {
        $response = Http::delete('http://localhost:3000/api/detail-pengadaan/' . $id);
        if ($response->successful()) return back()->with('success', 'Barang berhasil dihapus dari draf.');
        return back()->with('error', 'Gagal menghapus barang.');
    }

    // Fungsi Cek Inventaris (Untuk Sidebar)
    public function inventaris() {
        $inventaris = Http::get('http://localhost:3000/api/inventaris')->json()['data'] ?? [];
        $bhp = Http::get('http://localhost:3000/api/bhp')->json()['data'] ?? [];
        return view('kepala_lab.inventaris', compact('inventaris', 'bhp'));
    }
    // Fungsi Hapus Draf Keseluruhan
    public function destroyDraft($id) {
        $response = Http::delete('http://localhost:3000/api/draft-pengadaan/' . $id);
        
        if ($response->successful()) {
            return back()->with('success', 'Draf berhasil dihapus secara permanen.');
        }
        return back()->with('error', 'Gagal menghapus draf. Pastikan isinya sudah kosong.');
    }
}