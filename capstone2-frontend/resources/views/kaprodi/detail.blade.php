<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Detail Draf - Kaprodi</title>
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
                <a href="/kaprodi/review" class="block px-4 py-2 bg-blue-600 rounded text-white">Review Kaprodi</a>
            </nav>
            <div class="p-4 border-t border-gray-800 mt-auto">
                <a href="/logout" class="block px-4 py-2 bg-red-600 hover:bg-red-700 rounded text-white text-center font-bold">Logout</a>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            
            <header class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Review Detail Draf #{{ $draft['id'] }}</h1>
                    <p class="text-gray-500 mt-1">Diajukan oleh: <strong>{{ $draft['nama_kepala_lab'] }}</strong> pada {{ \Carbon\Carbon::parse($draft['tgl_pengajuan'])->format('d F Y') }}</p>
                </div>
                <a href="/kaprodi/review" class="text-gray-600 hover:text-gray-900 font-semibold underline">&larr; Kembali</a>
            </header>

            <form action="/kaprodi/draft/{{ $draft['id'] }}/finalize" method="POST">
                @csrf
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <table class="min-w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-4 font-semibold text-gray-600">No</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Nama Barang</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Harga Satuan</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Jumlah</th>
                                <th class="px-6 py-4 font-semibold text-gray-600">Subtotal</th>
                                <th class="px-6 py-4 font-semibold text-gray-600 bg-blue-50 text-center">Keputusan Review</th>
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
                                    
                                    <td class="px-6 py-4 bg-blue-50">
                                        @if($draft['status'] == 'Locked')
                                            <div class="flex items-center justify-center gap-4">
                                                <label class="flex items-center gap-1 text-green-700 font-bold cursor-pointer">
                                                    <input type="radio" name="items[{{ $item['id'] }}]" value="Disetujui" required class="w-4 h-4 text-green-600"> Setuju
                                                </label>
                                                <label class="flex items-center gap-1 text-red-700 font-bold cursor-pointer">
                                                    <input type="radio" name="items[{{ $item['id'] }}]" value="Ditolak" required class="w-4 h-4 text-red-600"> Tolak
                                                </label>
                                            </div>
                                        @else
                                            <div class="text-center font-bold">
                                                @if($item['status'] == 'Disetujui')
                                                    <span class="text-green-600">✓ Disetujui</span>
                                                @elseif($item['status'] == 'Ditolak')
                                                    <span class="text-red-600">✕ Ditolak</span>
                                                @else
                                                    <span class="text-gray-500">-</span>
                                                @endif
                                            </div>
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
                                <td colspan="4" class="px-6 py-4 text-right font-bold text-gray-700 text-lg">Estimasi Total Anggaran:</td>
                                <td class="px-6 py-4 font-bold text-blue-700 text-lg">Rp {{ number_format($total_semua, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($draft['status'] == 'Locked')
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded shadow-lg transition-transform transform hover:scale-105" onclick="return confirm('Apakah Anda yakin dengan keputusan review ini? Setelah difinalisasi, draf tidak bisa diubah kembali.')">
                        Simpan & Finalisasi Draf
                    </button>
                </div>
                @endif
            </form>

        </main>
    </div>
</body>
</html>