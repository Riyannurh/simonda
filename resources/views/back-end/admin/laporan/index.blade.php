@extends('layouts.app')

@section('title', 'Laporan UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6 flex-wrap gap-2">
    <h4 class="mb-0">Laporan UMKM</h4>
    <div class="d-flex gap-2 flex-wrap">
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="printLaporan()">
            <i class="ri-printer-line me-1"></i><span class="d-none d-sm-inline">Cetak</span>
        </button>
        <button type="button" class="btn btn-success btn-sm" onclick="openExportModal('excel')">
            <i class="ri-file-excel-2-line me-1"></i><span class="d-none d-sm-inline">Excel</span>
        </button>
        <button type="button" class="btn btn-danger btn-sm" onclick="openExportModal('pdf')">
            <i class="ri-file-pdf-line me-1"></i><span class="d-none d-sm-inline">PDF</span>
        </button>
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleFilter()" id="btnToggleFilter">
            <i class="ri-filter-3-line me-1"></i><span class="d-none d-sm-inline">Filter</span>
        </button>
    </div>
</div>

<!-- Modal Export -->
<div class="modal fade" id="modalExport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExportTitle">Export Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-4">Pilih cakupan data yang ingin di-export:</p>
                <div class="d-grid gap-3">
                    <button type="button" class="btn btn-outline-primary btn-lg" onclick="doExport('semua')">
                        <i class="ri-database-2-line me-2"></i>
                        <strong>Semua Data</strong>
                        <div class="small text-muted mt-1">Export seluruh data UMKM tanpa filter pendata</div>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg" id="btnExportPendata" onclick="doExport('pendata')">
                        <i class="ri-user-line me-2"></i>
                        <strong>Sesuai Filter Pendata</strong>
                        <div class="small text-muted mt-1" id="exportPendataInfo">Gunakan filter pendata yang aktif</div>
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-6" id="filterCard">
    <div class="card-header d-flex justify-content-between align-items-center" style="cursor:pointer" onclick="toggleFilter()">
        <h6 class="mb-0"><i class="ri-filter-3-line me-1"></i> Filter Laporan</h6>
        <i class="ri-arrow-up-s-line" id="filterChevron"></i>
    </div>
    <div id="filterBody" class="card-body">
        <form id="formFilter" method="GET" action="{{ route('admin.laporan.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kelas Usaha</label>
                    <select class="form-select" name="kelas_usaha" id="filterKelas">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasUsaha ?? [] as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_usaha') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori Usaha</label>
                    <select class="form-select" name="kategori_usaha" id="filterKategori">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriUsaha ?? [] as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori_usaha') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kecamatan</label>
                    <select class="form-select" name="kecamatan" id="filterKecamatan">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatanList ?? [] as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
                            {{ $kec }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pendata</label>
                    <select class="form-select" name="pendamping">
                        <option value="">Semua Pendata</option>
                        @foreach($pendampingList ?? [] as $pendamping)
                        <option value="{{ $pendamping->id }}" {{ request('pendamping') == $pendamping->id ? 'selected' : '' }}>
                            {{ $pendamping->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin Pemilik</label>
                    <select class="form-select" name="jenis_kelamin">
                        <option value="">Semua</option>
                        <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Pendataan (Dari)</label>
                    <input type="date" class="form-control" name="tgl_dari" value="{{ request('tgl_dari') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Pendataan (Sampai)</label>
                    <input type="date" class="form-control" name="tgl_sampai" value="{{ request('tgl_sampai') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-search-line me-1"></i> Tampilkan
                    </button>
                    <a href="{{ route('admin.laporan.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="ri-refresh-line me-1"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-6">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-primary rounded d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="ri-store-3-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total UMKM</p>
                    <h4 class="mb-0 fw-bold">{{ $totalUmkm ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-success rounded d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="ri-user-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Pemilik</p>
                    <h4 class="mb-0 fw-bold">{{ $totalPemilik ?? 0 }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-warning rounded d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="ri-team-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Karyawan</p>
                    <h4 class="mb-0 fw-bold">{{ number_format($totalKaryawan ?? 0) }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="avatar avatar-lg bg-label-info rounded d-flex align-items-center justify-content-center flex-shrink-0">
                    <i class="ri-money-dollar-circle-line ri-28px"></i>
                </div>
                <div>
                    <p class="mb-0 text-muted small">Total Omset/Tahun</p>
                    <h5 class="mb-0 fw-bold">Rp {{ number_format(($totalOmset ?? 0) * 12, 0, ',', '.') }}</h5>
                    <small class="text-muted">Bulanan: Rp {{ number_format($totalOmset ?? 0, 0, ',', '.') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-6">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Distribusi Kelas Usaha</h6>
            </div>
            <div class="card-body">
                <canvas id="chartKelas" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">UMKM per Kecamatan</h6>
            </div>
            <div class="card-body">
                <canvas id="chartKecamatan" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-6">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Jenis Kelamin Pemilik</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartGender" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Kepemilikan BPJS</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <canvas id="chartBpjs" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h6 class="mb-0">Top 5 Kategori Usaha</h6>
            </div>
            <div class="card-body">
                <canvas id="chartKategori" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
<script>
// Data dari controller
const chartKelasData     = @json($chartKelas ?? []);
const chartKecamatanData = @json($chartKecamatan ?? []);
const chartGenderData    = @json($chartGender ?? []);
const chartBpjsData      = @json($chartBpjs ?? []);
const chartKategoriData  = @json($chartKategori ?? []);

// Chart Kelas Usaha
new Chart(document.getElementById('chartKelas'), {
    type: 'bar',
    data: {
        labels: chartKelasData.map(d => d.nama),
        datasets: [{
            label: 'Jumlah UMKM',
            data: chartKelasData.map(d => d.total),
            backgroundColor: ['#696cff', '#03c3ec', '#71dd37', '#ffab00', '#ff3e1d'],
            borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

// Chart Kecamatan
new Chart(document.getElementById('chartKecamatan'), {
    type: 'bar',
    data: {
        labels: chartKecamatanData.map(d => d.kecamatan),
        datasets: [{
            label: 'Jumlah UMKM',
            data: chartKecamatanData.map(d => d.total),
            backgroundColor: '#03c3ec',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: { legend: { display: false } }
    }
});

// Chart Gender
new Chart(document.getElementById('chartGender'), {
    type: 'doughnut',
    data: {
        labels: ['Laki-laki', 'Perempuan'],
        datasets: [{
            data: [chartGenderData.laki ?? 0, chartGenderData.perempuan ?? 0],
            backgroundColor: ['#696cff', '#ff3e1d'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// Chart BPJS
new Chart(document.getElementById('chartBpjs'), {
    type: 'doughnut',
    data: {
        labels: ['BPJS Ketenagakerjaan', 'BPJS Kesehatan', 'Tidak Ada'],
        datasets: [{
            data: [
                chartBpjsData.ketenagakerjaan ?? 0,
                chartBpjsData.kesehatan ?? 0,
                chartBpjsData.tidak_ada ?? 0
            ],
            backgroundColor: ['#71dd37', '#03c3ec', '#8592a3'],
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});

// Chart Kategori
new Chart(document.getElementById('chartKategori'), {
    type: 'bar',
    data: {
        labels: chartKategoriData.map(d => d.nama),
        datasets: [{
            label: 'Jumlah',
            data: chartKategoriData.map(d => d.total),
            backgroundColor: '#ffab00',
            borderRadius: 6,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

function printLaporan() {
    window.print();
}

function toggleFilter() {
    const body    = document.getElementById('filterBody');
    const chevron = document.getElementById('filterChevron');
    const isOpen  = body.style.display !== 'none';
    body.style.display    = isOpen ? 'none' : 'block';
    chevron.className     = isOpen ? 'ri-arrow-down-s-line' : 'ri-arrow-up-s-line';
}

let currentExportType = 'excel';

function openExportModal(type) {
    currentExportType = type;
    const isExcel = type === 'excel';
    document.getElementById('modalExportTitle').innerHTML = isExcel
        ? '<i class="ri-file-excel-2-line me-2 text-success"></i> Export Excel'
        : '<i class="ri-file-pdf-line me-2 text-danger"></i> Export PDF';

    const pendataSelect = document.querySelector('select[name="pendamping"]');
    const pendataVal    = pendataSelect ? pendataSelect.value : '';
    const pendataText   = pendataSelect && pendataVal
        ? pendataSelect.options[pendataSelect.selectedIndex].text : null;

    const infoEl = document.getElementById('exportPendataInfo');
    const btnEl  = document.getElementById('btnExportPendata');

    if (pendataText) {
        infoEl.textContent = 'Pendata: ' + pendataText;
        btnEl.classList.remove('disabled');
    } else {
        infoEl.textContent = 'Tidak ada filter pendata aktif';
        btnEl.classList.add('disabled');
    }

    new bootstrap.Modal(document.getElementById('modalExport')).show();
}

function doExport(scope) {
    const params = new URLSearchParams(window.location.search);
    if (scope === 'semua') params.delete('pendamping');

    const baseUrl = currentExportType === 'excel'
        ? '{{ route("admin.laporan.export.excel") }}'
        : '{{ route("admin.laporan.export.pdf") }}';

    bootstrap.Modal.getInstance(document.getElementById('modalExport')).hide();
    window.location.href = baseUrl + '?' + params.toString();
}
</script>
@endpush
