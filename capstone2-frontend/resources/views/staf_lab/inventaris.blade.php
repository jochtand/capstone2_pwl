<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staf Lab - Manajemen Aset</title>
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
                @if(session('user.role') == 'Staf Laboratorium')
                    <a href="/staf-lab/inventaris" class="block px-4 py-2 bg-blue-600 rounded text-white shadow">Manajemen Aset & BHP</a>
                @endif
            </nav>
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold transition-colors">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto relative">
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Manajemen Aset & BHP</h1>
                    <p class="text-gray-500 mt-1">Halo, <strong>{{ session('user.nama') }}</strong>! Kelola stok dan pergantian barang rusak di sini.</p>
                </div>
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold border border-green-300">
                    Role: {{ session('user.role') }}
                </span>
            </header>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">{{ session('error') }}</div>
            @endif

            <div class="mb-10">
                <div class="flex justify-between items-end mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-blue-500 pl-3">Daftar Inventaris (Aset Tetap)</h2>
                    <button onclick="bukaModalAset()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow transition-colors">+ Tambah Aset Manual</button>
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden w-full">
                    <table class="min-w-full text-left table-fixed">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-4 font-semibold text-gray-600 text-center w-[8%]">No</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[27%]">Nama Barang</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[25%]">Ruangan</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[15%]">Kondisi</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[25%]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($inventaris as $i)
                            <tr class="hover:bg-gray-50 {{ $i['kondisi'] == 'Afkir' ? 'opacity-50 bg-gray-100' : '' }}">
                                <td class="px-4 py-4 text-center font-medium">{{ count($inventaris) - $loop->index }}</td>
                                <td class="px-6 py-4 font-bold truncate">{{ $i['nama_barang'] }}</td>
                                <td class="px-6 py-4 text-gray-600 truncate">{{ $i['nama_ruangan'] }}</td>
                                <td class="px-6 py-4">
                                    @if($i['kondisi'] == 'Baik') <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold uppercase">Baik</span>
                                    @elseif($i['kondisi'] == 'Rusak') <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-bold uppercase animate-pulse">Rusak</span>
                                    @else <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded text-xs font-bold uppercase line-through">Afkir</span> @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-row flex-nowrap justify-start items-center gap-2">
                                        <button onclick="bukaModalAset({{ $i['id'] }}, '{{ addslashes($i['nama_barang']) }}', {{ $i['ruangan_id'] }}, '{{ $i['kondisi'] }}')" class="w-[120px] text-center text-blue-600 hover:text-blue-800 font-semibold px-4 py-1.5 border border-blue-300 rounded transition-colors">Edit</button>
                                        
                                        @if($i['kondisi'] == 'Rusak')
                                            <button onclick="bukaModalReplace({{ $i['id'] }}, '{{ addslashes($i['nama_barang']) }}', '{{ $i['nama_ruangan'] }}')" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-1.5 rounded shadow text-sm transition-colors">Ganti Barang!</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada aset terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-end mb-4">
                    <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-green-500 pl-3">Daftar Stok BHP (Barang Habis Pakai)</h2>
                    <button onclick="bukaModalBhp()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow transition-colors">+ Tambah BHP</button>
                </div>
                <div class="bg-white rounded-lg shadow overflow-hidden w-full">
                    <table class="min-w-full text-left table-fixed">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-4 py-4 font-semibold text-gray-600 text-center w-[8%]">No</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[27%]">Nama BHP</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[25%]">Ruangan</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[15%]">Sisa Stok</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 w-[25%]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($bhp as $b)
                            <tr class="hover:bg-gray-50 {{ $b['stok'] == 0 ? 'bg-red-50' : '' }}">
                                <td class="px-4 py-4 text-center font-medium">{{ count($bhp) - $loop->index }}</td>
                                <td class="px-6 py-4 font-bold truncate">{{ $b['nama_barang'] }}</td>
                                <td class="px-6 py-4 text-gray-600 truncate">{{ $b['nama_ruangan'] }}</td>
                                <td class="px-6 py-4 font-mono font-bold {{ $b['stok'] == 0 ? 'text-red-600' : 'text-green-600' }}">{{ $b['stok'] }} Unit</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-row flex-nowrap justify-start items-center">
                                        <button onclick="bukaModalBhp({{ $b['id'] }}, '{{ addslashes($b['nama_barang']) }}', {{ $b['ruangan_id'] }}, {{ $b['stok'] }})" class="w-[120px] text-center text-green-600 hover:text-green-800 font-semibold border border-green-300 px-4 py-1.5 rounded transition-colors whitespace-nowrap">Update Stok</button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada BHP terdaftar.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="modal-aset" class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-50 p-4">
                <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl">
                    <h2 id="modal-aset-title" class="text-2xl font-bold mb-4 border-b pb-2">Form Aset</h2>
                    <form id="form-aset" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Aset</label>
                            <input type="text" id="aset_nama" name="nama_barang" class="border w-full py-2 px-3 rounded focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ruangan</label>
                            <select id="aset_ruangan" name="ruangan_id" class="border w-full py-2 px-3 rounded bg-white" required>
                                @foreach($ruangan as $r) <option value="{{ $r['id'] }}">{{ $r['nama_ruangan'] }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kondisi</label>
                            <select id="aset_kondisi" name="kondisi" class="border w-full py-2 px-3 rounded bg-white" required>
                                <option value="Baik">Baik</option>
                                <option value="Rusak">Rusak</option>
                                <option value="Afkir">Afkir (Buang)</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-aset').classList.add('hidden')" class="bg-gray-300 px-4 py-2 rounded font-semibold transition-colors">Batal</button>
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded font-bold shadow transition-colors">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modal-bhp" class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-50 p-4">
                <div class="bg-white rounded-xl p-8 w-full max-w-md shadow-2xl">
                    <h2 id="modal-bhp-title" class="text-2xl font-bold mb-4 border-b pb-2">Form BHP</h2>
                    <form id="form-bhp" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama BHP</label>
                            <input type="text" id="bhp_nama" name="nama_barang" class="border w-full py-2 px-3 rounded" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ruangan</label>
                            <select id="bhp_ruangan" name="ruangan_id" class="border w-full py-2 px-3 rounded bg-white" required>
                                @foreach($ruangan as $r) <option value="{{ $r['id'] }}">{{ $r['nama_ruangan'] }}</option> @endforeach
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jumlah Stok Baru</label>
                            <input type="number" id="bhp_stok" name="stok" class="border w-full py-2 px-3 rounded" min="0" required>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-bhp').classList.add('hidden')" class="bg-gray-300 px-4 py-2 rounded font-semibold transition-colors">Batal</button>
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded font-bold shadow transition-colors">Simpan Stok</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modal-replace" class="fixed inset-0 bg-black bg-opacity-60 hidden flex justify-center items-center z-50 p-4">
                <div class="bg-white rounded-xl p-8 w-full max-w-lg shadow-2xl">
                    <h2 class="text-2xl font-bold mb-2 text-red-600 border-b pb-2">Proses Ganti Barang (Replace)</h2>
                    <p class="text-sm text-gray-600 mb-4">Barang rusak di <strong id="replace_ruang_lama"></strong> akan dijadikan "Afkir", dan diganti dengan barang baru yang kondisinya baik.</p>
                    
                    <form id="form-replace" method="POST">
                        @csrf
                        <div class="bg-red-50 p-4 rounded-lg mb-4 border border-red-100">
                            <p class="text-sm text-gray-500 font-semibold">Barang Rusak:</p>
                            <p id="replace_nama_lama" class="text-lg font-bold text-red-800 line-through"></p>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Barang Baru (Pengganti)</label>
                            <select name="idBaru" class="border w-full max-w-full py-2 px-3 rounded bg-white shadow-sm focus:ring-2 focus:ring-red-500 text-sm overflow-hidden text-ellipsis" required>
                                <option value="">-- Pilih Barang dari Gudang --</option>
                                @foreach($inventaris as $i)
                                    @if($i['kondisi'] == 'Baik')
                                        <option value="{{ $i['id'] }}">{{ $i['nama_barang'] }} (Lokasi Asal: {{ $i['nama_ruangan'] }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="document.getElementById('modal-replace').classList.add('hidden')" class="bg-gray-300 px-4 py-2 rounded font-semibold transition-colors">Batal</button>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-4 py-2 rounded shadow flex items-center gap-2 transition-colors">Eksekusi Replace!</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function bukaModalAset(id = null, nama = '', ruangan = '', kondisi = 'Baik') {
            let form = document.getElementById('form-aset');
            form.action = id ? '/staf-lab/inventaris/' + id + '/update' : '/staf-lab/inventaris';
            document.getElementById('modal-aset-title').innerText = id ? 'Edit Aset Tetap' : 'Tambah Aset Baru';
            
            document.getElementById('aset_nama').value = nama;
            if(ruangan) document.getElementById('aset_ruangan').value = ruangan;
            document.getElementById('aset_kondisi').value = kondisi;
            
            document.getElementById('modal-aset').classList.remove('hidden');
        }

        function bukaModalBhp(id = null, nama = '', ruangan = '', stok = 0) {
            let form = document.getElementById('form-bhp');
            form.action = id ? '/staf-lab/bhp/' + id + '/update' : '/staf-lab/bhp';
            document.getElementById('modal-bhp-title').innerText = id ? 'Update Stok BHP' : 'Tambah Jenis BHP Baru';
            
            document.getElementById('bhp_nama').value = nama;
            if(ruangan) document.getElementById('bhp_ruangan').value = ruangan;
            document.getElementById('bhp_stok').value = stok;
            
            document.getElementById('modal-bhp').classList.remove('hidden');
        }

        function bukaModalReplace(idLama, namaLama, ruangLama) {
            document.getElementById('form-replace').action = '/staf-lab/inventaris/' + idLama + '/replace';
            document.getElementById('replace_nama_lama').innerText = namaLama;
            document.getElementById('replace_ruang_lama').innerText = ruangLama;
            document.getElementById('modal-replace').classList.remove('hidden');
        }

        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (typeof window.performance !== "undefined" && window.performance.navigation.type === 2)) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>