<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Barang & BHP</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <div class="flex h-screen">
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-6 border-b border-gray-800"><h2 class="text-2xl font-bold">Lab Aset</h2></div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="javascript:history.back()" class="block px-4 py-2 hover:bg-gray-800 rounded text-gray-300">&larr; Kembali</a>
                <a href="#" class="block px-4 py-2 bg-blue-600 text-white rounded">Cek Barang & BHP</a>
            </nav>
        </aside>
        <main class="flex-1 p-8 overflow-y-auto">
            <h1 class="text-3xl font-bold mb-6">Database Inventaris & BHP Aktif</h1>
            <div class="grid grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Aset Inventaris</h3>
                    <table class="w-full text-left text-sm">
                        <thead><tr class="bg-gray-50"><th class="p-2">ID</th><th class="p-2">Nama Barang</th><th class="p-2">Kondisi</th></tr></thead>
                        <tbody>
                            @foreach ($inventaris as $inv)
                            <tr class="border-b"><td class="p-2">INV-{{ $inv['id'] }}</td><td class="p-2">{{ $inv['nama_barang'] }}</td><td class="p-2 font-bold {{ $inv['kondisi'] == 'Baik' ? 'text-green-600' : 'text-red-600' }}">{{ $inv['kondisi'] }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Stok Barang Habis Pakai (BHP)</h3>
                    <table class="w-full text-left text-sm">
                        <thead><tr class="bg-gray-50"><th class="p-2">ID</th><th class="p-2">Nama BHP</th><th class="p-2">Sisa Stok</th></tr></thead>
                        <tbody>
                            @foreach ($bhp as $b)
                            <tr class="border-b"><td class="p-2">BHP-{{ $b['id'] }}</td><td class="p-2">{{ $b['nama_barang'] }}</td><td class="p-2 font-bold text-blue-600">{{ $b['stok'] }} unit</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>