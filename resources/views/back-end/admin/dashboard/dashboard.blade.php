@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-statistics.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-analytics.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
<div class="row gy-6">
    <!-- Greeting Card -->
    <div class="col-md-12 col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-0">Selamat Datang, {{ auth()->user()->name }}! 🎉</h5>
                <p class="mb-2">Dashboard Admin SIMONDA</p>
                <h4 class="text-primary mb-0" style="font-size: clamp(1rem, 3vw, 1.5rem);">Sistem Informasi Monitoring Data UMKM</h4>
            </div>
        </div>
    </div>
    <!--/ Greeting Card -->
    
    <!-- Statistics -->
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="card-info">
                        <p class="card-text">Total UMKM</p>
                        <div class="d-flex align-items-end mb-2">
                            <h4 class="card-title mb-0 me-2">{{ number_format($totalUmkm) }}</h4>
                        </div>
                        <small>Data UMKM Terdaftar</small>
                    </div>
                    <div class="card-icon">
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="ri-store-2-line ri-26px"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Statistics -->
</div>

<div class="row gy-6 mt-4">
    <!-- Data UMKM Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ri-store-2-line ri-24px"></i>
                        </span>
                    </div>
                </div>
                <p class="mb-1">Total UMKM</p>
                <h4 class="card-title mb-3">{{ number_format($totalUmkm) }}</h4>
                <small class="text-muted">Data UMKM Terdaftar</small>
            </div>
        </div>
    </div>
    
    <!-- Pengguna Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="ri-user-line ri-24px"></i>
                        </span>
                    </div>
                </div>
                <p class="mb-1">Total Pengguna</p>
                <h4 class="card-title mb-3">{{ number_format($totalPendamping) }}</h4>
                <small class="text-muted">Pendamping Aktif</small>
            </div>
        </div>
    </div>
    
    <!-- Kategori Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ri-list-check ri-24px"></i>
                        </span>
                    </div>
                </div>
                <p class="mb-1">Total Karyawan</p>
                <h4 class="card-title mb-3">{{ number_format($totalKaryawan) }}</h4>
                <small class="text-muted">Dari Semua UMKM</small>
            </div>
        </div>
    </div>
    
    <!-- Laporan Card -->
    <div class="col-md-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="ri-file-chart-line ri-24px"></i>
                        </span>
                    </div>
                </div>
                <p class="mb-1">Total Omset/Tahun</p>
                <h4 class="card-title mb-3" style="font-size:1rem;">Rp {{ number_format($totalOmset * 12, 0, ',', '.') }}</h4>
                <small class="text-muted">Dari Semua UMKM</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row mt-6">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                <small class="text-muted">{{ $aktivitasTerbaru->total() }} total aktivitas</small>
            </div>
            <div class="card-body p-0">
                @forelse($aktivitasTerbaru as $log)
                <div class="d-flex align-items-start px-4 py-3 border-bottom" id="log-row-{{ $log->id }}">
                    <div class="me-3 mt-1">
                        @if($log->action === 'created')
                            <span class="badge bg-label-success rounded-pill p-2"><i class="ri-add-line"></i></span>
                        @elseif($log->action === 'updated')
                            <span class="badge bg-label-warning rounded-pill p-2"><i class="ri-edit-line"></i></span>
                        @else
                            <span class="badge bg-label-danger rounded-pill p-2"><i class="ri-delete-bin-line"></i></span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <p class="mb-0">{{ $log->description }}</p>
                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="ms-2">
                        <button class="btn btn-sm btn-icon btn-text-danger btn-hapus-log"
                            data-id="{{ $log->id }}"
                            title="Hapus">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="ri-file-list-3-line ri-48px text-muted mb-3 d-block"></i>
                    <p class="text-muted">Belum ada aktivitas</p>
                </div>
                @endforelse
            </div>
            @if($aktivitasTerbaru->hasPages())
            <div class="card-footer d-flex justify-content-center pt-3">
                {{ $aktivitasTerbaru->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
document.querySelectorAll('.btn-hapus-log').forEach(btn => {
    btn.addEventListener('click', function () {
        const id  = this.dataset.id;
        const row = document.getElementById('log-row-' + id);

        Swal.fire({
            title: 'Hapus Aktivitas?',
            text: 'Log aktivitas ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: false,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
        }).then(result => {
            if (!result.isConfirmed) return;

            fetch(`/admin/notifications/${id}`, {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        text: 'Log aktivitas berhasil dihapus.',
                        timer: 1500,
                        showConfirmButton: false,
                    });
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
