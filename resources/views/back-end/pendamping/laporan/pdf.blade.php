<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 10px; color: #333; }
    h2 { text-align: center; margin-bottom: 2px; font-size: 14px; }
    .subtitle { text-align: center; font-size: 10px; color: #666; margin-bottom: 12px; }
    .info-row { margin-bottom: 8px; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    th { background: #2c3e50; color: #fff; padding: 5px 6px; text-align: left; font-size: 9px; }
    td { padding: 4px 6px; border-bottom: 1px solid #e0e0e0; font-size: 9px; vertical-align: top; }
    tr:nth-child(even) td { background: #f8f9fa; }
    .badge { padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
    .badge-mikro    { background: #d1ecf1; color: #0c5460; }
    .badge-kecil    { background: #d4edda; color: #155724; }
    .badge-menengah { background: #fff3cd; color: #856404; }
    .badge-none     { background: #e2e3e5; color: #383d41; }
    .text-right { text-align: right; }
    .footer { margin-top: 16px; font-size: 9px; color: #888; text-align: right; }
</style>
</head>
<body>

<h2>LAPORAN DATA UMKM</h2>
<p class="subtitle">Kabupaten Purworejo &mdash; Dinas Koperasi dan UMKM</p>

<div class="info-row">
    <strong>Pendamping :</strong> {{ $pendata->name }}<br>
    <strong>Total Data  :</strong> {{ $dataUmkm->count() }} UMKM<br>
    <strong>Dicetak     :</strong> {{ now()->translatedFormat('d F Y, H:i') }} WIB
</div>

<table>
    <thead>
        <tr>
            <th style="width:3%">No</th>
            <th style="width:13%">Nama Pemilik</th>
            <th style="width:11%">NIK</th>
            <th style="width:14%">Nama Usaha</th>
            <th style="width:10%">Kategori</th>
            <th style="width:9%">Kelas</th>
            <th style="width:10%">Kecamatan</th>
            <th style="width:5%">Karyw.</th>
            <th style="width:12%">Omset/Tahun (Rp)</th>
            <th style="width:10%">Legalitas</th>
            <th style="width:8%">Tgl Input</th>
        </tr>
    </thead>
    <tbody>
        @forelse($dataUmkm as $i => $item)
        @php
            $kelas = $item->kelasUsaha->nama ?? null;
            $badgeClass = match(true) {
                str_contains($kelas ?? '', 'Mikro')    => 'badge-mikro',
                str_contains($kelas ?? '', 'Kecil')    => 'badge-kecil',
                str_contains($kelas ?? '', 'Menengah') => 'badge-menengah',
                default => 'badge-none',
            };
        @endphp
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->pemilik->nama ?? '-' }}</td>
            <td>{{ $item->pemilik->nik ?? '-' }}</td>
            <td>{{ $item->nama_usaha }}</td>
            <td>{{ $item->kategoriUsaha->nama ?? '-' }}</td>
            <td><span class="badge {{ $badgeClass }}">{{ $kelas ?? '-' }}</span></td>
            <td>{{ $item->kecamatan_usaha ?? '-' }}</td>
            <td class="text-right">{{ number_format($item->karyawan ?? 0) }}</td>
            <td class="text-right">{{ number_format(($item->omset_bulanan_rp ?? 0) * 12, 0, ',', '.') }}</td>
            <td>{{ $item->legalitas->pluck('jenis')->join(', ') ?: '-' }}</td>
            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="11" style="text-align:center; padding:20px">Tidak ada data</td></tr>
        @endforelse
    </tbody>
</table>

<div class="footer">Dicetak oleh sistem SIMONDA &mdash; {{ now()->format('d/m/Y H:i') }}</div>

</body>
</html>
