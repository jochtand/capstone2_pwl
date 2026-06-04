<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penerimaan - Staf Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">

    <div class="flex h-screen">
        <aside class="w-64 bg-gray-900 text-white flex flex-col hidden md:flex">
            <div class="p-6 border-b border-gray-800">
                <h2 class="text-2xl font-bold">Lab Aset</h2>
                <p class="text-sm text-gray-400 mt-1">E-Procurement System</p>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                @if(session('user.role') == 'Staf Administrasi')
                    <a href="/staf-admin/pengadaan" class="block px-4 py-2 bg-blue-600 rounded text-white shadow">Penerimaan Barang</a>
                    <a href="/staf-admin/inventaris" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300 transition-colors">Cek Barang & BHP</a>
                @endif
            </nav>
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold transition-colors">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto relative">
            
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Proses Detail Draf #{{ $draft['id'] }}</h1>
                    <p class="text-gray-500 mt-1">Tahun Pengajuan: {{ $draft['tahun'] }} | Oleh: {{ $draft['nama_kepala_lab'] }}</p>
                </div>
                <a href="/staf-admin/pengadaan" class="text-gray-600 hover:text-gray-900 font-semibold underline">&larr; Kembali ke Daftar</a>
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

            <div class="bg-white rounded-lg shadow overflow-hidden w-full mb-8">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-600 w-12 text-center">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Nama Barang</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Jumlah</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center">Aksi / Label</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($draft['items'] as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-center font-medium text-gray-700">
                                    {{ count($draft['items']) - $loop->index }}
                                </td>
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $item['nama_barang'] }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $item['jumlah'] }} unit</td>
                                
                                <td class="px-6 py-4">
                                    @if(!$item['tanggal_terima'])
                                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold uppercase animate-pulse">Menunggu Barang</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold uppercase">Sudah Diterima</span>
                                    @endif
                                </td>
                                
                                <td class="px-6 py-4 text-center">
                                    @if(!$item['tanggal_terima'])
                                        <button onclick="bukaModalTerima({{ $item['id'] }}, '{{ addslashes($item['nama_barang']) }}', {{ $item['jumlah'] }})" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition-colors">
                                            Terima Barang & Alokasikan
                                        </button>
                                    @else
                                        <button onclick="bukaModalBarcode('{{ $item['label'] }}', '{{ addslashes($item['nama_barang']) }}')" class="bg-gray-800 hover:bg-black text-white font-bold py-2 px-4 rounded shadow transition-colors flex items-center justify-center gap-2 mx-auto">
                                            <span>Lihat Barcode</span>
                                        </button>
                                        <p class="text-xs text-gray-500 mt-1 font-mono">{{ $item['label'] }}</p>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada barang dalam draf ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div id="modal-terima" class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-xl p-8 w-full max-w-lg shadow-2xl">
                    <h2 class="text-2xl font-bold mb-2 text-gray-800 border-b pb-3">Form Penerimaan Barang</h2>
                    
                    <form id="form-terima-barang" method="POST">
                        @csrf
                        <div class="bg-blue-50 p-4 rounded-lg mb-4 border border-blue-100">
                            <p class="text-sm text-gray-500 font-semibold">Barang yang diterima:</p>
                            <p id="info_nama_barang" class="text-lg font-bold text-blue-900"></p>
                            <p id="info_jumlah_barang" class="text-sm font-bold text-blue-700"></p>
                        </div>

                        <input type="hidden" id="input_nama_barang" name="nama_barang">
                        <input type="hidden" id="input_jumlah" name="jumlah">

                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Diterima</label>
                            <input type="date" name="tanggal_terima" value="{{ date('Y-m-d') }}" class="border border-gray-300 w-full py-2 px-3 rounded focus:ring-2 focus:ring-blue-500" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Barang</label>
                                <select name="kategori" class="border border-gray-300 w-full py-2 px-3 rounded focus:ring-2 focus:ring-blue-500 bg-white" required>
                                    <option value="Inventaris">Aset / Inventaris</option>
                                    <option value="BHP">BHP (Habis Pakai)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi Fisik</label>
                                <select name="kondisi" class="border border-gray-300 w-full py-2 px-3 rounded focus:ring-2 focus:ring-blue-500 bg-white" required>
                                    <option value="Baik" class="text-green-600 font-semibold">Baik & Normal</option>
                                    <option value="Rusak" class="text-red-600 font-semibold">Cacat Produk / Rusak</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Alokasi Ruangan / Penempatan</label>
                            <select name="ruangan_id" class="border border-gray-300 w-full py-2 px-3 rounded focus:ring-2 focus:ring-blue-500 bg-white" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($ruangan as $r)
                                    <option value="{{ $r['id'] }}">{{ $r['nama_ruangan'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="document.getElementById('modal-terima').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded transition-colors">Batal</button>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-2 rounded transition-colors shadow">Simpan & Generate Barcode</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modal-barcode" class="fixed inset-0 bg-black bg-opacity-70 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl text-center">
                    <h2 class="text-xl font-bold text-gray-800 mb-1">Label Aset Resmi</h2>
                    <p id="barcode_nama_barang" class="text-gray-500 text-sm mb-6 pb-4 border-b"></p>
                    
                    <div class="bg-white border-2 border-dashed border-gray-300 p-4 flex justify-center items-center rounded-lg mb-6 w-full max-w-full overflow-hidden">
                        <svg id="barcode-canvas" class="max-w-full h-auto"></svg>
                    </div>

                    <div class="flex justify-center gap-3">
                        <button type="button" onclick="document.getElementById('modal-barcode').classList.add('hidden')" class="bg-gray-800 hover:bg-black text-white font-semibold px-6 py-2 rounded transition-colors">Tutup</button>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Fungsi Buka Modal Terima
        function bukaModalTerima(id, nama, jumlah) {
            document.getElementById('form-terima-barang').action = '/staf-admin/barang/' + id + '/terima';
            document.getElementById('info_nama_barang').innerText = nama;
            document.getElementById('info_jumlah_barang').innerText = jumlah + ' Unit';
            
            // Isi input hidden untuk dikirim ke Backend
            document.getElementById('input_nama_barang').value = nama;
            document.getElementById('input_jumlah').value = jumlah;
            
            document.getElementById('modal-terima').classList.remove('hidden');
        }

        // Fungsi Buka Modal Barcode
        function bukaModalBarcode(labelString, namaBarang) {
            document.getElementById('barcode_nama_barang').innerText = namaBarang;
            
            // Generate Gambar Barcode menggunakan JsBarcode
            JsBarcode("#barcode-canvas", labelString, {
                format: "CODE128",     // Standar format barcode industri
                lineColor: "#000000",  // Warna garis hitam
                width: 1.8,              // Ketebalan garis
                height: 70,            // Tinggi barcode
                displayValue: true,    // Tampilkan teks label di bawah barcode
                fontSize: 14,          // Ukuran font teks label
                fontOptions: "bold",
                margin: 0             // Margin di sekitar barcode
            });

            document.getElementById('modal-barcode').classList.remove('hidden');
        }

        window.addEventListener('pageshow', function(event) {
            var isBackNavigation = event.persisted || (typeof window.performance !== "undefined" && window.performance.navigation.type === 2);
            if (isBackNavigation) { window.location.reload(); }
        });
    </script>
</body>
</html>