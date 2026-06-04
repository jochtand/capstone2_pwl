<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pengadaan Aset</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; font-size: 12px; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px; }
        .data-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .data-table th { background-color: #f2f2f2; font-weight: bold; }
        .status-setuju { color: green; font-weight: bold; }
        .status-tolak { color: red; font-weight: bold; }
        .footer { width: 100%; margin-top: 50px; text-align: right; }
        .signature { margin-top: 70px; font-weight: bold; text-decoration: underline; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Dokumen Finalisasi Pengadaan Aset & BHP</h1>
        <p>Sistem E-Procurement Laboratorium Tahun {{ $draft['tahun'] }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="20%"><strong>No. Draf</strong></td>
            <td width="30%">: #{{ $draft['id'] }}</td>
            <td width="20%"><strong>Status</strong></td>
            <td width="30%">: <span class="status-setuju">FINALIZED (DISETUJUI)</span></td>
        </tr>
        <tr>
            <td><strong>Diajukan Oleh</strong></td>
            <td>: {{ $draft['nama_kepala_lab'] }}</td>
            <td><strong>Tanggal Pengajuan</strong></td>
            <td>: {{ \Carbon\Carbon::parse($draft['tgl_pengajuan'])->format('d F Y') }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Barang / BHP</th>
                <th width="15%">Harga Satuan</th>
                <th width="10%">Jumlah</th>
                <th width="20%">Subtotal</th>
                <th width="15%">Keputusan</th>
            </tr>
        </thead>
        <tbody>
            @php $total_disetujui = 0; @endphp
            @foreach ($draft['items'] as $index => $item)
                @php 
                    $subtotal = $item['harga'] * $item['jumlah'];
                    if($item['status'] == 'Disetujui') {
                        $total_disetujui += $subtotal;
                    }
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['nama_barang'] }}</td>
                    <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                    <td>{{ $item['jumlah'] }}</td>
                    <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td>
                        @if($item['status'] == 'Disetujui')
                            <span class="status-setuju">Disetujui</span>
                        @else
                            <span class="status-tolak">Ditolak</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; font-weight: bold;">Total Anggaran Disetujui:</td>
                <td colspan="2" style="font-weight: bold; color: green;">Rp {{ number_format($total_disetujui, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Bandung, {{ date('d F Y') }}</p>
        <p>Mengetahui,</p>
        <div class="signature">Ketua Program Studi</div>
    </div>

</body>
</html>