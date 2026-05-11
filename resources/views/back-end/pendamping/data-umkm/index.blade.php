@extends('layouts.app')

@section('title', 'Data UMKM Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Data UMKM</h4>
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCreate">
        <i class="ri-add-line me-1"></i> Tambah Data UMKM
    </button>
</div>

<!-- Filter -->
<div class="mb-3 d-flex justify-content-end">
    <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel" aria-expanded="false">
        <i class="ri-filter-3-line me-1"></i> Filter
    </button>
</div>
<div class="collapse mb-3" id="filterPanel">
<div class="card">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('pendamping.data-umkm.index') }}" id="formFilter">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label mb-1 small">Cari</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                        <input type="text" class="form-control form-control-sm" name="search" value="{{ request('search') }}" placeholder="Nama pemilik / usaha...">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Kelas Usaha</label>
                    <select class="form-select form-select-sm" name="kelas" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasUsaha ?? [] as $k)
                        <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Kecamatan</label>
                    <select class="form-select form-select-sm" name="kecamatan" onchange="this.form.submit()">
                        <option value="">Semua Kecamatan</option>
                        @foreach($kecamatanList ?? [] as $kec)
                        <option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>{{ $kec }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Tgl Dari</label>
                    <input type="date" class="form-control form-control-sm" name="tgl_dari" value="{{ request('tgl_dari') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-2">
                    <label class="form-label mb-1 small">Tgl Sampai</label>
                    <input type="date" class="form-control form-control-sm" name="tgl_sampai" value="{{ request('tgl_sampai') }}" onchange="this.form.submit()">
                </div>
                <div class="col-md-1 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100" title="Cari">
                        <i class="ri-search-line"></i>
                    </button>
                    <a href="{{ route('pendamping.data-umkm.index') }}" class="btn btn-outline-secondary btn-sm w-100" title="Reset">
                        <i class="ri-refresh-line"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
</div>
@if(request()->hasAny(['search','kelas','kecamatan','tgl_dari','tgl_sampai']))
<script>
    document.getElementById('filterPanel').classList.add('show');
</script>
@endif

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
                    <th>Lokasi Usaha</th>
                    <th>Tgl Input</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataUmkm as $index => $item)
                <tr id="umkm-row-{{ $item->id }}"
                    data-pemilik="{{ strtolower($item->pemilik->nama ?? '') }}"
                    data-usaha="{{ strtolower($item->nama_usaha) }}"
                    data-kelas="{{ $item->kelasUsaha->nama ?? '' }}"
                    data-kecamatan="{{ $item->kecamatan_usaha ?? '' }}"
                    data-tgl="{{ $item->created_at ? $item->created_at->format('Y-m-d') : '' }}">
                    <td>{{ $dataUmkm->firstItem() + $index }}</td>
                    <td id="umkm-pemilik-{{ $item->id }}">{{ $item->pemilik->nama }}</td>
                    <td id="umkm-usaha-{{ $item->id }}">{{ $item->nama_usaha }}</td>
                    <td id="umkm-kelas-{{ $item->id }}">
                        @if($item->kelasUsaha)
                            <span class="badge bg-label-primary">{{ $item->kelasUsaha->nama }}</span>
                        @else
                            <span class="badge bg-label-secondary">Belum Ditentukan</span>
                        @endif
                    </td>
                    <td id="umkm-lokasi-{{ $item->id }}">{{ $item->kecamatan_usaha ?? '-' }}</td>
                    <td>{{ $item->created_at ? $item->created_at->format('d/m/Y') : '-' }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ri-more-2-line"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item" onclick="showDetail({{ $item->id }})">
                                    <i class="ri-eye-line me-1"></i> Detail
                                </button>
                                <button type="button" class="dropdown-item" onclick="editData({{ $item->id }})">
                                    <i class="ri-pencil-line me-1"></i> Edit
                                </button>
                                <button type="button" class="dropdown-item text-danger" onclick="deleteData({{ $item->id }}, '{{ $item->nama_usaha }}')">
                                    <i class="ri-delete-bin-6-line me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
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
        <small class="text-muted">
            Halaman {{ $dataUmkm->currentPage() }} dari {{ $dataUmkm->lastPage() }}
        </small>
        {{ $dataUmkm->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreate">
                @csrf
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="step-label active" data-step="1">
                                <i class="ri-user-line"></i> Data Pemilik
                            </span>
                            <span class="step-label" data-step="2">
                                <i class="ri-store-3-line"></i> Data Usaha
                            </span>
                            <span class="step-label" data-step="3">
                                <i class="ri-file-text-line"></i> Legalitas
                            </span>
                            <span class="step-label" data-step="4">
                                <i class="ri-share-line"></i> Media Sosial
                            </span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar" id="progressBar" role="progressbar" style="width: 25%"></div>
                        </div>
                    </div>

                    <!-- Step 1: Data Pemilik -->
                    <div class="step-content active" id="step1">
                        <h6 class="mb-3">Data Pemilik</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nik" maxlength="16" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control" name="tempat_lahir">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                    max="{{ now()->subYears(17)->format('Y-m-d') }}">
                                <div class="invalid-feedback">Pemilik harus berusia minimal 17 tahun.</div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">No. HP/WA</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="tel" class="form-control" name="hp" placeholder="81234567890" maxlength="13" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                </div>
                                <small class="text-muted">Contoh: 81234567890 (tanpa 0 di depan)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                                <select class="form-select" name="provinsi_pemilik" id="provinsi_pemilik" onchange="loadKabupaten('pemilik')">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                                <select class="form-select" name="kabupaten_pemilik" id="kabupaten_pemilik" onchange="loadKecamatan('pemilik')" disabled>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                <select class="form-select" name="kecamatan_pemilik" id="kecamatan_pemilik" onchange="loadDesa('pemilik')" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                                <select class="form-select" name="desa_pemilik" id="desa_pemilik" disabled>
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" name="alamat_pemilik" rows="2"></textarea>
                            </div>
                            
                            <div class="col-12"><hr class="my-4"></div>
                            <div class="col-12"><h6 class="mb-3">Informasi Tambahan</h6></div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="bpjs_ketenagakerjaan" id="bpjs_ketenagakerjaan" value="1">
                                    <label class="form-check-label" for="bpjs_ketenagakerjaan">
                                        BPJS Ketenagakerjaan
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="bpjs_kesehatan" id="bpjs_kesehatan" value="1">
                                    <label class="form-check-label" for="bpjs_kesehatan">
                                        BPJS Kesehatan
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ikut_forum" id="ikut_forum" value="1" onchange="toggleForumFields()">
                                    <label class="form-check-label" for="ikut_forum">
                                        Ikut Forum Usaha
                                    </label>
                                </div>
                                <div class="row g-2 mb-3" id="forum_fields" style="display: none;">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm" name="nama_forum" placeholder="Nama Forum">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm" name="jabatan_forum" placeholder="Jabatan di Forum">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ikut_koperasi" id="ikut_koperasi" value="1" onchange="toggleKoperasiFields()">
                                    <label class="form-check-label" for="ikut_koperasi">
                                        Ikut Koperasi
                                    </label>
                                </div>
                                <div class="row g-2 mb-3" id="koperasi_fields" style="display: none;">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm" name="nama_koperasi" placeholder="Nama Koperasi">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm" name="jabatan_koperasi" placeholder="Jabatan di Koperasi">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="ikut_pelatihan" id="ikut_pelatihan" value="1" onchange="togglePelatihanFields()">
                                    <label class="form-check-label" for="ikut_pelatihan">
                                        Pernah Ikut Pelatihan
                                    </label>
                                </div>
                                <div id="pelatihan_fields" style="display: none;">
                                    <input type="text" class="form-control form-control-sm mb-3" name="nama_pelatihan" placeholder="Nama Pelatihan Terakhir">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Data Usaha -->
                    <div class="step-content" id="step2">
                        <h6 class="mb-3">Data Usaha</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Usaha <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_usaha" required>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Merek/Brand</label>
                                <input type="text" class="form-control" name="merek">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Kategori Usaha <span class="text-danger">*</span></label>
                                <select class="form-select" name="id_kategori_usaha">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriUsaha ?? [] as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kecamatan Usaha <span class="text-danger">*</span></label>
                                <select class="form-select" name="kecamatan_usaha" id="kecamatan_usaha_select" onchange="loadDesaUsaha()">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Desa/Kelurahan Usaha <span class="text-danger">*</span></label>
                                <select class="form-select" name="desa_usaha" id="desa_usaha_select" disabled>
                                    <option value="">Pilih Desa</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Alamat Usaha</label>
                                <textarea class="form-control" name="alamat_usaha" rows="2"></textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Jumlah Karyawan <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="karyawan" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Omset Bulanan (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="omset_bulanan_rp" min="0">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Aset (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="aset_rp" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Legalitas -->
                    <div class="step-content" id="step3">
                        <h6 class="mb-3">Dokumen Legalitas</h6>
                        <div id="legalitasContainer">
                            <div class="legalitas-item border rounded p-3 mb-3">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Jenis Dokumen</label>
                                        <select class="form-select" name="legalitas_jenis[]">
                                            <option value="">Pilih Jenis</option>
                                            <option value="NIB">NIB</option>
                                            <option value="NPWP">NPWP</option>
                                            <option value="SIUP">SIUP</option>
                                            <option value="TDP">TDP</option>
                                            <option value="Sertifikat Halal">Sertifikat Halal</option>
                                            <option value="PIRT">PIRT</option>
                                            <option value="HKI">HKI</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">Nomor Dokumen</label>
                                        <input type="text" class="form-control" name="legalitas_nomor[]">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeLegalitas(this)">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addLegalitas()">
                            <i class="ri-add-line me-1"></i> Tambah Legalitas
                        </button>
                        <p class="text-muted small mt-2 mb-0">* Opsional - Bisa dilewati jika belum ada dokumen legalitas</p>
                    </div>

                    <!-- Step 4: Media Sosial -->
                    <div class="step-content" id="step4">
                        <h6 class="mb-3">Akun Media Sosial</h6>
                        <div id="sosmedContainer">
                            <div class="sosmed-item border rounded p-3 mb-3">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label class="form-label">Platform</label>
                                        <select class="form-select" name="sosmed_platform[]">
                                            <option value="">Pilih Platform</option>
                                            <option value="Instagram">Instagram</option>
                                            <option value="Facebook">Facebook</option>
                                            <option value="TikTok">TikTok</option>
                                            <option value="WhatsApp">WhatsApp</option>
                                            <option value="Twitter">Twitter</option>
                                            <option value="YouTube">YouTube</option>
                                            <option value="Website">Website</option>
                                            <option value="Lainnya">Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label">URL/Username</label>
                                        <input type="text" class="form-control" name="sosmed_url[]" placeholder="@username atau https://...">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSosmed(this)">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSosmed()">
                            <i class="ri-add-line me-1"></i> Tambah Media Sosial
                        </button>
                        <p class="text-muted small mt-2 mb-0">* Opsional - Bisa dilewati jika belum ada akun media sosial</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary d-none" id="btnPrev">
                        <i class="ri-arrow-left-line me-1"></i> Sebelumnya
                    </button>
                    <button type="button" class="btn btn-primary" id="btnNext">
                        Selanjutnya <i class="ri-arrow-right-line ms-1"></i>
                    </button>
                    <button type="submit" class="btn btn-primary d-none" id="btnSubmit">
                        <i class="ri-save-line me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data UMKM</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="step-label active" data-step="1">
                                <i class="ri-user-line"></i> Data Pemilik
                            </span>
                            <span class="step-label" data-step="2">
                                <i class="ri-store-3-line"></i> Data Usaha
                            </span>
                            <span class="step-label" data-step="3">
                                <i class="ri-file-text-line"></i> Legalitas
                            </span>
                            <span class="step-label" data-step="4">
                                <i class="ri-share-line"></i> Media Sosial
                            </span>
                        </div>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar" id="editProgressBar" role="progressbar" style="width: 25%"></div>
                        </div>
                    </div>

                    <!-- Step 1: Data Pemilik -->
                    <div class="step-content active" id="edit_step1">
                        <h6 class="mb-3">Data Pemilik</h6>
                        <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="edit_nik" name="nik" maxlength="16" required>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="edit_nama" name="nama" required>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Tempat Lahir</label>
                                                    <input type="text" class="form-control" id="edit_tempat_lahir" name="tempat_lahir">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Tanggal Lahir</label>
                                                    <input type="date" class="form-control" id="edit_tanggal_lahir" name="tanggal_lahir">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Jenis Kelamin</label>
                                                    <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin">
                                                        <option value="">Pilih</option>
                                                        <option value="L">Laki-laki</option>
                                                        <option value="P">Perempuan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">No. HP/WA</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">+62</span>
                                                        <input type="tel" class="form-control" id="edit_hp" name="hp" placeholder="81234567890" maxlength="13" inputmode="numeric" pattern="[0-9]*" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                                    </div>
                                                    <small class="text-muted">Contoh: 81234567890 (tanpa 0 di depan)</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="edit_email" name="email">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Provinsi <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="provinsi_pemilik" id="edit_provinsi_pemilik" onchange="loadKabupaten('edit')">
                                                        <option value="">Pilih Provinsi</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="kabupaten_pemilik" id="edit_kabupaten_pemilik" onchange="loadKecamatan('edit')" disabled>
                                                        <option value="">Pilih Kabupaten</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="kecamatan_pemilik" id="edit_kecamatan_pemilik" onchange="loadDesa('edit')" disabled>
                                                        <option value="">Pilih Kecamatan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Desa/Kelurahan <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="desa_pemilik" id="edit_desa_pemilik" disabled>
                                                        <option value="">Pilih Desa</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Alamat Lengkap</label>
                                                    <textarea class="form-control" id="edit_alamat_pemilik" name="alamat_pemilik" rows="2"></textarea>
                                                </div>
                                                <div class="col-12"><hr class="my-4"></div>
                                                <div class="col-12"><h6 class="mb-3">Informasi Tambahan</h6></div>
                                                <div class="col-md-6">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" name="bpjs_ketenagakerjaan" id="edit_bpjs_ketenagakerjaan" value="1">
                                                        <label class="form-check-label" for="edit_bpjs_ketenagakerjaan">
                                                            BPJS Ketenagakerjaan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox" name="bpjs_kesehatan" id="edit_bpjs_kesehatan" value="1">
                                                        <label class="form-check-label" for="edit_bpjs_kesehatan">
                                                            BPJS Kesehatan
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="ikut_forum" id="edit_ikut_forum" value="1" onchange="toggleEditForumFields()">
                                                        <label class="form-check-label" for="edit_ikut_forum">
                                                            Ikut Forum Usaha
                                                        </label>
                                                    </div>
                                                    <div class="row g-2 mb-3" id="edit_forum_fields" style="display: none;">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm" id="edit_nama_forum" name="nama_forum" placeholder="Nama Forum">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm" id="edit_jabatan_forum" name="jabatan_forum" placeholder="Jabatan di Forum">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="ikut_koperasi" id="edit_ikut_koperasi" value="1" onchange="toggleEditKoperasiFields()">
                                                        <label class="form-check-label" for="edit_ikut_koperasi">
                                                            Ikut Koperasi
                                                        </label>
                                                    </div>
                                                    <div class="row g-2 mb-3" id="edit_koperasi_fields" style="display: none;">
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm" id="edit_nama_koperasi" name="nama_koperasi" placeholder="Nama Koperasi">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <input type="text" class="form-control form-control-sm" id="edit_jabatan_koperasi" name="jabatan_koperasi" placeholder="Jabatan di Koperasi">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" name="ikut_pelatihan" id="edit_ikut_pelatihan" value="1" onchange="toggleEditPelatihanFields()">
                                                        <label class="form-check-label" for="edit_ikut_pelatihan">
                                                            Pernah Ikut Pelatihan
                                                        </label>
                                                    </div>
                                                    <div id="edit_pelatihan_fields" style="display: none;">
                                                        <input type="text" class="form-control form-control-sm mb-3" id="edit_nama_pelatihan" name="nama_pelatihan" placeholder="Nama Pelatihan Terakhir">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 2: Data Usaha -->
                                        <div class="step-content" id="edit_step2">
                                            <h6 class="mb-3">Data Usaha</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label">Nama Usaha <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" id="edit_nama_usaha" name="nama_usaha" required>
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Merek/Brand</label>
                                                    <input type="text" class="form-control" id="edit_merek" name="merek">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="form-label">Kategori Usaha <span class="text-danger">*</span></label>
                                                    <select class="form-select" id="edit_id_kategori_usaha" name="id_kategori_usaha">
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach($kategoriUsaha ?? [] as $kategori)
                                                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Kecamatan Usaha <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="kecamatan_usaha" id="edit_kecamatan_usaha_select" onchange="loadEditDesaUsaha()">
                                                        <option value="">Pilih Kecamatan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Desa/Kelurahan Usaha <span class="text-danger">*</span></label>
                                                    <select class="form-select" name="desa_usaha" id="edit_desa_usaha_select" disabled>
                                                        <option value="">Pilih Desa</option>
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Alamat Usaha</label>
                                                    <textarea class="form-control" id="edit_alamat_usaha" name="alamat_usaha" rows="2"></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Jumlah Karyawan <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="edit_karyawan" name="karyawan" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Omset Bulanan (Rp) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="edit_omset_bulanan_rp" name="omset_bulanan_rp" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Total Aset (Rp) <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" id="edit_aset_rp" name="aset_rp" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 3: Legalitas -->
                                        <div class="step-content" id="edit_step3">
                                            <h6 class="mb-3">Dokumen Legalitas</h6>
                                            <div id="edit_legalitasContainer">
                                                <!-- Legalitas item akan diisi via JS -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEditLegalitas()">
                                                <i class="ri-add-line me-1"></i> Tambah Legalitas
                                            </button>
                                            <p class="text-muted small mt-2 mb-0">* Opsional - Bisa dilewati jika belum ada dokumen legalitas</p>
                                        </div>

                                        <!-- Step 4: Media Sosial -->
                                        <div class="step-content" id="edit_step4">
                                            <h6 class="mb-3">Akun Media Sosial</h6>
                                            <div id="edit_sosmedContainer">
                                                <!-- Sosmed item akan diisi via JS -->
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="addEditSosmed()">
                                                <i class="ri-add-line me-1"></i> Tambah Media Sosial
                                            </button>
                                            <p class="text-muted small mt-2 mb-0">* Opsional - Bisa dilewati jika belum ada akun media sosial</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary d-none" id="editBtnPrev">
                                            <i class="ri-arrow-left-line me-1"></i> Sebelumnya
                                        </button>
                                        <button type="button" class="btn btn-primary" id="editBtnNext">
                                            Selanjutnya <i class="ri-arrow-right-line ms-1"></i>
                                        </button>
                                        <button type="submit" class="btn btn-primary d-none" id="editBtnSubmit">
                                            <i class="ri-save-line me-1"></i> Update
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
<style>
.step-label {
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 500;
}

.step-label.active {
    color: #696cff;
}

.step-label.completed {
    color: #198754;
}

.step-content {
    display: none;
}

.step-content.active {
    display: block;
}

.progress-bar {
    transition: width 0.3s ease;
}

.legalitas-item, .sosmed-item {
    background-color: #f8f9fa;
}

.modal-footer {
    position: sticky;
    bottom: 0;
    background: white;
    z-index: 1;
    border-top: none;
    padding-top: 1rem;
    gap: 0.5rem;
}

.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}

#modalEdit.is-loading .modal-body {
    opacity: 0.6;
    pointer-events: none;
}
</style>

@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
$(document).ready(function() {
    $('#modalCreate').on('shown.bs.modal', function() {
        loadProvinsi();
        loadKecamatanPurworejo();
        currentStep = 1;
        updateProgress();
    });
});

// ===== WILAYAH FUNCTIONS =====
const wilayahCache = new Map();

function apiWilayah(params) {
    const key = new URLSearchParams(params).toString();

    if (wilayahCache.has(key)) {
        return Promise.resolve(wilayahCache.get(key));
    }

    return fetch('/pendamping/data-umkm/api/wilayah?' + key)
        .then(r => r.json())
        .then(data => {
            wilayahCache.set(key, data);
            return data;
        });
}

function fillSelect(selectEl, data, placeholder) {
    selectEl.innerHTML = `<option value="">${placeholder}</option>`;
    data.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.nama;
        opt.dataset.kode = item.kode;
        opt.textContent = item.nama;
        selectEl.appendChild(opt);
    });
}

function setSelectValue(selectEl, value) {
    if (!value) {
        selectEl.value = '';
        return '';
    }

    const existingOption = Array.from(selectEl.options).find(option => option.value === value);

    if (existingOption) {
        selectEl.value = value;
        return existingOption.dataset.kode || '';
    }

    const fallbackOption = document.createElement('option');
    fallbackOption.value = value;
    fallbackOption.textContent = value;
    selectEl.appendChild(fallbackOption);
    selectEl.value = value;

    return '';
}

function normalizePhoneForInput(phone) {
    if (!phone) {
        return '';
    }

    return phone.replace(/^\+62/, '').replace(/^0/, '');
}

function normalizeDateForInput(value) {
    if (!value) {
        return '';
    }

    if (/^\d{4}-\d{2}-\d{2}$/.test(value)) {
        return value;
    }

    const parsedDate = new Date(value);
    if (Number.isNaN(parsedDate.getTime())) {
        return '';
    }

    return parsedDate.toISOString().split('T')[0];
}

function renderKelasUsahaBadge(kelasUsahaNama) {
    if (!kelasUsahaNama) {
        return '<span class="badge bg-label-secondary">Belum Ditentukan</span>';
    }

    return `<span class="badge bg-label-primary">${kelasUsahaNama}</span>`;
}

function updateUmkmRow(item) {
    const pemilikEl = document.getElementById(`umkm-pemilik-${item.id}`);
    const usahaEl = document.getElementById(`umkm-usaha-${item.id}`);
    const kelasEl = document.getElementById(`umkm-kelas-${item.id}`);
    const lokasiEl = document.getElementById(`umkm-lokasi-${item.id}`);

    if (pemilikEl) {
        pemilikEl.textContent = item.nama_pemilik || '-';
    }

    if (usahaEl) {
        usahaEl.textContent = item.nama_usaha || '-';
    }

    if (kelasEl) {
        kelasEl.innerHTML = renderKelasUsahaBadge(item.kelas_usaha_nama);
    }

    if (lokasiEl) {
        lokasiEl.textContent = item.kecamatan_usaha || item.kecamatan_usaha || '-';
    }
}

function loadProvinsi() {
    apiWilayah({ type: 'provinsi' }).then(data => {
        fillSelect(document.getElementById('provinsi_pemilik'), data, 'Pilih Provinsi');
    });
}

function getWilayahIds(prefix) {
    if (prefix === 'edit') {
        return {
            provinsi: 'edit_provinsi_pemilik',
            kabupaten: 'edit_kabupaten_pemilik',
            kecamatan: 'edit_kecamatan_pemilik',
            desa: 'edit_desa_pemilik',
        };
    }
    return {
        provinsi: 'provinsi_pemilik',
        kabupaten: 'kabupaten_pemilik',
        kecamatan: 'kecamatan_pemilik',
        desa: 'desa_pemilik',
    };
}

function loadKabupaten(prefix) {
    const ids = getWilayahIds(prefix);
    const sel = document.getElementById(ids.provinsi);
    const kode = sel.options[sel.selectedIndex]?.dataset.kode;
    const kabSel = document.getElementById(ids.kabupaten);
    const kecSel = document.getElementById(ids.kecamatan);
    const desaSel = document.getElementById(ids.desa);

    fillSelect(kabSel, [], 'Pilih Kabupaten');
    fillSelect(kecSel, [], 'Pilih Kecamatan');
    fillSelect(desaSel, [], 'Pilih Desa');
    kabSel.disabled = true; kecSel.disabled = true; desaSel.disabled = true;

    if (!kode) return;
    apiWilayah({ type: 'kabupaten', kode }).then(data => {
        fillSelect(kabSel, data, 'Pilih Kabupaten');
        kabSel.disabled = false;
    });
}

function loadKecamatan(prefix) {
    const ids = getWilayahIds(prefix);
    const sel = document.getElementById(ids.kabupaten);
    const kode = sel.options[sel.selectedIndex]?.dataset.kode;
    const kecSel = document.getElementById(ids.kecamatan);
    const desaSel = document.getElementById(ids.desa);

    fillSelect(kecSel, [], 'Pilih Kecamatan');
    fillSelect(desaSel, [], 'Pilih Desa');
    kecSel.disabled = true; desaSel.disabled = true;

    if (!kode) return;
    apiWilayah({ type: 'kecamatan', kode }).then(data => {
        fillSelect(kecSel, data, 'Pilih Kecamatan');
        kecSel.disabled = false;
    });
}

function loadDesa(prefix) {
    const ids = getWilayahIds(prefix);
    const sel = document.getElementById(ids.kecamatan);
    const kode = sel.options[sel.selectedIndex]?.dataset.kode;
    const desaSel = document.getElementById(ids.desa);

    fillSelect(desaSel, [], 'Pilih Desa');
    desaSel.disabled = true;

    if (!kode) return;
    apiWilayah({ type: 'desa', kode }).then(data => {
        fillSelect(desaSel, data, 'Pilih Desa');
        desaSel.disabled = false;
    });
}

function loadKecamatanPurworejo() {
    apiWilayah({ type: 'kecamatan', kode: '33.06' }).then(data => {
        const sel = document.getElementById('kecamatan_usaha_select');
        fillSelect(sel, data, 'Pilih Kecamatan');
        sel.disabled = false;
    });
}

function loadDesaUsaha() {
    const sel = document.getElementById('kecamatan_usaha_select');
    const kode = sel.options[sel.selectedIndex]?.dataset.kode;
    const desaSel = document.getElementById('desa_usaha_select');

    fillSelect(desaSel, [], 'Pilih Desa');
    desaSel.disabled = true;

    if (!kode) return;
    apiWilayah({ type: 'desa', kode }).then(data => {
        fillSelect(desaSel, data, 'Pilih Desa');
        desaSel.disabled = false;
    });
}

// ===== TOGGLE FIELDS =====
function toggleForumFields() {
    document.getElementById('forum_fields').style.display =
        document.getElementById('ikut_forum').checked ? 'block' : 'none';
}

function toggleKoperasiFields() {
    document.getElementById('koperasi_fields').style.display =
        document.getElementById('ikut_koperasi').checked ? 'block' : 'none';
}

function togglePelatihanFields() {
    document.getElementById('pelatihan_fields').style.display =
        document.getElementById('ikut_pelatihan').checked ? 'block' : 'none';
}

// ===== ALERT =====
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible`;
    alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    const container = document.querySelector('.container-xxl');
    container.insertBefore(alertDiv, container.firstChild);
    setTimeout(() => alertDiv.remove(), 5000);
}

// Step Wizard
let currentStep = 1;
const totalSteps = 4;

// Helper: buat atau ambil elemen invalid-feedback di bawah input
function getOrCreateFeedback(el) {
    let fb = el.parentElement.querySelector('.invalid-feedback');
    if (!fb) {
        fb = document.createElement('div');
        fb.className = 'invalid-feedback';
        el.parentElement.appendChild(fb);
    }
    return fb;
}

// Clear invalid state saat user mulai mengetik/mengubah
document.querySelectorAll('#modalCreate input, #modalCreate select, #modalEdit input, #modalEdit select')
    .forEach(el => el.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    }));

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    
    document.querySelectorAll('#modalCreate .step-label').forEach((label, index) => {
        const step = index + 1;
        label.classList.remove('active', 'completed');
        if (step < currentStep) {
            label.classList.add('completed');
        } else if (step === currentStep) {
            label.classList.add('active');
        }
    });
    
    document.querySelectorAll('#modalCreate .step-content').forEach((content, index) => {
        content.classList.remove('active');
        if (index + 1 === currentStep) {
            content.classList.add('active');
        }
    });
    
    // Show/hide buttons
    const btnPrev   = document.getElementById('btnPrev');
    const btnNext   = document.getElementById('btnNext');
    const btnSubmit = document.getElementById('btnSubmit');

    btnPrev.classList.toggle('d-none', currentStep === 1);

    if (currentStep === totalSteps) {
        btnNext.classList.add('d-none');
        btnSubmit.classList.remove('d-none');
    } else {
        btnNext.classList.remove('d-none');
        btnSubmit.classList.add('d-none');
    }
}

document.getElementById('btnNext').addEventListener('click', function() {
    if (currentStep < totalSteps) {
        if (currentStep === 1) {
            const nikEl  = document.querySelector('#modalCreate input[name="nik"]');
            const namaEl = document.querySelector('#modalCreate input[name="nama"]');
            let valid = true;

            [nikEl, namaEl].forEach(el => el.classList.remove('is-invalid'));

            if (!nikEl.value.trim()) {
                nikEl.classList.add('is-invalid');
                getOrCreateFeedback(nikEl).textContent = 'NIK wajib diisi.';
                valid = false;
            } else if (nikEl.value.trim().length !== 16) {
                nikEl.classList.add('is-invalid');
                getOrCreateFeedback(nikEl).textContent = 'NIK harus 16 digit.';
                valid = false;
            }
            if (!namaEl.value.trim()) {
                namaEl.classList.add('is-invalid');
                getOrCreateFeedback(namaEl).textContent = 'Nama Lengkap wajib diisi.';
                valid = false;
            }

            // Validasi usia minimal 17 tahun
            const tglLahirEl = document.getElementById('tanggal_lahir');
            if (tglLahirEl && tglLahirEl.value) {
                const tgl = new Date(tglLahirEl.value);
                const now = new Date();
                let age   = now.getFullYear() - tgl.getFullYear();
                const m   = now.getMonth() - tgl.getMonth();
                if (m < 0 || (m === 0 && now.getDate() < tgl.getDate())) age--;
                if (age < 17) {
                    tglLahirEl.classList.add('is-invalid');
                    valid = false;
                } else {
                    tglLahirEl.classList.remove('is-invalid');
                }
            }

            // Validasi wilayah pemilik
            const provEl  = document.querySelector('#modalCreate select[name="provinsi_pemilik"]');
            const kabEl   = document.querySelector('#modalCreate select[name="kabupaten_pemilik"]');
            const kecEl   = document.querySelector('#modalCreate select[name="kecamatan_pemilik"]');
            const desaEl  = document.querySelector('#modalCreate select[name="desa_pemilik"]');
            [provEl, kabEl, kecEl, desaEl].forEach(el => el.classList.remove('is-invalid'));
            if (!provEl.value) { provEl.classList.add('is-invalid'); valid = false; }
            if (!kabEl.value)  { kabEl.classList.add('is-invalid');  valid = false; }
            if (!kecEl.value)  { kecEl.classList.add('is-invalid');  valid = false; }
            if (!desaEl.value) { desaEl.classList.add('is-invalid'); valid = false; }

            if (!valid) return;

        } else if (currentStep === 2) {
            const namaUsahaEl   = document.querySelector('#modalCreate input[name="nama_usaha"]');
            const kategoriEl    = document.querySelector('#modalCreate select[name="id_kategori_usaha"]');
            const kecUsahaEl    = document.querySelector('#modalCreate select[name="kecamatan_usaha"]');
            const desaUsahaEl   = document.querySelector('#modalCreate select[name="desa_usaha"]');
            const karyawanEl    = document.querySelector('#modalCreate input[name="karyawan"]');
            const omsetEl       = document.querySelector('#modalCreate input[name="omset_bulanan_rp"]');
            const asetEl        = document.querySelector('#modalCreate input[name="aset_rp"]');
            let valid2 = true;

            [namaUsahaEl, kategoriEl, kecUsahaEl, desaUsahaEl, karyawanEl, omsetEl, asetEl].forEach(el => el.classList.remove('is-invalid'));

            if (!namaUsahaEl.value.trim()) {
                namaUsahaEl.classList.add('is-invalid');
                getOrCreateFeedback(namaUsahaEl).textContent = 'Nama Usaha wajib diisi.';
                valid2 = false;
            }
            if (!kategoriEl.value) { kategoriEl.classList.add('is-invalid'); valid2 = false; }
            if (!kecUsahaEl.value) { kecUsahaEl.classList.add('is-invalid'); valid2 = false; }
            if (!desaUsahaEl.value) { desaUsahaEl.classList.add('is-invalid'); valid2 = false; }
            if (karyawanEl.value === '' || karyawanEl.value === null) { karyawanEl.classList.add('is-invalid'); valid2 = false; }
            if (omsetEl.value === '' || omsetEl.value === null) { omsetEl.classList.add('is-invalid'); valid2 = false; }
            if (asetEl.value === '' || asetEl.value === null) { asetEl.classList.add('is-invalid'); valid2 = false; }

            if (!valid2) return;
        }

        currentStep++;
        updateProgress();
    }
});

document.getElementById('btnPrev').addEventListener('click', function() {
    if (currentStep > 1) {
        currentStep--;
        updateProgress();
    }
});

// Reset wizard when modal is closed
document.getElementById('modalCreate').addEventListener('hidden.bs.modal', function() {
    currentStep = 1;
    updateProgress();
    document.getElementById('formCreate').reset();
    
    // Reset legalitas dan sosmed ke 1 item
    document.getElementById('legalitasContainer').innerHTML = `
        <div class="legalitas-item border rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Jenis Dokumen</label>
                    <select class="form-select" name="legalitas_jenis[]">
                        <option value="">Pilih Jenis</option>
                        <option value="NIB">NIB</option>
                        <option value="NPWP">NPWP</option>
                        <option value="SIUP">SIUP</option>
                        <option value="TDP">TDP</option>
                        <option value="Sertifikat Halal">Sertifikat Halal</option>
                        <option value="PIRT">PIRT</option>
                        <option value="HKI">HKI</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Nomor Dokumen</label>
                    <input type="text" class="form-control" name="legalitas_nomor[]">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeLegalitas(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.getElementById('sosmedContainer').innerHTML = `
        <div class="sosmed-item border rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Platform</label>
                    <select class="form-select" name="sosmed_platform[]">
                        <option value="">Pilih Platform</option>
                        <option value="Instagram">Instagram</option>
                        <option value="Facebook">Facebook</option>
                        <option value="TikTok">TikTok</option>
                        <option value="WhatsApp">WhatsApp</option>
                        <option value="Twitter">Twitter</option>
                        <option value="YouTube">YouTube</option>
                        <option value="Website">Website</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">URL/Username</label>
                    <input type="text" class="form-control" name="sosmed_url[]" placeholder="@username atau https://...">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSosmed(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
});

// Add/Remove Legalitas
function addLegalitas() {
    const container = document.getElementById('legalitasContainer');
    const newItem = document.createElement('div');
    newItem.className = 'legalitas-item border rounded p-3 mb-3';
    newItem.innerHTML = `
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Jenis Dokumen</label>
                <select class="form-select" name="legalitas_jenis[]">
                    <option value="">Pilih Jenis</option>
                    <option value="NIB">NIB</option>
                    <option value="NPWP">NPWP</option>
                    <option value="SIUP">SIUP</option>
                    <option value="TDP">TDP</option>
                    <option value="Sertifikat Halal">Sertifikat Halal</option>
                    <option value="PIRT">PIRT</option>
                    <option value="HKI">HKI</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">Nomor Dokumen</label>
                <input type="text" class="form-control" name="legalitas_nomor[]">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeLegalitas(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeLegalitas(btn) {
    const container = document.getElementById('legalitasContainer');
    if (container.children.length > 1) {
        btn.closest('.legalitas-item').remove();
    } else {
        showAlert('warning', 'Minimal harus ada 1 item legalitas');
    }
}

// Add/Remove Sosmed
function addSosmed() {
    const container = document.getElementById('sosmedContainer');
    const newItem = document.createElement('div');
    newItem.className = 'sosmed-item border rounded p-3 mb-3';
    newItem.innerHTML = `
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">Platform</label>
                <select class="form-select" name="sosmed_platform[]">
                    <option value="">Pilih Platform</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Facebook">Facebook</option>
                    <option value="TikTok">TikTok</option>
                    <option value="WhatsApp">WhatsApp</option>
                    <option value="Twitter">Twitter</option>
                    <option value="YouTube">YouTube</option>
                    <option value="Website">Website</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">URL/Username</label>
                <input type="text" class="form-control" name="sosmed_url[]" placeholder="@username atau https://...">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeSosmed(this)">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeSosmed(btn) {
    const container = document.getElementById('sosmedContainer');
    if (container.children.length > 1) {
        btn.closest('.sosmed-item').remove();
    } else {
        showAlert('warning', 'Minimal harus ada 1 item media sosial');
    }
}

// Create
document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = document.getElementById('btnSubmit');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
    
    fetch("{{ route('pendamping.data-umkm.store') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalCreate')).hide();
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showAlert('danger', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Simpan';
    });
});

// Show Detail
function showDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetail'));
    modal.show();
    
    fetch(`/pendamping/data-umkm/${id}/detail`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pemilik = data.pemilik;
                const usaha = data.usaha;
                const legalitas = data.legalitas;
                const sosialMedia = data.sosialMedia;
                
                document.getElementById('detailContent').innerHTML = `
                    <!-- Header Info -->
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
                                        <div>
                                            <small class="text-muted d-block">Pemilik</small>
                                            <span class="fw-medium">${pemilik.nama}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-map-pin-line text-danger me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Lokasi</small>
                                            <span class="fw-medium">${usaha.kecamatan_usaha || '-'}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-folder-line text-warning me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Kategori</small>
                                            <span class="fw-medium">${usaha.kategori_usaha || '-'}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="nav-align-top">
                        <ul class="nav nav-pills mb-4" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#detailPemilik">
                                    <i class="ri-user-line me-1"></i> Data Pemilik
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#detailUsaha">
                                    <i class="ri-store-3-line me-1"></i> Data Usaha
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#detailLegalitas">
                                    <i class="ri-file-text-line me-1"></i> Legalitas
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#detailSosmed">
                                    <i class="ri-share-line me-1"></i> Media Sosial
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content p-0">
                            <!-- Tab Data Pemilik -->
                            <div class="tab-pane fade show active" id="detailPemilik" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-4"><i class="ri-user-line me-2"></i>Identitas Pemilik</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="border-start border-primary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">NIK</small>
                                                    <p class="fw-semibold mb-0">${pemilik.nik}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="border-start border-primary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Nama Lengkap</small>
                                                    <p class="fw-semibold mb-0">${pemilik.nama}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-info border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Tempat Lahir</small>
                                                    <p class="fw-semibold mb-0">${pemilik.tempat_lahir || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-info border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Tanggal Lahir</small>
                                                    <p class="fw-semibold mb-0">${pemilik.tanggal_lahir ? new Date(pemilik.tanggal_lahir).toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) : '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-info border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Jenis Kelamin</small>
                                                    <p class="fw-semibold mb-0">${pemilik.jenis_kelamin == 'L' ? 'Laki-laki' : (pemilik.jenis_kelamin == 'P' ? 'Perempuan' : '-')}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-phone-line me-2"></i>Kontak</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="border-start border-success border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">No. HP/WA</small>
                                                    <p class="fw-semibold mb-0">${pemilik.hp || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="border-start border-success border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Email</small>
                                                    <p class="fw-semibold mb-0">${pemilik.email || '-'}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-map-pin-line me-2"></i>Alamat</h6>
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Provinsi</small>
                                                    <p class="fw-semibold mb-0">${pemilik.provinsi_pemilik || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Kabupaten/Kota</small>
                                                    <p class="fw-semibold mb-0">${pemilik.kabupaten_pemilik || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Kecamatan</small>
                                                    <p class="fw-semibold mb-0">${pemilik.kecamatan_pemilik || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Desa/Kelurahan</small>
                                                    <p class="fw-semibold mb-0">${pemilik.desa_pemilik || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="alert alert-secondary mb-0">
                                                    <small class="text-muted d-block mb-1">Alamat Lengkap</small>
                                                    <p class="mb-0">${pemilik.alamat_pemilik || '-'}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-information-line me-2"></i>Informasi Tambahan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="border-start border-primary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">BPJS Ketenagakerjaan</small>
                                                    <p class="fw-semibold mb-0">
                                                        ${pemilik.bpjs_ketenagakerjaan ? '<span class="badge bg-success">Sudah</span>' : '<span class="badge bg-secondary">Belum</span>'}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="border-start border-primary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">BPJS Kesehatan</small>
                                                    <p class="fw-semibold mb-0">
                                                        ${pemilik.bpjs_kesehatan ? '<span class="badge bg-success">Sudah</span>' : '<span class="badge bg-secondary">Belum</span>'}
                                                    </p>
                                                </div>
                                            </div>
                                            ${pemilik.ikut_forum ? `
                                            <div class="col-md-6">
                                                <div class="border-start border-info border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Forum Usaha</small>
                                                    <p class="fw-semibold mb-1">${pemilik.nama_forum || '-'}</p>
                                                    <small class="text-muted">Jabatan: ${pemilik.jabatan_forum || '-'}</small>
                                                </div>
                                            </div>
                                            ` : ''}
                                            ${pemilik.ikut_koperasi ? `
                                            <div class="col-md-6">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Koperasi</small>
                                                    <p class="fw-semibold mb-1">${pemilik.nama_koperasi || '-'}</p>
                                                    <small class="text-muted">Jabatan: ${pemilik.jabatan_koperasi || '-'}</small>
                                                </div>
                                            </div>
                                            ` : ''}
                                            ${pemilik.ikut_pelatihan ? `
                                            <div class="col-md-6">
                                                <div class="border-start border-success border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Pelatihan Terakhir</small>
                                                    <p class="fw-semibold mb-0">${pemilik.nama_pelatihan || '-'}</p>
                                                </div>
                                            </div>
                                            ` : ''}
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-file-list-line me-2"></i>Informasi Pendataan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Didata Oleh</small>
                                                    <p class="fw-semibold mb-0">${usaha.pendata || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Tanggal Input</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_input || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Terakhir Diupdate</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_update || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="detailUsaha" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-4"><i class="ri-store-3-line me-2"></i>Informasi Usaha</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="border-start border-primary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Nama Usaha</small>
                                                    <p class="fw-semibold mb-0">${usaha.nama_usaha}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="border-start border-primary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Merek/Brand</small>
                                                    <p class="fw-semibold mb-0">${usaha.merek || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="border-start border-info border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Kategori Usaha</small>
                                                    <p class="fw-semibold mb-0">${usaha.kategori_usaha || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="border-start border-info border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Kelas Usaha</small>
                                                    <p class="fw-semibold mb-0">
                                                        ${usaha.kelas_usaha ? `<span class="badge bg-label-primary">${usaha.kelas_usaha}</span>` : '<span class="badge bg-label-secondary">Belum Ditentukan</span>'}
                                                    </p>
                                                    <small class="text-muted">Otomatis berdasarkan omset dan aset</small>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-map-pin-line me-2"></i>Lokasi Usaha</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Kecamatan</small>
                                                    <p class="fw-semibold mb-0">${usaha.kecamatan_usaha || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="border-start border-warning border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Desa/Kelurahan</small>
                                                    <p class="fw-semibold mb-0">${usaha.desa_usaha || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="alert alert-secondary mb-0">
                                                    <small class="text-muted d-block mb-1">Alamat Lengkap</small>
                                                    <p class="mb-0">${usaha.alamat_usaha || '-'}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-line-chart-line me-2"></i>Data Finansial & Operasional</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="card border shadow-none">
                                                    <div class="card-body text-center py-3">
                                                        <i class="ri-team-line ri-24px text-primary mb-2"></i>
                                                        <h6 class="mb-1">${usaha.karyawan || 0}</h6>
                                                        <small class="text-muted">Karyawan</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card border shadow-none">
                                                    <div class="card-body text-center py-3">
                                                        <i class="ri-money-dollar-circle-line ri-24px text-success mb-2"></i>
                                                        <h6 class="mb-1">Rp ${new Intl.NumberFormat('id-ID').format((usaha.omset_bulanan_rp || 0) * 12)}</h6><small class="text-muted">Omset Tahunan</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card border shadow-none">
                                                    <div class="card-body text-center py-3">
                                                        <i class="ri-safe-line ri-24px text-warning mb-2"></i>
                                                        <h6 class="mb-1">Rp ${new Intl.NumberFormat('id-ID').format(usaha.aset_rp || 0)}</h6>
                                                        <small class="text-muted">Total Aset</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-file-list-line me-2"></i>Informasi Pendataan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Didata Oleh</small>
                                                    <p class="fw-semibold mb-0">${usaha.pendata || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Tanggal Input</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_input || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Terakhir Diupdate</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_update || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="detailLegalitas" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-4"><i class="ri-file-text-line me-2"></i>Dokumen Legalitas</h6>
                                        ${legalitas.length > 0 ? `
                                            <div class="row g-3">
                                                ${legalitas.map(item => `
                                                    <div class="col-md-6">
                                                        <div class="card border shadow-none">
                                                            <div class="card-body">
                                                                <div class="d-flex align-items-start">
                                                                    <div class="avatar avatar-sm me-3">
                                                                        <span class="avatar-initial rounded bg-label-success">
                                                                            <i class="ri-file-check-line"></i>
                                                                        </span>
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="mb-1">${item.jenis}</h6>
                                                                        <small class="text-muted">Nomor: ${item.nomor || '-'}</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `).join('')}
                                            </div>
                                        ` : `
                                            <div class="text-center py-5">
                                                <i class="ri-file-forbid-line ri-48px text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada dokumen legalitas</p>
                                            </div>
                                        `}

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-file-list-line me-2"></i>Informasi Pendataan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Didata Oleh</small>
                                                    <p class="fw-semibold mb-0">${usaha.pendata || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Tanggal Input</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_input || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Terakhir Diupdate</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_update || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="tab-pane fade" id="detailSosmed" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title mb-4"><i class="ri-share-line me-2"></i>Akun Media Sosial</h6>
                                        ${sosialMedia.length > 0 ? `
                                            <div class="row g-3">
                                                ${sosialMedia.map(item => {
                                                    let icon = 'ri-global-line';
                                                    let color = 'secondary';
                                                    
                                                    if (item.platform === 'Instagram') {
                                                        icon = 'ri-instagram-line';
                                                        color = 'danger';
                                                    } else if (item.platform === 'Facebook') {
                                                        icon = 'ri-facebook-line';
                                                        color = 'primary';
                                                    } else if (item.platform === 'TikTok') {
                                                        icon = 'ri-tiktok-line';
                                                        color = 'dark';
                                                    } else if (item.platform === 'WhatsApp') {
                                                        icon = 'ri-whatsapp-line';
                                                        color = 'success';
                                                    } else if (item.platform === 'Twitter') {
                                                        icon = 'ri-twitter-line';
                                                        color = 'info';
                                                    } else if (item.platform === 'YouTube') {
                                                        icon = 'ri-youtube-line';
                                                        color = 'danger';
                                                    } else if (item.platform === 'Website') {
                                                        icon = 'ri-global-line';
                                                        color = 'primary';
                                                    }
                                                    
                                                    return `
                                                        <div class="col-md-6">
                                                            <div class="card border shadow-none">
                                                                <div class="card-body">
                                                                    <div class="d-flex align-items-start">
                                                                        <div class="avatar avatar-sm me-3">
                                                                            <span class="avatar-initial rounded bg-label-${color}">
                                                                                <i class="${icon}"></i>
                                                                            </span>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <h6 class="mb-1">${item.platform}</h6>
                                                                            <small class="text-muted text-break">${item.url}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    `;
                                                }).join('')}
                                            </div>
                                        ` : `
                                            <div class="text-center py-5">
                                                <i class="ri-share-circle-line ri-48px text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada akun media sosial</p>
                                            </div>
                                        `}

                                        <h6 class="card-title mt-4 mb-4"><i class="ri-file-list-line me-2"></i>Informasi Pendataan</h6>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Didata Oleh</small>
                                                    <p class="fw-semibold mb-0">${usaha.pendata || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Tanggal Input</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_input || '-'}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="border-start border-secondary border-3 ps-3">
                                                    <small class="text-muted d-block mb-1">Terakhir Diupdate</small>
                                                    <p class="fw-semibold mb-0">${usaha.tanggal_update || '-'}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('detailContent').innerHTML = '<p class="text-danger">Gagal memuat data</p>';
        });
}

// Toggle Edit Fields
function toggleEditForumFields() {
    const checked = document.getElementById('edit_ikut_forum').checked;
    document.getElementById('edit_forum_fields').style.display = checked ? 'block' : 'none';
}

function toggleEditKoperasiFields() {
    const checked = document.getElementById('edit_ikut_koperasi').checked;
    document.getElementById('edit_koperasi_fields').style.display = checked ? 'block' : 'none';
}

function toggleEditPelatihanFields() {
    const checked = document.getElementById('edit_ikut_pelatihan').checked;
    document.getElementById('edit_pelatihan_fields').style.display = checked ? 'block' : 'none';
}

let editCurrentStep = 1;
const editTotalSteps = 4;

function updateEditProgress() {
    const progress = (editCurrentStep / editTotalSteps) * 100;
    document.getElementById('editProgressBar').style.width = progress + '%';

    document.querySelectorAll('#modalEdit .step-label').forEach((label, index) => {
        const step = index + 1;
        label.classList.remove('active', 'completed');

        if (step < editCurrentStep) {
            label.classList.add('completed');
        } else if (step === editCurrentStep) {
            label.classList.add('active');
        }
    });

    document.querySelectorAll('#modalEdit .step-content').forEach((content, index) => {
        content.classList.toggle('active', index + 1 === editCurrentStep);
    });

    document.getElementById('editBtnPrev').classList.toggle('d-none', editCurrentStep === 1);

    if (editCurrentStep === editTotalSteps) {
        document.getElementById('editBtnNext').classList.add('d-none');
        document.getElementById('editBtnSubmit').classList.remove('d-none');
    } else {
        document.getElementById('editBtnNext').classList.remove('d-none');
        document.getElementById('editBtnSubmit').classList.add('d-none');
    }
}

function setEditLoading(isLoading) {
    const modal = document.getElementById('modalEdit');
    modal.classList.toggle('is-loading', isLoading);
    document.getElementById('editBtnPrev').disabled = isLoading;
    document.getElementById('editBtnNext').disabled = isLoading;
    document.getElementById('editBtnSubmit').disabled = isLoading;
}

function editLegalitasTemplate(item = {}) {
    return `
        <div class="legalitas-item border rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Jenis Dokumen</label>
                    <select class="form-select" name="legalitas_jenis[]">
                        <option value="">Pilih Jenis</option>
                        <option value="NIB" ${item.jenis === 'NIB' ? 'selected' : ''}>NIB</option>
                        <option value="NPWP" ${item.jenis === 'NPWP' ? 'selected' : ''}>NPWP</option>
                        <option value="SIUP" ${item.jenis === 'SIUP' ? 'selected' : ''}>SIUP</option>
                        <option value="TDP" ${item.jenis === 'TDP' ? 'selected' : ''}>TDP</option>
                        <option value="Sertifikat Halal" ${item.jenis === 'Sertifikat Halal' ? 'selected' : ''}>Sertifikat Halal</option>
                        <option value="PIRT" ${item.jenis === 'PIRT' ? 'selected' : ''}>PIRT</option>
                        <option value="HKI" ${item.jenis === 'HKI' ? 'selected' : ''}>HKI</option>
                        <option value="Lainnya" ${item.jenis === 'Lainnya' ? 'selected' : ''}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Nomor Dokumen</label>
                    <input type="text" class="form-control" name="legalitas_nomor[]" value="${item.nomor || ''}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeEditLegalitas(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function editSosmedTemplate(item = {}) {
    return `
        <div class="sosmed-item border rounded p-3 mb-3">
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Platform</label>
                    <select class="form-select" name="sosmed_platform[]">
                        <option value="">Pilih Platform</option>
                        <option value="Instagram" ${item.platform === 'Instagram' ? 'selected' : ''}>Instagram</option>
                        <option value="Facebook" ${item.platform === 'Facebook' ? 'selected' : ''}>Facebook</option>
                        <option value="TikTok" ${item.platform === 'TikTok' ? 'selected' : ''}>TikTok</option>
                        <option value="WhatsApp" ${item.platform === 'WhatsApp' ? 'selected' : ''}>WhatsApp</option>
                        <option value="Twitter" ${item.platform === 'Twitter' ? 'selected' : ''}>Twitter</option>
                        <option value="YouTube" ${item.platform === 'YouTube' ? 'selected' : ''}>YouTube</option>
                        <option value="Website" ${item.platform === 'Website' ? 'selected' : ''}>Website</option>
                        <option value="Lainnya" ${item.platform === 'Lainnya' ? 'selected' : ''}>Lainnya</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <label class="form-label">URL/Username</label>
                    <input type="text" class="form-control" name="sosmed_url[]" placeholder="@username atau https://..." value="${item.url || ''}">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-danger w-100" onclick="removeEditSosmed(this)">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

function addEditLegalitas(item = {}) {
    const container = document.getElementById('edit_legalitasContainer');
    container.insertAdjacentHTML('beforeend', editLegalitasTemplate(item));
}

function removeEditLegalitas(btn) {
    const container = document.getElementById('edit_legalitasContainer');
    if (container.children.length > 1) {
        btn.closest('.legalitas-item').remove();
    } else {
        showAlert('warning', 'Minimal harus ada 1 item legalitas');
    }
}

function addEditSosmed(item = {}) {
    const container = document.getElementById('edit_sosmedContainer');
    container.insertAdjacentHTML('beforeend', editSosmedTemplate(item));
}

function removeEditSosmed(btn) {
    const container = document.getElementById('edit_sosmedContainer');
    if (container.children.length > 1) {
        btn.closest('.sosmed-item').remove();
    } else {
        showAlert('warning', 'Minimal harus ada 1 item media sosial');
    }
}

function resetEditCollections() {
    document.getElementById('edit_legalitasContainer').innerHTML = '';
    document.getElementById('edit_sosmedContainer').innerHTML = '';
    addEditLegalitas();
    addEditSosmed();
}

function resetEditForm() {
    document.getElementById('formEdit').reset();
    document.getElementById('edit_id').value = '';

    ['edit_provinsi_pemilik', 'edit_kabupaten_pemilik', 'edit_kecamatan_pemilik', 'edit_desa_pemilik', 'edit_desa_usaha_select']
        .forEach(id => fillSelect(document.getElementById(id), [], document.getElementById(id).options[0]?.textContent || 'Pilih'));

    document.getElementById('edit_kabupaten_pemilik').disabled = true;
    document.getElementById('edit_kecamatan_pemilik').disabled = true;
    document.getElementById('edit_desa_pemilik').disabled = true;
    document.getElementById('edit_desa_usaha_select').disabled = true;

    editCurrentStep = 1;
    updateEditProgress();
    toggleEditForumFields();
    toggleEditKoperasiFields();
    toggleEditPelatihanFields();
    resetEditCollections();
}

async function loadEditDesaUsaha(selectedDesa = '') {
    const kecamatanSelect = document.getElementById('edit_kecamatan_usaha_select');
    const kode = kecamatanSelect.options[kecamatanSelect.selectedIndex]?.dataset.kode;
    const desaSelect = document.getElementById('edit_desa_usaha_select');

    if (!kode) {
        fillSelect(desaSelect, [], 'Pilih Desa');
        desaSelect.disabled = true;
        return;
    }

    try {
        const data = await apiWilayah({ type: 'desa', kode });
        fillSelect(desaSelect, data, 'Pilih Desa');
        desaSelect.disabled = false;
        setSelectValue(desaSelect, selectedDesa);
    } catch (error) {
        console.error('Error loading desa:', error);
        desaSelect.disabled = true;
    }
}

async function fillEditWilayah(pemilik, usaha) {
    const provinsiSelect = document.getElementById('edit_provinsi_pemilik');
    const kabupatenSelect = document.getElementById('edit_kabupaten_pemilik');
    const kecamatanSelect = document.getElementById('edit_kecamatan_pemilik');
    const desaSelect = document.getElementById('edit_desa_pemilik');
    const kecamatanUsahaSelect = document.getElementById('edit_kecamatan_usaha_select');
    const [provinsiData, kecamatanUsahaData] = await Promise.all([
        apiWilayah({ type: 'provinsi' }),
        apiWilayah({ type: 'kecamatan', kode: '33.06' })
    ]);

    fillSelect(provinsiSelect, provinsiData, 'Pilih Provinsi');
    const provinsiKode = setSelectValue(provinsiSelect, pemilik.provinsi_pemilik);

    fillSelect(kecamatanUsahaSelect, kecamatanUsahaData, 'Pilih Kecamatan');
    const kecamatanUsahaKode = setSelectValue(kecamatanUsahaSelect, usaha.kecamatan_usaha);
    kecamatanUsahaSelect.disabled = false;

    if (provinsiKode) {
        const kabupatenData = await apiWilayah({ type: 'kabupaten', kode: provinsiKode });
        fillSelect(kabupatenSelect, kabupatenData, 'Pilih Kabupaten');
        kabupatenSelect.disabled = false;
        const kabupatenKode = setSelectValue(kabupatenSelect, pemilik.kabupaten_pemilik);

        if (kabupatenKode) {
            const kecamatanData = await apiWilayah({ type: 'kecamatan', kode: kabupatenKode });
            fillSelect(kecamatanSelect, kecamatanData, 'Pilih Kecamatan');
            kecamatanSelect.disabled = false;
            const kecamatanKode = setSelectValue(kecamatanSelect, pemilik.kecamatan_pemilik);

            if (kecamatanKode) {
                const desaData = await apiWilayah({ type: 'desa', kode: kecamatanKode });
                fillSelect(desaSelect, desaData, 'Pilih Desa');
                desaSelect.disabled = false;
                setSelectValue(desaSelect, pemilik.desa_pemilik);
            }
        }
    }

    if (kecamatanUsahaKode) {
        await loadEditDesaUsaha(usaha.desa_usaha);
    }
}

// Edit
function editData(id) {
    const modalElement = document.getElementById('modalEdit');
    const modalEdit = bootstrap.Modal.getOrCreateInstance(modalElement);

    resetEditForm();
    setEditLoading(true);
    modalEdit.show();

    fetch(`/pendamping/data-umkm/${id}/edit`)
        .then(response => response.json())
        .then(async data => {
            if (data.success) {
                const pemilik = data.pemilik;
                const usaha = data.usaha;
                const legalitas = data.legalitas || [];
                const sosialMedia = data.sosialMedia || [];

                document.getElementById('edit_id').value = usaha.id;
                document.getElementById('edit_nik').value = pemilik.nik || '';
                document.getElementById('edit_nama').value = pemilik.nama || '';
                document.getElementById('edit_tempat_lahir').value = pemilik.tempat_lahir || '';
                document.getElementById('edit_tanggal_lahir').value = normalizeDateForInput(pemilik.tanggal_lahir);
                document.getElementById('edit_jenis_kelamin').value = pemilik.jenis_kelamin || '';
                document.getElementById('edit_hp').value = normalizePhoneForInput(pemilik.hp);
                document.getElementById('edit_email').value = pemilik.email || '';
                document.getElementById('edit_alamat_pemilik').value = pemilik.alamat_pemilik || '';

                document.getElementById('edit_bpjs_ketenagakerjaan').checked = !!pemilik.bpjs_ketenagakerjaan;
                document.getElementById('edit_bpjs_kesehatan').checked = !!pemilik.bpjs_kesehatan;
                document.getElementById('edit_ikut_forum').checked = !!pemilik.ikut_forum;
                document.getElementById('edit_ikut_koperasi').checked = !!pemilik.ikut_koperasi;
                document.getElementById('edit_ikut_pelatihan').checked = !!pemilik.ikut_pelatihan;

                // Field forum/koperasi/pelatihan
                document.getElementById('edit_nama_forum').value = pemilik.nama_forum || '';
                document.getElementById('edit_jabatan_forum').value = pemilik.jabatan_forum || '';
                document.getElementById('edit_nama_koperasi').value = pemilik.nama_koperasi || '';
                document.getElementById('edit_jabatan_koperasi').value = pemilik.jabatan_koperasi || '';
                document.getElementById('edit_nama_pelatihan').value = pemilik.nama_pelatihan || '';
                toggleEditForumFields();
                toggleEditKoperasiFields();
                toggleEditPelatihanFields();

                await fillEditWilayah(pemilik, usaha);

                document.getElementById('edit_nama_usaha').value = usaha.nama_usaha || '';
                document.getElementById('edit_merek').value = usaha.merek || '';
                document.getElementById('edit_id_kategori_usaha').value = usaha.id_kategori_usaha || '';
                document.getElementById('edit_alamat_usaha').value = usaha.alamat_usaha || '';
                document.getElementById('edit_karyawan').value = usaha.karyawan || '';
                document.getElementById('edit_omset_bulanan_rp').value = usaha.omset_bulanan_rp || '';
                document.getElementById('edit_aset_rp').value = usaha.aset_rp || '';

                document.getElementById('edit_legalitasContainer').innerHTML = '';
                document.getElementById('edit_sosmedContainer').innerHTML = '';

                if (legalitas.length) {
                    legalitas.forEach(item => addEditLegalitas(item));
                } else {
                    addEditLegalitas();
                }

                if (sosialMedia.length) {
                    sosialMedia.forEach(item => addEditSosmed(item));
                } else {
                    addEditSosmed();
                }
            } else {
                throw new Error(data.message || 'Gagal memuat data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('danger', 'Gagal memuat data');
            modalEdit.hide();
        })
        .finally(() => {
            setEditLoading(false);
        });
}

document.getElementById('editBtnNext').addEventListener('click', function() {
    if (editCurrentStep < editTotalSteps) {
        if (editCurrentStep === 1) {
            const nikEl  = document.querySelector('#modalEdit input[name="nik"]');
            const namaEl = document.querySelector('#modalEdit input[name="nama"]');
            let valid = true;

            [nikEl, namaEl].forEach(el => el.classList.remove('is-invalid'));

            if (!nikEl.value.trim()) {
                nikEl.classList.add('is-invalid');
                getOrCreateFeedback(nikEl).textContent = 'NIK wajib diisi.';
                valid = false;
            } else if (nikEl.value.trim().length !== 16) {
                nikEl.classList.add('is-invalid');
                getOrCreateFeedback(nikEl).textContent = 'NIK harus 16 digit.';
                valid = false;
            }
            if (!namaEl.value.trim()) {
                namaEl.classList.add('is-invalid');
                getOrCreateFeedback(namaEl).textContent = 'Nama Lengkap wajib diisi.';
                valid = false;
            }

            // Validasi wilayah pemilik
            const provEl  = document.querySelector('#modalEdit select[name="provinsi_pemilik"]');
            const kabEl   = document.querySelector('#modalEdit select[name="kabupaten_pemilik"]');
            const kecEl   = document.querySelector('#modalEdit select[name="kecamatan_pemilik"]');
            const desaEl  = document.querySelector('#modalEdit select[name="desa_pemilik"]');
            [provEl, kabEl, kecEl, desaEl].forEach(el => el.classList.remove('is-invalid'));
            if (!provEl.value) { provEl.classList.add('is-invalid'); valid = false; }
            if (!kabEl.value)  { kabEl.classList.add('is-invalid');  valid = false; }
            if (!kecEl.value)  { kecEl.classList.add('is-invalid');  valid = false; }
            if (!desaEl.value) { desaEl.classList.add('is-invalid'); valid = false; }

            if (!valid) return;

        } else if (editCurrentStep === 2) {
            const namaUsahaEl   = document.querySelector('#modalEdit input[name="nama_usaha"]');
            const kategoriEl    = document.querySelector('#modalEdit select[name="id_kategori_usaha"]');
            const kecUsahaEl    = document.querySelector('#modalEdit select[name="kecamatan_usaha"]');
            const desaUsahaEl   = document.querySelector('#modalEdit select[name="desa_usaha"]');
            const karyawanEl    = document.querySelector('#modalEdit input[name="karyawan"]');
            const omsetEl       = document.querySelector('#modalEdit input[name="omset_bulanan_rp"]');
            const asetEl        = document.querySelector('#modalEdit input[name="aset_rp"]');
            let valid2 = true;

            [namaUsahaEl, kategoriEl, kecUsahaEl, desaUsahaEl, karyawanEl, omsetEl, asetEl].forEach(el => el.classList.remove('is-invalid'));

            if (!namaUsahaEl.value.trim()) {
                namaUsahaEl.classList.add('is-invalid');
                getOrCreateFeedback(namaUsahaEl).textContent = 'Nama Usaha wajib diisi.';
                valid2 = false;
            }
            if (!kategoriEl.value) { kategoriEl.classList.add('is-invalid'); valid2 = false; }
            if (!kecUsahaEl.value) { kecUsahaEl.classList.add('is-invalid'); valid2 = false; }
            if (!desaUsahaEl.value) { desaUsahaEl.classList.add('is-invalid'); valid2 = false; }
            if (karyawanEl.value === '' || karyawanEl.value === null) { karyawanEl.classList.add('is-invalid'); valid2 = false; }
            if (omsetEl.value === '' || omsetEl.value === null) { omsetEl.classList.add('is-invalid'); valid2 = false; }
            if (asetEl.value === '' || asetEl.value === null) { asetEl.classList.add('is-invalid'); valid2 = false; }

            if (!valid2) return;
        }

        editCurrentStep++;
        updateEditProgress();
    }
});

document.getElementById('editBtnPrev').addEventListener('click', function() {
    if (editCurrentStep > 1) {
        editCurrentStep--;
        updateEditProgress();
    }
});

document.getElementById('modalEdit').addEventListener('hidden.bs.modal', function() {
    resetEditForm();
    setEditLoading(false);
});

document.getElementById('formEdit').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit_id').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengupdate...';
    
    fetch(`/pendamping/data-umkm/${id}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
            if (data.item) {
                updateUmkmRow(data.item);
            }
            showAlert('success', data.message);
        } else {
            showAlert('danger', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat mengupdate data');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ri-save-line me-1"></i> Update';
    });
});

// Delete
function deleteData(id, nama) {
    Swal.fire({
        title: 'Hapus Data UMKM?',
        html: `Yakin ingin menghapus data UMKM <strong>"${nama}"</strong>?<br><small class="text-muted">Data pemilik, legalitas, dan media sosial juga akan terhapus.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/pendamping/data-umkm/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data', 'error');
            });
        }
    });
}

</script>
@endpush

