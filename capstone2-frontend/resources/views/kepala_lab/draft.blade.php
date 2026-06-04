<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kepala Lab - Draf Pengadaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <div class="flex h-screen">
        
        <aside class="w-64 bg-gray-900 text-white flex flex-col hidden md:flex">
            <div class="p-6 border-b border-gray-800">
                <h2 class="text-2xl font-bold">Lab Aset</h2>
                <p class="text-sm text-gray-400 mt-1">E-Procurement System</p>
            </div>
            
            <nav class="flex-1 px-4 py-6 space-y-2">
                @if(session('user.role') == 'Administrator')
                    <a href="/admin/ruangan" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300">Kelola Ruangan</a>
                    <a href="/admin/users" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300">Kelola Pengguna</a>
                @endif

                @if(session('user.role') == 'Kepala Laboratorium')
                    <a href="/kepala-lab/draft" class="block px-4 py-2 bg-blue-600 rounded text-white shadow">Draf Pengadaan (Kepala Lab)</a>
                    <a href="/kepala-lab/inventaris" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300 transition-colors">Cek Barang & BHP</a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold transition-colors">
                    Logout
                </a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Draf Pengadaan Barang</h1>
                    <p class="text-gray-500 mt-1">Halo, <strong>{{ session('user.nama') }}</strong>! Ini adalah daftar pengajuan pengadaan aset dan BHP tahunan.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold border border-green-300">
                        Role: {{ session('user.role') }}
                    </span>
                    <a href="/kepala-lab/draft/create" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow font-semibold transition-colors">
                        + Buat Draf Baru
                    </a>
                </div>
            </header>
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="🔍 Cari berdasarkan ID Draf, Tahun, Status..." class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden w-full">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <!-- TAMBAHAN: Kolom No -->
                            <th class="px-6 py-4 font-semibold text-gray-600 w-12 text-center">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">ID Draf</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Tahun Pengajuan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Diajukan Oleh</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($drafts as $d)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <!-- TAMBAHAN: Iterasi Loop untuk nomor urut -->
                            <td class="px-6 py-4 font-medium text-gray-700 text-center">{{ count($drafts) - $loop->index }}</td>
                            <td class="px-6 py-4 font-bold text-gray-700">#{{ $d['id'] }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $d['tahun'] }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($d['tgl_pengajuan'])->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $d['nama_kepala_lab'] }}</td>
                            <td class="px-6 py-4">
                                @if($d['status'] == 'Draft')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold uppercase">Draft</span>
                                @elseif($d['status'] == 'Locked')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold uppercase">Locked</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold uppercase">Finalized</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="/kepala-lab/draft/{{ $d['id'] }}" class="text-blue-600 hover:text-blue-800 font-medium inline-block">
                                    Lihat Detail
                                </a>
    
                                @if($d['status'] == 'Draft')
                                    <span class="text-gray-300">|</span>
                                    <form action="/kepala-lab/draft/{{ $d['id'] }}/submit" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 font-medium" onclick="return confirm('Yakin ingin mengirim draf ini ke Kaprodi? Setelah dikirim, data akan dikunci dan tidak bisa diubah lagi.')">
                                            Kirim ke Kaprodi
                                        </button>
                                    </form>
        
                                    <span class="text-gray-300">|</span>
                                    @if($d['jumlah_barang'] == 0)
                                        <form action="/kepala-lab/draft/{{ $d['id'] }}/delete" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium" onclick="return confirm('Yakin ingin menghapus draf kosong ini?')">
                                                Hapus Draf
                                            </button>
                                        </form>
                                    @else
                                        <button type="button" class="text-gray-400 cursor-not-allowed font-medium inline-block" title="Kosongkan barang di dalam detail terlebih dahulu untuk menghapus draf ini!">
                                            Hapus Draf
                                        </button>
                                    @endif
                                 @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada draf pengadaan yang diajukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        function filterTable() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                row.style.display = text.includes(input) ? "" : "none";
            });
        }
        window.addEventListener('pageshow', function(event) {
            // Deteksi jika halaman diakses dari tombol "Back" atau "Kembali"
            var isBackNavigation = event.persisted || 
                                   (typeof window.performance !== "undefined" && 
                                    window.performance.navigation.type === 2);
            
            if (isBackNavigation) {
                // Paksa browser memuat ulang data terbaru dari server secara otomatis
                window.location.reload();
            }
        });
    </script>
</body>
</html>