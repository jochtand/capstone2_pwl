<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Draf Baru - Lab Aset</title>
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
                <a href="/kepala-lab/draft" class="block px-4 py-2 bg-blue-600 rounded text-white">Draf Pengadaan (Kepala Lab)</a>
            </nav>
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Buat Draf Pengadaan Baru</h1>
                    <p class="text-gray-500 mt-1">Formulir pengajuan aset dan BHP</p>
                </div>
                <a href="/kepala-lab/draft" class="text-gray-600 hover:text-gray-900 font-semibold underline">&larr; Kembali</a>
            </header>

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <form action="/kepala-lab/draft" method="POST" class="bg-white rounded-lg shadow p-6">
                @csrf
                
                <div class="mb-6 w-1/4">
                    <label class="block text-gray-700 font-bold mb-2">Tahun Pengajuan</label>
                    <input type="number" name="tahun" value="{{ date('Y') }}" class="border rounded w-full py-2 px-3 text-gray-700 focus:ring-blue-500" required>
                </div>

                <hr class="mb-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Daftar Barang</h3>
                    <button type="button" id="btn-tambah-barang" class="bg-blue-100 text-blue-700 hover:bg-blue-200 px-4 py-2 rounded font-semibold text-sm">
                        + Tambah Baris Barang
                    </button>
                </div>

                <div id="container-barang" class="space-y-4">
                    <div class="baris-barang flex gap-4 items-end bg-gray-50 p-4 rounded border border-gray-200">
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Nama Barang *</label>
                            <input type="text" name="nama_barang[]" class="border rounded w-full py-2 px-3 text-sm" required placeholder="Cth: PC All-in-One">
                        </div>
                        <div class="w-32">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Harga *</label>
                            <input type="number" name="harga[]" class="border rounded w-full py-2 px-3 text-sm" required placeholder="15000000">
                        </div>
                        <div class="w-24">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Jumlah *</label>
                            <input type="number" name="jumlah[]" class="border rounded w-full py-2 px-3 text-sm" required placeholder="5">
                        </div>
                        <div class="flex-1">
                            <label class="block text-xs font-bold text-gray-600 mb-1">Link Pembelian (Opsional)</label>
                            <input type="text" name="link_pembelian[]" class="border rounded w-full py-2 px-3 text-sm" placeholder="https://tokopedia.com/...">
                        </div>
                        <div>
                            <button type="button" class="btn-hapus bg-red-100 text-red-600 hover:bg-red-200 px-3 py-2 rounded font-bold text-sm" onclick="hapusBaris(this)" disabled>X</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded shadow-lg transition-transform transform hover:scale-105">
                        Simpan Draf Pengajuan
                    </button>
                </div>
            </form>
        </main>
    </div>

    <script>
        const container = document.getElementById('container-barang');
        const btnTambah = document.getElementById('btn-tambah-barang');

        btnTambah.addEventListener('click', function() {
            // Ambil baris pertama sebagai template
            const barisPertama = container.querySelector('.baris-barang');
            const barisBaru = barisPertama.cloneNode(true);
            
            // Kosongkan semua input di baris baru
            const inputs = barisBaru.querySelectorAll('input');
            inputs.forEach(input => input.value = '');

            // Aktifkan tombol hapus di baris baru
            const btnHapus = barisBaru.querySelector('.btn-hapus');
            btnHapus.disabled = false;

            // Tambahkan ke container
            container.appendChild(barisBaru);
            updateTombolHapus();
        });

        function hapusBaris(btn) {
            const baris = btn.closest('.baris-barang');
            if (container.children.length > 1) {
                baris.remove();
                updateTombolHapus();
            }
        }

        // Jangan biarkan baris terakhir dihapus
        function updateTombolHapus() {
            const barisList = container.querySelectorAll('.baris-barang');
            const barisPertamaBtnHapus = barisList[0].querySelector('.btn-hapus');
            
            if (barisList.length === 1) {
                barisPertamaBtnHapus.disabled = true;
                barisPertamaBtnHapus.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                barisList.forEach(baris => {
                    const btn = baris.querySelector('.btn-hapus');
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                });
            }
        }
    </script>
</body>
</html>