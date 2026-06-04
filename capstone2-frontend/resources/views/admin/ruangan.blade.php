<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator - Kelola Ruangan</title>
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
                    <a href="/admin/ruangan" class="block px-4 py-2 bg-blue-600 text-white rounded shadow">Kelola Ruangan</a>
                    <a href="/admin/users" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300 transition-colors">Kelola Pengguna</a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold transition-colors">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto relative">
            
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Daftar Ruangan Laboratorium</h1>
                    <p class="text-gray-500 mt-1">Halo, <strong>{{ session('user.nama') }}</strong>! Atur data master ruangan di sini.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold border border-green-300">
                        Role: {{ session('user.role') }}
                    </span>
                    <button onclick="document.getElementById('modal-ruangan').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-semibold transition-transform transform hover:scale-105">
                        + Tambah Ruangan
                    </button>
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

            <div class="bg-white rounded-lg shadow overflow-hidden w-full">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="px-6 py-4 font-semibold text-gray-600 w-24">ID</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Nama Ruangan Lab</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($ruangans as $r)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-700">#{{ $r['id'] }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $r['nama_ruangan'] }}</td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button onclick="editRuangan({{ $r['id'] }}, '{{ addslashes($r['nama_ruangan']) }}')" class="text-blue-600 hover:text-blue-800 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition-colors">Edit</button>
                                
                                <form action="/admin/ruangan/{{ $r['id'] }}/delete" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition-colors" onclick="return confirm('Yakin ingin menghapus ruangan ini? Pastikan tidak ada barang di dalamnya.')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data ruangan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="modal-ruangan" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Ruangan Baru</h2>
                    <form action="/admin/ruangan" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ruangan / Laboratorium</label>
                            <input type="text" name="nama_ruangan" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Cth: Lab Komputer 3" required>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-ruangan').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded transition-colors">Batal</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded transition-colors shadow">Simpan Ruangan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modal-edit-ruangan" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Ruangan</h2>
                    <form id="edit-form-ruangan" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Ruangan</label>
                            <input type="text" id="edit_nama_ruangan" name="nama_ruangan" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-edit-ruangan').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded transition-colors">Batal</button>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded transition-colors shadow">Update Ruangan</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function editRuangan(id, nama) {
            // Ubah action form sesuai ID ruangan yang dipilih
            document.getElementById('edit-form-ruangan').action = '/admin/ruangan/' + id + '/update';
            // Isi form dengan nama yang lama
            document.getElementById('edit_nama_ruangan').value = nama;
            // Tampilkan modal edit
            document.getElementById('modal-edit-ruangan').classList.remove('hidden');
        }
    </script>
</body>
</html>