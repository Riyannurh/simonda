@extends('layouts.app')

@section('title', 'Dashboard Pendamping')

@section('content')

{{-- Greeting --}}
<div class="row gy-4 mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center py-4">
                <div>
                    <h5 class="text-white mb-1">Selamat Datang, {{ auth()->user()->name }} 👋</h5>
                    <p class="mb-0 opacity-75">
                        @if(auth()->user()->wilayah)
                            Pendamping Kecamatan {{ auth()->user()->wilayah->nama }}
                        @else
                            Dashboard Pendamping SIMONDA
                        @endif
                    </p>
                </div>
                <div class="d-none d-md-block">
                    <i class="ri-user-star-line" style="font-size: 3rem; opacity: 0.4;"></i>
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
                    <p class="mb-0 text-muted small">Total UMKM Saya</p>
                    <h4 class="mb-0 fw-bold">{{ $totalUmkm }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-success rounded flex-shrink-0 d-flex align-items-center justify-content-center">
                    <i class="ri-calendar-check-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Input Bulan Ini</p>
                    <h4 class="mb-0 fw-bold">{{ $bulanIni }}</h4>
                    <small class="text-muted">Bulan lalu: {{ $bulanLalu }}</small>
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
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card h-100">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-info rounded flex-shrink-0 d-flex align-items-center justify-content-center">
                    <i class="ri-money-dollar-circle-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Omset/Tahun</p>
                    <h5 class="mb-0 fw-bold">Rp {{ number_format($totalOmsetTahunan, 0, ',', '.') }}</h5>
                    <small class="text-muted">Bulanan: Rp {{ number_format($totalOmset, 0, ',', '.') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row gy-4 mb-4">
    <div class="col-md-7">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Tren Input Data (6 Bulan Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="chartBulan" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Distribusi Kelas Usaha</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                @if($chartKelas->isEmpty())
                    <div class="text-center text-muted py-4">
                        <i class="ri-pie-chart-line ri-48px d-block mb-2"></i>
                        Belum ada data
                    </div>
                @else
                    <canvas id="chartKelas" height="220"></canvas>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Recent UMKM --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0">UMKM Terbaru yang Diinput</h6>
        <a href="{{ route('pendamping.data-umkm.index') }}" class="btn btn-sm btn-primary">
            <i class="ri-arrow-right-line me-1"></i> Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if($recentUmkm->isEmpty())
            <div class="text-center py-5">
                <i class="ri-store-3-line ri-48px text-muted d-block mb-2"></i>
                <p class="text-muted mb-3">Belum ada data UMKM yang diinput</p>
                <a href="{{ route('pendamping.data-umkm.index') }}" class="btn btn-primary btn-sm">
                    <i class="ri-add-line me-1"></i> Tambah Data UMKM
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nama Usaha</th>
                            <th>Pemilik</th>
                            <th>Kelas</th>
                            <th>Kecamatan</th>
                            <th>Tgl Input</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentUmkm as $item)
                        <tr id="umkm-row-{{ $item->id }}">
                            <td>
                                <span class="fw-medium">{{ $item->nama_usaha }}</span>
                                @if($item->merek)
                                    <br><small class="text-muted">{{ $item->merek }}</small>
                                @endif
                            </td>
                            <td>{{ $item->pemilik->nama ?? '-' }}</td>
                            <td>
                                @if($item->kelasUsaha)
                                    <span class="badge bg-label-primary">{{ $item->kelasUsaha->nama }}</span>
                                @else
                                    <span class="badge bg-label-secondary">-</span>
                                @endif
                            </td>
                            <td>{{ $item->kecamatan_usaha ?? '-' }}</td>
                            <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <button class="btn btn-sm btn-icon btn-text-danger btn-hapus-umkm"
                                    data-id="{{ $item->id }}"
                                    data-nama="{{ $item->nama_usaha }}"
                                    title="Hapus">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    @if($recentUmkm->hasPages())
    <div class="card-footer d-flex justify-content-center pt-3">
        {{ $recentUmkm->links() }}
    </div>
    @endif
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
const chartBulanData = @json($chartBulan);
const chartKelasData = @json($chartKelas);

// Chart tren bulanan
new Chart(document.getElementById('chartBulan'), {
    type: 'bar',
    data: {
        labels: chartBulanData.map(d => d.bulan),
        datasets: [{
            label: 'UMKM Diinput',
            data: chartBulanData.map(d => d.total),
            backgroundColor: '#696cff',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// Chart kelas usaha
@if($chartKelas->isNotEmpty())
new Chart(document.getElementById('chartKelas'), {
    type: 'doughnut',
    data: {
        labels: chartKelasData.map(d => d.nama),
        datasets: [{
            data: chartKelasData.map(d => d.total),
            backgroundColor: ['#696cff', '#03c3ec', '#71dd37', '#ffab00', '#ff3e1d'],
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
@endif
</script>

<script>
document.querySelectorAll('.btn-hapus-umkm').forEach(btn => {
    btn.addEventListener('click', function () {
        const id   = this.dataset.id;
        const nama = this.dataset.nama;
        const row  = document.getElementById('umkm-row-' + id);

        Swal.fire({
            title: 'Hapus UMKM?',
            text: `"${nama}" akan dihapus permanen.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ff3e1d',
            cancelButtonColor: '#8592a3',
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch(`/pendamping/data-umkm/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    row.remove();
                    Swal.fire({ icon: 'success', title: 'Terhapus', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire('Gagal', data.message, 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Terjadi kesalahan.', 'error'));
        });
    });
});
</script>
@endpush
