@extends('layouts.app')

@section('title', 'Master Legalitas Usaha')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Master Legalitas Usaha</h4>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
        <i class="ri-add-line me-1"></i> <span class="d-none d-sm-inline">Tambah Legalitas</span>
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible" role="alert">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Nama Legalitas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($legalitas as $index => $item)
                <tr>
                    <td>{{ $legalitas->firstItem() + $index }}</td>
                    <td>{{ $item->kode ?? '-' }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>
                        @if($item->is_active)
                            <span class="badge bg-label-success">Aktif</span>
                        @else
                            <span class="badge bg-label-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ri-more-2-line"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item" onclick="editData({{ $item->id }}, '{{ $item->kode }}', '{{ $item->nama }}', {{ $item->is_active ? 'true' : 'false' }})">
                                    <i class="ri-pencil-line me-1"></i> Edit
                                </button>
                                <form action="{{ route('admin.legalitas-usaha.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item text-danger" onclick="deleteData(this, '{{ $item->nama }}')">
                                        <i class="ri-delete-bin-6-line me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="ri-file-list-3-line ri-48px text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data legalitas usaha</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($legalitas->hasPages())
    <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Menampilkan {{ $legalitas->firstItem() }} - {{ $legalitas->lastItem() }} dari {{ $legalitas->total() }} data
            </div>
            {{ $legalitas->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Legalitas Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.legalitas-usaha.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="kode" class="form-label">Kode Legalitas</label>
                        <input type="text" class="form-control" id="kode" name="kode" value="{{ old('kode') }}" placeholder="Contoh: NIB, SIUP, dll">
                        <small class="text-muted">Opsional - Kode singkatan legalitas</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="nama" class="form-label">Nama Legalitas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama legalitas" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Legalitas Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="edit_kode" class="form-label">Kode Legalitas</label>
                        <input type="text" class="form-control" id="edit_kode" name="kode" placeholder="Contoh: NIB, SIUP, dll">
                        <small class="text-muted">Opsional - Kode singkatan legalitas</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="edit_nama" class="form-label">Nama Legalitas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" placeholder="Masukkan nama legalitas" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script>
function editData(id, kode, nama, isActive) {
    document.getElementById('formEdit').action = "{{ route('admin.legalitas-usaha.index') }}/" + id;
    document.getElementById('edit_kode').value = kode || '';
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_is_active').checked = isActive;
    
    var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    modalEdit.show();
}

// Auto show modal if there are validation errors
@if($errors->any() && old('_method') === null)
    var modalCreate = new bootstrap.Modal(document.getElementById('modalCreate'));
    modalCreate.show();
@elseif($errors->any() && old('_method') === 'PUT')
    // Show edit modal with old values if edit form has errors
    var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    modalEdit.show();
@endif

// Delete
function deleteData(btn, nama) {
    const form = btn.closest('form');
    Swal.fire({
        title: 'Hapus Legalitas?',
        text: `Yakin ingin menghapus legalitas "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
}
</script>
@endpush
