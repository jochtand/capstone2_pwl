<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kaprodi - Review Pengadaan</title>
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
                @if(session('user.role') == 'Kaprodi')
                    <a href="/kaprodi/review" class="block px-4 py-2 bg-blue-600 rounded text-white shadow">Review Kaprodi</a>
                    <a href="/kaprodi/inventaris" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300 transition-colors">Cek Barang & BHP</a>
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
                    <h1 class="text-3xl font-bold">Review Pengajuan Pengadaan</h1>
                    <p class="text-gray-500 mt-1">Halo, <strong>{{ session('user.nama') }}</strong>! Silakan tinjau draf yang diajukan oleh Kepala Laboratorium.</p>
                </div>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold border border-green-300">
                    Role: {{ session('user.role') }}
                </span>
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
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="🔍 Cari berdasarkan ID Draf, Tahun, Kepala Lab, atau Status..." class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-sm transition-all">
            </div>

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-600 w-12 text-center">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">ID Draf</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Tahun Pengajuan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Diajukan Oleh</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Aksi Review</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($drafts as $d)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-700 text-center">{{ count($drafts) - $loop->index }}</td>
                            <td class="px-6 py-4 font-bold text-gray-700">#{{ $d['id'] }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $d['tahun'] }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $d['nama_kepala_lab'] }}</td>
                            <td class="px-6 py-4">
                                @if($d['status'] == 'Draft')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold uppercase">Draft</span>
                                @elseif($d['status'] == 'Locked')
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold uppercase animate-pulse">Menunggu Review</span>
                                @else
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold uppercase">Finalized</span>
                                @endif
                            </td>
                            
                            <td class="px-6 py-4 text-center">
                                @if($d['status'] == 'Locked')
                                    <a href="/kaprodi/draft/{{ $d['id'] }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-semibold inline-block transition-colors">
                                        Mulai Review Detail
                                    </a>
                                @elseif($d['status'] == 'Finalized')
                                    <div class="space-x-2">
                                        <a href="/kaprodi/draft/{{ $d['id'] }}" class="text-blue-600 hover:text-blue-800 font-semibold underline transition-colors">
                                            Lihat Detail
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="/kaprodi/draft/{{ $d['id'] }}/pdf" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded shadow-sm text-sm font-semibold transition-colors">
                                            Cetak PDF
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic text-sm">Menunggu Kepala Lab</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada draf pengadaan.</td>
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
            var isBackNavigation = event.persisted || 
                                   (typeof window.performance !== "undefined" && 
                                    window.performance.navigation.type === 2);
            if (isBackNavigation) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>