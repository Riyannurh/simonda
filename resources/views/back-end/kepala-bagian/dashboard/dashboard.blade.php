@extends('layouts.app')

@section('title', 'Dashboard Kepala Bagian')

@section('content')

{{-- Greeting --}}
<div class="row gy-4 mb-4">
    <div class="col-12">
        <div class="card bg-dark text-white">
            <div class="card-body d-flex justify-content-between align-items-center py-4">
                <div>
                    <h5 class="text-white mb-1">Selamat Datang, {{ auth()->user()->name }} 📊</h5>
                    <p class="mb-0 opacity-75">Monitoring & Evaluasi UMKM — Kabupaten Purworejo</p>
                </div>
                <div class="d-none d-md-block">
                    <i class="ri-bar-chart-2-line" style="font-size: 3rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Summary Cards --}}
<div class="row gy-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-primary rounded flex-shrink-0 d-flex align-items-center justify-content-center">
                    <i class="ri-store-3-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total UMKM</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalUmkm) }}</h4>
                    <small class="text-success">+{{ $bulanIni }} bulan ini</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-info rounded flex-shrink-0 d-flex align-items-center justify-content-center">
                    <i class="ri-user-star-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Pendamping</p>
                    <h4 class="mb-0 fw-bold">{{ $totalPendamping }}</h4>
                    <small class="text-muted">Aktif bertugas</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-warning rounded flex-shrink-0 d-flex align-items-center justify-content-center">
                    <i class="ri-team-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Karyawan</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalKaryawan) }}</h4>
                    <small class="text-muted">Dari seluruh UMKM</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-success rounded flex-shrink-0 d-flex align-items-center justify-content-center">
                    <i class="ri-money-dollar-circle-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Omset/Tahun</p>
                    <h5 class="mb-0 fw-bold">Rp {{ number_format($totalOmsetTahunan, 0, ',', '.') }}</h5>
                    <small class="text-muted">Bulanan: Rp {{ number_format($totalOmsetBulanan, 0, ',', '.') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 1 --}}
<div class="row gy-4 mb-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Tren Pendataan UMKM (6 Bulan Terakhir)</h6>
                <small class="text-muted">Bulan ini: <strong>{{ $bulanIni }}</strong> | Bulan lalu: <strong>{{ $bulanLalu }}</strong></small>
            </div>
            <div class="card-body">
                <canvas id="chartBulan" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">Distribusi Kelas Usaha</h6></div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($chartKelas->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="ri-pie-chart-line ri-48px d-block mb-2"></i>Belum ada data
                    </div>
                @else
                    <canvas id="chartKelas" height="220"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Charts Row 2 --}}
<div class="row gy-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">UMKM per Kecamatan (Top 10)</h6></div>
            <div class="card-body">
                <canvas id="chartKecamatan" height="280"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">Input per Pendamping (Top 10)</h6></div>
            <div class="card-body">
                <canvas id="chartPendamping" height="280"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Top Pendamping Bulan Ini --}}
<div class="row gy-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Top Pendamping Bulan Ini</h6>
                <span class="badge bg-label-primary">{{ now()->translatedFormat('F Y') }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Pendamping</th>
                                <th>Wilayah</th>
                                <th class="text-end">Input</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPendamping as $i => $p)
                            <tr>
                                <td>
                                    @if($i === 0) <span class="badge bg-warning">🥇</span>
                                    @elseif($i === 1) <span class="badge bg-secondary">🥈</span>
                                    @elseif($i === 2) <span class="badge bg-label-warning">🥉</span>
                                    @else {{ $i + 1 }}
                                    @endif
                                </td>
                                <td>{{ $p->name }}</td>
                                <td><small class="text-muted">{{ $p->wilayah->nama ?? '-' }}</small></td>
                                <td class="text-end"><span class="badge bg-label-primary">{{ $p->bulan_ini }}</span></td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data bulan ini</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Ringkasan Cepat</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-store-3-line text-primary ri-20px"></i>
                        <span>Total UMKM Terdaftar</span>
                    </div>
                    <strong>{{ number_format($totalUmkm) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-calendar-check-line text-success ri-20px"></i>
                        <span>Input Bulan Ini</span>
                    </div>
                    <strong class="text-success">{{ $bulanIni }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-calendar-line text-muted ri-20px"></i>
                        <span>Input Bulan Lalu</span>
                    </div>
                    <strong>{{ $bulanLalu }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-user-star-line text-info ri-20px"></i>
                        <span>Jumlah Pendamping</span>
                    </div>
                    <strong>{{ $totalPendamping }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-team-line text-warning ri-20px"></i>
                        <span>Total Karyawan UMKM</span>
                    </div>
                    <strong>{{ number_format($totalKaryawan) }}</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center py-3 border-top">
                    <div class="d-flex align-items-center gap-2">
                        <i class="ri-funds-line text-success ri-20px"></i>
                        <span>Omset/Tahun</span>
                    </div>
                    <strong class="text-success">Rp {{ number_format($totalOmsetTahunan, 0, ',', '.') }}</strong>
                </div>
                <div class="mt-4 d-flex gap-2">
                    <a href="{{ route('kepala-bagian.data-umkm.index') }}" class="btn btn-outline-primary btn-sm w-100">
                        <i class="ri-store-3-line me-1"></i> Data UMKM
                    </a>
                    <a href="{{ route('kepala-bagian.laporan.index') }}" class="btn btn-primary btn-sm w-100">
                        <i class="ri-file-chart-line me-1"></i> Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
<script>
const chartBulanData      = @json($chartBulan);
const chartKelasData      = @json($chartKelas);
const chartKecamatanData  = @json($chartKecamatan);
const chartPendampingData = @json($chartPendamping);

new Chart(document.getElementById('chartBulan'), {
    type: 'bar',
    data: {
        labels: chartBulanData.map(d => d.bulan),
        datasets: [{ label: 'UMKM Diinput', data: chartBulanData.map(d => d.total), backgroundColor: '#696cff', borderRadius: 6 }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

@if($chartKelas->isNotEmpty())
new Chart(document.getElementById('chartKelas'), {
    type: 'doughnut',
    data: {
        labels: chartKelasData.map(d => d.nama),
        datasets: [{ data: chartKelasData.map(d => d.total), backgroundColor: ['#696cff','#03c3ec','#71dd37','#ffab00','#ff3e1d'] }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
@endif

new Chart(document.getElementById('chartKecamatan'), {
    type: 'bar',
    data: {
        labels: chartKecamatanData.map(d => d.kecamatan),
        datasets: [{ label: 'UMKM', data: chartKecamatanData.map(d => d.total), backgroundColor: '#03c3ec', borderRadius: 4 }]
    },
    options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});

new Chart(document.getElementById('chartPendamping'), {
    type: 'bar',
    data: {
        labels: chartPendampingData.map(d => d.nama),
        datasets: [{ label: 'Total Input', data: chartPendampingData.map(d => d.total), backgroundColor: '#71dd37', borderRadius: 4 }]
    },
    options: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
});
</script>
@endpush
