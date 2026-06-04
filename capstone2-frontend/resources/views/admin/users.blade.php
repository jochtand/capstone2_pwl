<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator - Kelola Pengguna</title>
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
                    <a href="/admin/users" class="block px-4 py-2 bg-blue-600 text-white rounded shadow">Kelola Pengguna</a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold transition-colors">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto relative">
            
            <header class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold">Daftar Pengguna Sistem</h1>
                    <p class="text-gray-500 mt-1">Halo, <strong>{{ session('user.nama') }}</strong>! Selamat bekerja.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold border border-green-300">
                        Role: {{ session('user.role') }}
                    </span>
                    <button onclick="document.getElementById('modal-tambah').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow font-semibold transition-transform transform hover:scale-105">
                        + Tambah Pengguna
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
                            <th class="px-6 py-4 font-semibold text-gray-600 w-16">ID</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Nama Lengkap</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Email</th>
                            <th class="px-6 py-4 font-semibold text-gray-600">Role</th>
                            <th class="px-6 py-4 font-semibold text-gray-600 text-center w-48">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($users as $u)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-gray-700">{{ $u['id'] }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">{{ $u['nama'] }}</td>
                            <td class="px-6 py-4 text-blue-600">{{ $u['email'] }}</td>
                            <td class="px-6 py-4">
                                <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs font-bold uppercase">{{ $u['role'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <button onclick="editUser({{ $u['id'] }}, '{{ addslashes($u['nama']) }}', '{{ addslashes($u['email']) }}', '{{ $u['role'] }}')" class="text-blue-600 hover:text-blue-800 font-medium bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded transition-colors">Edit</button>
                                
                                <form action="/admin/users/{{ $u['id'] }}/delete" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-medium bg-red-50 hover:bg-red-100 px-3 py-1 rounded transition-colors" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data pengguna.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="modal-tambah" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Tambah Pengguna Baru</h2>
                    <form action="/admin/users" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="nama" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" name="email" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                            <input type="password" name="password" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Role Akses</label>
                            <select name="role" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Administrator">Administrator</option>
                                <option value="Kepala Laboratorium">Kepala Laboratorium</option>
                                <option value="Kaprodi">Ketua Program Studi (Kaprodi)</option>
                                <option value="Staf Administrasi">Staf Administrasi</option>
                                <option value="Staf Laboratorium">Staf Laboratorium</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-tambah').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded transition-colors">Batal</button>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded transition-colors shadow">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="modal-edit-user" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center z-50 transition-opacity">
                <div class="bg-white rounded-lg p-8 w-full max-w-md shadow-2xl">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Edit Pengguna</h2>
                    <form id="edit-form-user" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="edit_nama" name="nama" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                            <input type="email" id="edit_email" name="email" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Role Akses</label>
                            <select id="edit_role" name="role" class="border border-gray-300 w-full py-2 px-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="Administrator">Administrator</option>
                                <option value="Kepala Laboratorium">Kepala Laboratorium</option>
                                <option value="Kaprodi">Ketua Program Studi (Kaprodi)</option>
                                <option value="Staf Administrasi">Staf Administrasi</option>
                                <option value="Staf Laboratorium">Staf Laboratorium</option>
                            </select>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-4 py-2 rounded transition-colors">Batal</button>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white font-semibold px-4 py-2 rounded transition-colors shadow">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function editUser(id, nama, email, role) {
            // Ubah action form sesuai ID pengguna yang dipilih
            document.getElementById('edit-form-user').action = '/admin/users/' + id + '/update';
            // Isi form dengan data lama
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            // Tampilkan modal edit
            document.getElementById('modal-edit-user').classList.remove('hidden');
        }
    </script>
</body>
</html>