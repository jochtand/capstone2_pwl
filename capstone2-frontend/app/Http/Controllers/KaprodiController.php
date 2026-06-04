<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf; //

class KaprodiController extends Controller
{
    // Menampilkan halaman daftar draf untuk Kaprodi
    public function index()
    {
        $response = Http::get('http://localhost:3000/api/draft-pengadaan');
        $drafts = $response->json()['data'] ?? [];
        return view('kaprodi.review', compact('drafts'));
    }

    // Menampilkan halaman detail untuk di-review per-barang
    public function show($id)
    {
        $response = Http::get('http://localhost:3000/api/draft-pengadaan/' . $id);
        
        if ($response->successful() && $response->json()['status'] == 'success') {
            $draft = $response->json()['data'];
            return view('kaprodi.detail', compact('draft'));
        }

        return redirect('/kaprodi/review')->with('error', 'Detail draf tidak ditemukan.');
    }

    // Memproses finalisasi draf dari halaman detail
    public function finalize(Request $request, $id)
    {
        // Format inputan array dari form HTML: items[item_id] = 'Disetujui' / 'Ditolak'
        $itemsData = [];
        if ($request->has('items')) {
            foreach ($request->items as $itemId => $status) {
                $itemsData[] = [
                    'id' => $itemId,
                    'status' => $status
                ];
            }
        }

        // Tembak API Node.js
        $response = Http::put('http://localhost:3000/api/kaprodi/draft/' . $id . '/finalize', [
            'items' => $itemsData
        ]);

        if ($response->successful() && $response->json()['status'] == 'success') {
            return redirect('/kaprodi/review')->with('success', 'Draf berhasil difinalisasi beserta status tiap barang!');
        }

        return back()->with('error', 'Gagal memproses finalisasi draf.');
    }
    // Fungsi untuk Cetak PDF Draf yang Finalized
    public function cetakPdf($id)
    {
        // 1. Ambil data dari Node.js
        $response = Http::get('http://localhost:3000/api/draft-pengadaan/' . $id);
        
        if ($response->successful() && $response->json()['status'] == 'success') {
            $draft = $response->json()['data'];
            
            // Pastikan hanya yang sudah Finalized yang bisa dicetak
            if ($draft['status'] != 'Finalized') {
                return back()->with('error', 'Hanya draf yang sudah Finalisasi yang dapat dicetak.');
            }

            // 2. Render tampilan ke PDF
            $pdf = Pdf::loadView('kaprodi.laporan_pdf', compact('draft'));
            
            // 3. Download otomatis dengan nama file yang rapi
            return $pdf->download('Laporan-Pengadaan-Barang-'.$draft['tahun'].'.pdf');
        }

        return redirect('/kaprodi/review')->with('error', 'Gagal mengambil data untuk cetak PDF.');
    }
    // Fungsi Cek Inventaris (Untuk Sidebar Kaprodi)
    public function inventaris() {
        $inventaris = Http::get('http://localhost:3000/api/inventaris')->json()['data'] ?? [];
        $bhp = Http::get('http://localhost:3000/api/bhp')->json()['data'] ?? [];
        return view('kaprodi.inventaris', compact('inventaris', 'bhp'));
    }
}