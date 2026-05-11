@extends('layouts.app')

@section('title', 'Data UMKM')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Data UMKM</h4>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('kepala-bagian.data-umkm.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label mb-1">Cari</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Nama pemilik / usaha...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Kelas Usaha</label>
                    <select class="form-select" name="kelas" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasUsaha as $k)
                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Kecamatan</label>
                    <select class="form-select" name="kecamatan" onchange="this.form.submit()">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatanList as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Tanggal Dari</label>
                    <input type="date" class="form-control" name="tgl_dari" value="{{ request('tgl_dari') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1">Tanggal Sampai</label>
                    <input type="date" class="form-control" name="tgl_sampai" value="{{ request('tgl_sampai') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary w-100" title="Cari">
                        <i class="ri-search-line"></i>
                    </button>
                    <a href="{{ route('kepala-bagian.data-umkm.index') }}" class="btn btn-outline-secondary w-100" title="Reset">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center py-3">
        <span class="text-muted small">
            Menampilkan {{ $dataUmkm->firstItem() ?? 0 }}-{{ $dataUmkm->lastItem() ?? 0 }}
            dari {{ $dataUmkm->total() }} data
        </span>
    </div>
    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tableUmkm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pemilik</th>
                    <th>Nama Usaha</th>
                    <th>Kelas Usaha</th>
                    <th>Kecamatan</th>
                    <th>Pendata</th>
                    <th>Tgl Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataUmkm as $index => $item)
                <tr>
                    <td>{{ $dataUmkm->firstItem() + $index }}</td>
                    <td>{{ $item->pemilik->nama ?? '-' }}</td>
                    <td>{{ $item->nama_usaha }}</td>
                    <td>
                        @if($item->kelasUsaha)
                            <span class="badge bg-label-primary">{{ $item->kelasUsaha->nama }}</span>
                        @else
                            <span class="badge bg-label-secondary">Belum Ditentukan</span>
                        @endif
                    </td>
                    <td>{{ $item->kecamatan_usaha ?? '-' }}</td>
                    <td>{{ $item->pendata->name ?? '-' }}</td>
                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="showDetail({{ $item->id }})">
                            <i class="ri-eye-line me-1"></i> Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="ri-store-3-line ri-48px text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data UMKM</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($dataUmkm->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Halaman {{ $dataUmkm->currentPage() }} dari {{ $dataUmkm->lastPage() }}</small>
        {{ $dataUmkm->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
function showDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();

    fetch(`/kepala-bagian/data-umkm/${id}/detail`)
        .then(r => r.json())
        .then(data => {
            if (!data.success) {
                document.getElementById('detailContent').innerHTML = '<p class="text-danger p-3">Gagal memuat data</p>';
                return;
            }
            const pemilik    = data.pemilik;
            const usaha      = data.usaha;
            const legalitas  = data.legalitas;
            const sosialMedia = data.sosialMedia;

            document.getElementById('detailContent').innerHTML = `
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-lg me-3">
                                <span class="avatar-initial rounded-circle bg-label-primary">
                                    <i class="ri-store-3-line ri-24px"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0">${usaha.nama_usaha}</h5>
                                <small class="text-muted">${usaha.merek || 'Tidak ada merek'}</small>
                            </div>
                            <div>
                                ${usaha.kelas_usaha ? `<span class="badge bg-label-primary fs-6">${usaha.kelas_usaha}</span>` : '<span class="badge bg-label-secondary">Belum Ditentukan</span>'}
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-line text-primary me-2"></i>
                                    <div><small class="text-muted d-block">Pemilik</small><span class="fw-medium">${pemilik.nama}</span></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="ri-map-pin-line text-danger me-2"></i>
                                    <div><small class="text-muted d-block">Lokasi</small><span class="fw-medium">${usaha.kecamatan_usaha || '-'}</span></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <i class="ri-folder-line text-warning me-2"></i>
                                    <div><small class="text-muted d-block">Kategori</small><span class="fw-medium">${usaha.kategori_usaha || '-'}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="nav-align-top">
                    <ul class="nav nav-pills mb-4" role="tablist">
                        <li class="nav-item"><button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#detailPemilik"><i class="ri-user-line me-1"></i> Data Pemilik</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#detailUsaha"><i class="ri-store-3-line me-1"></i> Data Usaha</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#detailLegalitas"><i class="ri-file-text-line me-1"></i> Legalitas</button></li>
                        <li class="nav-item"><button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#detailSosmed"><i class="ri-share-line me-1"></i> Media Sosial</button></li>
                    </ul>
                    <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="detailPemilik" role="tabpanel">
                            <div class="card"><div class="card-body">
                                <h6 class="card-title mb-4"><i class="ri-user-line me-2"></i>Identitas Pemilik</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="border-start border-primary border-3 ps-3"><small class="text-muted d-block mb-1">NIK</small><p class="fw-semibold mb-0">${pemilik.nik}</p></div></div>
                                    <div class="col-md-8"><div class="border-start border-primary border-3 ps-3"><small class="text-muted d-block mb-1">Nama Lengkap</small><p class="fw-semibold mb-0">${pemilik.nama}</p></div></div>
                                    <div class="col-md-4"><div class="border-start border-info border-3 ps-3"><small class="text-muted d-block mb-1">Tempat Lahir</small><p class="fw-semibold mb-0">${pemilik.tempat_lahir || '-'}</p></div></div>
                                    <div class="col-md-4"><div class="border-start border-info border-3 ps-3"><small class="text-muted d-block mb-1">Tanggal Lahir</small><p class="fw-semibold mb-0">${pemilik.tanggal_lahir ? new Date(pemilik.tanggal_lahir).toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'}) : '-'}</p></div></div>
                                    <div class="col-md-4"><div class="border-start border-info border-3 ps-3"><small class="text-muted d-block mb-1">Jenis Kelamin</small><p class="fw-semibold mb-0">${pemilik.jenis_kelamin=='L'?'Laki-laki':(pemilik.jenis_kelamin=='P'?'Perempuan':'-')}</p></div></div>
                                </div>
                                <h6 class="card-title mt-4 mb-4"><i class="ri-phone-line me-2"></i>Kontak</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="border-start border-success border-3 ps-3"><small class="text-muted d-block mb-1">No. HP/WA</small><p class="fw-semibold mb-0">${pemilik.hp || '-'}</p></div></div>
                                    <div class="col-md-8"><div class="border-start border-success border-3 ps-3"><small class="text-muted d-block mb-1">Email</small><p class="fw-semibold mb-0">${pemilik.email || '-'}</p></div></div>
                                </div>
                                <h6 class="card-title mt-4 mb-4"><i class="ri-map-pin-line me-2"></i>Alamat</h6>
                                <div class="row g-3">
                                    <div class="col-md-3"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Provinsi</small><p class="fw-semibold mb-0">${pemilik.provinsi_pemilik || '-'}</p></div></div>
                                    <div class="col-md-3"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Kabupaten/Kota</small><p class="fw-semibold mb-0">${pemilik.kabupaten_pemilik || '-'}</p></div></div>
                                    <div class="col-md-3"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Kecamatan</small><p class="fw-semibold mb-0">${pemilik.kecamatan_pemilik || '-'}</p></div></div>
                                    <div class="col-md-3"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Desa/Kelurahan</small><p class="fw-semibold mb-0">${pemilik.desa_pemilik || '-'}</p></div></div>
                                    <div class="col-12"><div class="alert alert-secondary mb-0"><small class="text-muted d-block mb-1">Alamat Lengkap</small><p class="mb-0">${pemilik.alamat_pemilik || '-'}</p></div></div>
                                </div>
                                <h6 class="card-title mt-4 mb-4"><i class="ri-information-line me-2"></i>Informasi Tambahan</h6>
                                <div class="row g-3">
                                    <div class="col-md-6"><div class="border-start border-primary border-3 ps-3"><small class="text-muted d-block mb-1">BPJS Ketenagakerjaan</small><p class="fw-semibold mb-0">${pemilik.bpjs_ketenagakerjaan ? '<span class="badge bg-success">Sudah</span>' : '<span class="badge bg-secondary">Belum</span>'}</p></div></div>
                                    <div class="col-md-6"><div class="border-start border-primary border-3 ps-3"><small class="text-muted d-block mb-1">BPJS Kesehatan</small><p class="fw-semibold mb-0">${pemilik.bpjs_kesehatan ? '<span class="badge bg-success">Sudah</span>' : '<span class="badge bg-secondary">Belum</span>'}</p></div></div>
                                    ${pemilik.ikut_forum ? `<div class="col-md-6"><div class="border-start border-info border-3 ps-3"><small class="text-muted d-block mb-1">Forum Usaha</small><p class="fw-semibold mb-1">${pemilik.nama_forum||'-'}</p><small class="text-muted">Jabatan: ${pemilik.jabatan_forum||'-'}</small></div></div>` : ''}
                                    ${pemilik.ikut_koperasi ? `<div class="col-md-6"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Koperasi</small><p class="fw-semibold mb-1">${pemilik.nama_koperasi||'-'}</p><small class="text-muted">Jabatan: ${pemilik.jabatan_koperasi||'-'}</small></div></div>` : ''}
                                    ${pemilik.ikut_pelatihan ? `<div class="col-md-6"><div class="border-start border-success border-3 ps-3"><small class="text-muted d-block mb-1">Pelatihan Terakhir</small><p class="fw-semibold mb-0">${pemilik.nama_pelatihan||'-'}</p></div></div>` : ''}
                                </div>
                                <h6 class="card-title mt-4 mb-4"><i class="ri-file-list-line me-2"></i>Informasi Pendataan</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="border-start border-secondary border-3 ps-3"><small class="text-muted d-block mb-1">Didata Oleh</small><p class="fw-semibold mb-0">${usaha.pendata||'-'}</p></div></div>
                                    <div class="col-md-4"><div class="border-start border-secondary border-3 ps-3"><small class="text-muted d-block mb-1">Tanggal Input</small><p class="fw-semibold mb-0">${usaha.tanggal_input||'-'}</p></div></div>
                                    <div class="col-md-4"><div class="border-start border-secondary border-3 ps-3"><small class="text-muted d-block mb-1">Terakhir Diupdate</small><p class="fw-semibold mb-0">${usaha.tanggal_update||'-'}</p></div></div>
                                </div>
                            </div></div>
                        </div>

                        <div class="tab-pane fade" id="detailUsaha" role="tabpanel">
                            <div class="card"><div class="card-body">
                                <h6 class="card-title mb-4"><i class="ri-store-3-line me-2"></i>Informasi Usaha</h6>
                                <div class="row g-3">
                                    <div class="col-md-6"><div class="border-start border-primary border-3 ps-3"><small class="text-muted d-block mb-1">Nama Usaha</small><p class="fw-semibold mb-0">${usaha.nama_usaha}</p></div></div>
                                    <div class="col-md-6"><div class="border-start border-primary border-3 ps-3"><small class="text-muted d-block mb-1">Merek/Brand</small><p class="fw-semibold mb-0">${usaha.merek||'-'}</p></div></div>
                                    <div class="col-md-6"><div class="border-start border-info border-3 ps-3"><small class="text-muted d-block mb-1">Kategori Usaha</small><p class="fw-semibold mb-0">${usaha.kategori_usaha||'-'}</p></div></div>
                                    <div class="col-md-6"><div class="border-start border-info border-3 ps-3"><small class="text-muted d-block mb-1">Kelas Usaha</small><p class="fw-semibold mb-0">${usaha.kelas_usaha ? `<span class="badge bg-label-primary">${usaha.kelas_usaha}</span>` : '<span class="badge bg-label-secondary">Belum Ditentukan</span>'}</p></div></div>
                                </div>
                                <h6 class="card-title mt-4 mb-4"><i class="ri-map-pin-line me-2"></i>Lokasi Usaha</h6>
                                <div class="row g-3">
                                    <div class="col-md-6"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Kecamatan</small><p class="fw-semibold mb-0">${usaha.kecamatan_usaha||'-'}</p></div></div>
                                    <div class="col-md-6"><div class="border-start border-warning border-3 ps-3"><small class="text-muted d-block mb-1">Desa/Kelurahan</small><p class="fw-semibold mb-0">${usaha.desa_usaha||'-'}</p></div></div>
                                    <div class="col-12"><div class="alert alert-secondary mb-0"><small class="text-muted d-block mb-1">Alamat Lengkap</small><p class="mb-0">${usaha.alamat_usaha||'-'}</p></div></div>
                                </div>
                                <h6 class="card-title mt-4 mb-4"><i class="ri-line-chart-line me-2"></i>Data Finansial & Operasional</h6>
                                <div class="row g-3">
                                    <div class="col-md-4"><div class="card border shadow-none"><div class="card-body text-center py-3"><i class="ri-team-line ri-24px text-primary mb-2"></i><h6 class="mb-1">${usaha.karyawan||0}</h6><small class="text-muted">Karyawan</small></div></div></div>
                                    <div class="col-md-4"><div class="card border shadow-none"><div class="card-body text-center py-3"><i class="ri-money-dollar-circle-line ri-24px text-success mb-2"></i><h6 class="mb-1">Rp ${new Intl.NumberFormat('id-ID').format((usaha.omset_bulanan_rp||0) * 12)}</h6><small class="text-muted">Omset Tahunan</small></div></div></div>
                                    <div class="col-md-4"><div class="card border shadow-none"><div class="card-body text-center py-3"><i class="ri-safe-line ri-24px text-warning mb-2"></i><h6 class="mb-1">Rp ${new Intl.NumberFormat('id-ID').format(usaha.aset_rp||0)}</h6><small class="text-muted">Total Aset</small></div></div></div>
                                </div>
                            </div></div>
                        </div>

                        <div class="tab-pane fade" id="detailLegalitas" role="tabpanel">
                            <div class="card"><div class="card-body">
                                <h6 class="card-title mb-4"><i class="ri-file-text-line me-2"></i>Dokumen Legalitas</h6>
                                ${legalitas.length > 0 ? `
                                    <div class="row g-3">
                                        ${legalitas.map(item => `
                                            <div class="col-md-6">
                                                <div class="card border shadow-none"><div class="card-body">
                                                    <div class="d-flex align-items-start">
                                                        <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded bg-label-success"><i class="ri-file-check-line"></i></span></div>
                                                        <div><h6 class="mb-1">${item.jenis}</h6><small class="text-muted">Nomor: ${item.nomor||'-'}</small></div>
                                                    </div>
                                                </div></div>
                                            </div>`).join('')}
                                    </div>` : `
                                    <div class="text-center py-5">
                                        <i class="ri-file-forbid-line ri-48px text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Belum ada dokumen legalitas</p>
                                    </div>`}
                            </div></div>
                        </div>

                        <div class="tab-pane fade" id="detailSosmed" role="tabpanel">
                            <div class="card"><div class="card-body">
                                <h6 class="card-title mb-4"><i class="ri-share-line me-2"></i>Akun Media Sosial</h6>
                                ${sosialMedia.length > 0 ? `
                                    <div class="row g-3">
                                        ${sosialMedia.map(item => `
                                            <div class="col-md-6">
                                                <div class="card border shadow-none"><div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar avatar-sm me-3"><span class="avatar-initial rounded bg-label-info"><i class="ri-share-line"></i></span></div>
                                                        <div><h6 class="mb-1">${item.platform}</h6><small class="text-muted">${item.url||'-'}</small></div>
                                                    </div>
                                                </div></div>
                                            </div>`).join('')}
                                    </div>` : `
                                    <div class="text-center py-5">
                                        <i class="ri-share-forward-line ri-48px text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">Belum ada akun media sosial</p>
                                    </div>`}
                            </div></div>
                        </div>
                    </div>
                </div>`;
        })
        .catch(() => {
            document.getElementById('detailContent').innerHTML = '<p class="text-danger p-3">Terjadi kesalahan saat memuat data</p>';
        });
}
</script>
@endpush
