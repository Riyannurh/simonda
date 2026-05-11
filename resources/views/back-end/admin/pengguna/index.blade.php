@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Manajemen Pengguna</h4>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
        <i class="ri-add-line me-1"></i> <span class="d-none d-sm-inline">Tambah Pengguna</span>
    </button>
</div>

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Wilayah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $index => $user)
                <tr>
                    <td>{{ $users->firstItem() + $index }}</td>
                    <td>{{ $user->nip }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge bg-label-danger">Admin</span>
                        @elseif($user->role === 'pendamping')
                            <span class="badge bg-label-info">Pendamping</span>
                        @elseif($user->role === 'kepala_bagian')
                            <span class="badge bg-label-warning">Kepala Bagian</span>
                        @endif
                    </td>
                    <td>{{ $user->wilayah->nama ?? '-' }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ri-more-2-line"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item" onclick='editData(@json($user))'>
                                    <i class="ri-pencil-line me-1"></i> Edit
                                </button>
                                @if($user->id !== auth()->id())
                                <button type="button" class="dropdown-item text-danger" onclick="deleteData({{ $user->id }}, '{{ $user->name }}')">
                                    <i class="ri-delete-bin-6-line me-1"></i> Hapus
                                </button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="ri-user-line ri-48px text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data pengguna</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center pt-3">
        <div class="text-muted">
            Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} dari {{ $users->total() }} data
        </div>
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreate">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukkan NIP" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama lengkap" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="email@example.com" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="pendamping">Pendamping</option>
                                <option value="kepala_bagian">Kepala Bagian</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="wilayah_kode_kecamatan" class="form-label">Kecamatan</label>
                            <select class="form-select" id="wilayah_kode_kecamatan" name="wilayah_kode_kecamatan" disabled>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatan as $item)
                                    <option value="{{ $item->kode }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>                            
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
                <h5 class="modal-title">Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="edit_nip" class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nip" name="nip" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="edit_name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="edit_email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="edit_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="edit_password" name="password" placeholder="Kosongkan jika tidak diubah">
                            <small class="text-muted">Minimal 8 karakter</small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="edit_role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_role" name="role" required>
                                <option value="">Pilih Role</option>
                                <option value="admin">Admin</option>
                                <option value="pendamping">Pendamping</option>
                                <option value="kepala_bagian">Kepala Bagian</option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="edit_wilayah_kode_kecamatan" class="form-label">Kecamatan</label>
                            <select class="form-select" id="edit_wilayah_kode_kecamatan" name="wilayah_kode_kecamatan" disabled>
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatan as $item)
                                    <option value="{{ $item->kode }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
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
// Toggle kecamatan dropdown based on role
function toggleKecamatanDropdown(roleSelectId, kecamatanSelectId) {
    const roleSelect = document.getElementById(roleSelectId);
    const kecamatanSelect = document.getElementById(kecamatanSelectId);
    
    if (roleSelect.value === 'pendamping') {
        kecamatanSelect.disabled = false;
    } else {
        kecamatanSelect.disabled = true;
        kecamatanSelect.value = ''; // Reset value
    }
}

// Event listener for role change in create modal
document.getElementById('role').addEventListener('change', function() {
    toggleKecamatanDropdown('role', 'wilayah_kode_kecamatan');
});

// Event listener for role change in edit modal
document.getElementById('edit_role').addEventListener('change', function() {
    toggleKecamatanDropdown('edit_role', 'edit_wilayah_kode_kecamatan');
});

// Clear validation errors
function clearValidationErrors(formId) {
    const form = document.getElementById(formId);
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}

// Show validation errors
function showValidationErrors(errors, prefix = '') {
    Object.keys(errors).forEach(key => {
        const input = document.getElementById(prefix + key);
        if (input) {
            input.classList.add('is-invalid');
            const feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('invalid-feedback')) {
                feedback.textContent = errors[key][0];
            }
        }
    });
}

// Reset form on modal close
document.getElementById('modalCreate').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formCreate').reset();
    clearValidationErrors('formCreate');
    // Reset kecamatan dropdown to disabled
    document.getElementById('wilayah_kode_kecamatan').disabled = true;
});

document.getElementById('modalEdit').addEventListener('hidden.bs.modal', function() {
    document.getElementById('formEdit').reset();
    clearValidationErrors('formEdit');
    // Reset kecamatan dropdown to disabled
    document.getElementById('edit_wilayah_kode_kecamatan').disabled = true;
});

// Create
document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('formCreate');
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
    
    fetch("{{ route('admin.pengguna.store') }}", {
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
            if (data.errors) {
                showValidationErrors(data.errors);
            } else {
                showAlert('danger', data.message || 'Terjadi kesalahan');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat menyimpan data');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Edit
function editData(user) {
    clearValidationErrors('formEdit');
    
    document.getElementById('edit_id').value = user.id;
    document.getElementById('edit_nip').value = user.nip;
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_wilayah_kode_kecamatan').value = user.wilayah_kode_kecamatan || '';
    document.getElementById('edit_password').value = '';
    
    // Enable/disable kecamatan dropdown based on role
    toggleKecamatanDropdown('edit_role', 'edit_wilayah_kode_kecamatan');
    
    var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    modalEdit.show();
}

document.getElementById('formEdit').addEventListener('submit', function(e) {
    e.preventDefault();
    clearValidationErrors('formEdit');
    
    const id = document.getElementById('edit_id').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memperbarui...';
    
    fetch("{{ route('admin.pengguna.index') }}/" + id, {
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
        } else {
            if (data.errors) {
                showValidationErrors(data.errors, 'edit_');
            } else {
                showAlert('danger', data.message || 'Terjadi kesalahan');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('danger', 'Terjadi kesalahan saat memperbarui data');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    });
});

// Delete
function deleteData(id, nama) {
    Swal.fire({
        title: 'Hapus Pengguna?',
        text: `Yakin ingin menghapus pengguna "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('admin.pengguna.index') }}/" + id, {
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
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.querySelector('.container-xxl');
    container.insertBefore(alertDiv, container.firstChild);
    
    setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 150);
    }, 5000);
}
</script>
@endpush
