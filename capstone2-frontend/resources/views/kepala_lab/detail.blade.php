<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Draf Pengadaan - Lab Aset</title>
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
                    <!-- TAMBAHAN MENU CEK BARANG -->
                    <a href="/kepala-lab/inventaris" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300 transition-colors">Cek Barang & BHP</a>
                @endif
            </nav>
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold transition-colors">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto relative">
            
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Detail Draf #{{ $draft['id'] }}</h1>
                    <p class="text-gray-500 mt-1">Rincian pengajuan barang tahun {{ $draft['tahun'] }}</p>
                </div>
                <a href="/kepala-lab/draft" class="text-gray-600 hover:text-gray-900 font-semibold underline">&larr; Kembali ke Daftar</a>
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

            <div class="bg-white rounded-lg shadow p-6 mb-8 flex gap-12">
                <div>
                    <p class="text-sm text-gray-500 font-semibold mb-1">Diajukan Oleh</p>
                    <p class="text-lg font-bold text-gray-800">{{ $draft['nama_kepala_lab'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold mb-1">Tanggal Pengajuan</p>
                    <p class="text-lg font-bold text-gray-800">{{ \Carbon\Carbon::parse($draft['tgl_pengajuan'])->format('d F Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 font-semibold mb-1">Status Pengajuan</p>
                    @if($draft['status'] == 'Draft')
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold uppercase">Draft</span>
                    @elseif($draft['status'] == 'Locked')
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-bold uppercase">Locked</span>
                    @else
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-bold uppercase">Finalized</span>
                    @endif
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-4">Daftar Barang yang Diajukan</h3>
            <div class="bg-white rounded-lg shadow overflow-hidden w-full">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-600">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Nama Barang</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Harga Satuan</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Jumlah</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Subtotal</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $total_semua = 0; @endphp
                        
                        @forelse ($draft['items'] as $index => $item)
                            @php 
                                $subtotal = $item['harga'] * $item['jumlah'];
                                $total_semua += $subtotal;
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $item['nama_barang'] }}</td>
                                <td class="px-6 py-4 text-gray-600">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $item['jumlah'] }} unit</td>
                                <td class="px-6 py-4 font-bold text-gray-700">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                
                                <!-- KOLOM AKSI (EDIT & DELETE) -->
                                <td class="px-6 py-4 text-center space-x-2">
                                    @if($draft['status'] == 'Draft')
                                        <button onclick="editItem({{ $item['id'] }}, '{{ addslashes($item['nama_barang']) }}', {{ $item['harga'] }}, {{ $item['jumlah'] }}, '{{ addslashes($item['link_pembelian'] ?? '') }}')" class="text-blue-600 hover:text-blue-800 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition-colors">Edit</button>
                                        
                                        <form action="/kepala-lab/item/{{ $item['id'] }}/delete" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition-colors" onclick="return confirm('Yakin ingin menghapus barang ini dari draf?')">Hapus</button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 italic text-sm">Terkunci</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada barang dalam draf ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-gray-100 border-t-2 border-gray-200">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-700 text-lg">Total Anggaran:</td>
                            <td class="px-6 py-4 font-bold text-blue-700 text-lg">Rp {{ number_format($total_semua, 0, ',', '.') }}</td>
                            <td></td> 
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- MODAL EDIT BARANG -->
            <div id="modal-edit-item" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Barang</h2>
                    <form id="edit-form-item" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Barang</label>
                            <input type="text" id="edit_nama" name="nama_barang" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Harga Satuan</label>
                            <input type="number" id="edit_harga" name="harga" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah</label>
                            <input type="number" id="edit_jumlah" name="jumlah" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Link Pembelian (Opsional)</label>
                            <input type="text" id="edit_link" name="link_pembelian" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-edit-item').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded transition-colors">Batal</button>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded transition-colors shadow">Update Barang</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function editItem(id, nama, harga, jumlah, link) {
            document.getElementById('edit-form-item').action = '/kepala-lab/item/' + id + '/update';
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_harga').value = harga;
            document.getElementById('edit_jumlah').value = jumlah;
            document.getElementById('edit_link').value = link || '';
            document.getElementById('modal-edit-item').classList.remove('hidden');
        }
    </script>
</body>
</html>