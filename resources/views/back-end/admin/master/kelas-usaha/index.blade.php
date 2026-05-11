@extends('layouts.app')

@section('title', 'Master Kelas Usaha')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Master Kelas Usaha</h4>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
        <i class="ri-add-line me-1"></i> <span class="d-none d-sm-inline">Tambah Kelas Usaha</span>
    </button>
</div>

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kelas</th>
                    <th>Rank</th>
                    <th>Omset Tahunan</th>
                    <th>Modal</th>
                    <th>Karyawan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelasUsaha as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->rank ?? '-' }}</td>
                    <td>
                        @if($item->min_omset_tahunan || $item->max_omset_tahunan)
                            Rp {{ number_format($item->min_omset_tahunan ?? 0, 0, ',', '.') }} - 
                            Rp {{ number_format($item->max_omset_tahunan ?? 0, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($item->min_modal || $item->max_modal)
                            Rp {{ number_format($item->min_modal ?? 0, 0, ',', '.') }} - 
                            Rp {{ number_format($item->max_modal ?? 0, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($item->min_karyawan || $item->max_karyawan)
                            {{ $item->min_karyawan ?? 0 }} - {{ $item->max_karyawan ?? 0 }} orang
                        @else
                            -
                        @endif
                    </td>
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
                                <button type="button" class="dropdown-item" onclick='editData(@json($item))'>
                                    <i class="ri-pencil-line me-1"></i> Edit
                                </button>
                                <button type="button" class="dropdown-item text-danger" onclick="deleteData({{ $item->id }}, '{{ $item->nama }}')">
                                    <i class="ri-delete-bin-6-line me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="ri-file-list-3-line ri-48px text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data kelas usaha</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kelas Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreate">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <label for="nama" class="form-label">Nama Kelas Usaha <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Contoh: Mikro, Kecil, Menengah" required>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="rank" class="form-label">Rank/Urutan</label>
                            <input type="number" class="form-control" id="rank" name="rank" placeholder="1, 2, 3...">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="min_omset_tahunan" class="form-label">Min. Omset Tahunan (Rp)</label>
                            <input type="number" class="form-control" id="min_omset_tahunan" name="min_omset_tahunan" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="max_omset_tahunan" class="form-label">Max. Omset Tahunan (Rp)</label>
                            <input type="number" class="form-control" id="max_omset_tahunan" name="max_omset_tahunan" placeholder="0">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="min_modal" class="form-label">Min. Modal (Rp)</label>
                            <input type="number" class="form-control" id="min_modal" name="min_modal" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="max_modal" class="form-label">Max. Modal (Rp)</label>
                            <input type="number" class="form-control" id="max_modal" name="max_modal" placeholder="0">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="min_karyawan" class="form-label">Min. Karyawan</label>
                            <input type="number" class="form-control" id="min_karyawan" name="min_karyawan" placeholder="0">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="max_karyawan" class="form-label">Max. Karyawan</label>
                            <input type="number" class="form-control" id="max_karyawan" name="max_karyawan" placeholder="0">
                        </div>
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kelas Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <label for="edit_nama" class="form-label">Nama Kelas Usaha <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama" name="nama" required>
                        </div>
                        <div class="col-md-4 mb-4">
                            <label for="edit_rank" class="form-label">Rank/Urutan</label>
                            <input type="number" class="form-control" id="edit_rank" name="rank">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="edit_min_omset_tahunan" class="form-label">Min. Omset Tahunan (Rp)</label>
                            <input type="number" class="form-control" id="edit_min_omset_tahunan" name="min_omset_tahunan">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="edit_max_omset_tahunan" class="form-label">Max. Omset Tahunan (Rp)</label>
                            <input type="number" class="form-control" id="edit_max_omset_tahunan" name="max_omset_tahunan">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="edit_min_modal" class="form-label">Min. Modal (Rp)</label>
                            <input type="number" class="form-control" id="edit_min_modal" name="min_modal">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="edit_max_modal" class="form-label">Max. Modal (Rp)</label>
                            <input type="number" class="form-control" id="edit_max_modal" name="max_modal">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="edit_min_karyawan" class="form-label">Min. Karyawan</label>
                            <input type="number" class="form-control" id="edit_min_karyawan" name="min_karyawan">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="edit_max_karyawan" class="form-label">Max. Karyawan</label>
                            <input type="number" class="form-control" id="edit_max_karyawan" name="max_karyawan">
                        </div>
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
<script>
// Create
document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch("{{ route('admin.kelas-usaha.store') }}", {
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
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan');
    })
    .finally(() => {
        submitBtn.disabled = false;
    });
});

// Edit
function editData(item) {
    document.getElementById('edit_id').value = item.id;
    document.getElementById('edit_nama').value = item.nama;
    document.getElementById('edit_rank').value = item.rank || '';
    document.getElementById('edit_min_omset_tahunan').value = item.min_omset_tahunan || '';
    document.getElementById('edit_max_omset_tahunan').value = item.max_omset_tahunan || '';
    document.getElementById('edit_min_modal').value = item.min_modal || '';
    document.getElementById('edit_max_modal').value = item.max_modal || '';
    document.getElementById('edit_min_karyawan').value = item.min_karyawan || '';
    document.getElementById('edit_max_karyawan').value = item.max_karyawan || '';
    document.getElementById('edit_is_active').checked = item.is_active;
    
    var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    modalEdit.show();
}

document.getElementById('formEdit').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('edit_id').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch("{{ route('admin.kelas-usaha.index') }}/" + id, {
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
            showAlert('success', data.message);
            setTimeout(() => location.reload(), 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan');
    })
    .finally(() => {
        submitBtn.disabled = false;
    });
});

// Delete
function deleteData(id, nama) {
    Swal.fire({
        title: 'Hapus Kelas Usaha?',
        text: `Yakin ingin menghapus kelas usaha "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('admin.kelas-usaha.index') }}/" + id, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Terhapus!', data.message, 'success').then(() => location.reload());
                } else {
                    Swal.fire('Gagal!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data', 'error');
            });
        }
    });
}

// Show Alert
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container-xxl');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => alertDiv.remove(), 5000);
}
</script>
@endpush
