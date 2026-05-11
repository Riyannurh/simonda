@extends('layouts.app')

@section('title', 'Master Kategori Usaha')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-6">
    <h4 class="mb-0">Master Kategori Usaha</h4>
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
        <i class="ri-add-line me-1"></i> <span class="d-none d-sm-inline">Tambah Kategori</span>
    </button>
</div>

<div class="card">
    <div class="card-datatable table-responsive">
        <table class="table table-hover" id="tableKategori">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoriUsaha as $index => $item)
                <tr data-id="{{ $item->id }}">
                    <td>{{ $kategoriUsaha->firstItem() + $index }}</td>
                    <td class="kategori-nama">{{ $item->nama }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ri-more-2-line"></i>
                            </button>
                            <div class="dropdown-menu">
                                <button type="button" class="dropdown-item" onclick="editData({{ $item->id }}, '{{ $item->nama }}')">
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
                    <td colspan="3" class="text-center py-5">
                        <i class="ri-file-list-3-line ri-48px text-muted mb-3 d-block"></i>
                        <p class="text-muted">Belum ada data kategori usaha</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($kategoriUsaha->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center pt-3">
        <div class="text-muted">
            Menampilkan {{ $kategoriUsaha->firstItem() }} - {{ $kategoriUsaha->lastItem() }} dari {{ $kategoriUsaha->total() }} data
        </div>
        {{ $kategoriUsaha->links() }}
    </div>
    @endif
</div>

<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCreate">
                @csrf
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama kategori" required>
                        <div class="invalid-feedback"></div>
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
                <h5 class="modal-title">Edit Kategori Usaha</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEdit">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_id">
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="edit_nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" placeholder="Masukkan nama kategori" required>
                        <div class="invalid-feedback"></div>
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
// Clear invalid state saat user mengetik
document.querySelectorAll('#modalCreate input, #modalEdit input').forEach(el => {
    el.addEventListener('input', function() { this.classList.remove('is-invalid'); });
});

// Create
document.getElementById('formCreate').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const namaInput = this.querySelector('input[name="nama"]');
    namaInput.classList.remove('is-invalid');
    
    if (!namaInput.value.trim()) {
        namaInput.classList.add('is-invalid');
        namaInput.nextElementSibling.textContent = 'Nama kategori wajib diisi.';
        return;
    }
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch("{{ route('admin.kategori-usaha.store') }}", {
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
            document.getElementById('formCreate').reset();

            // Insert baris baru ke tabel tanpa reload
            const tbody = document.querySelector('#tableKategori tbody');
            const emptyRow = tbody.querySelector('td[colspan]');
            if (emptyRow) emptyRow.closest('tr').remove();

            const rowCount = tbody.querySelectorAll('tr').length + 1;
            const tr = document.createElement('tr');
            tr.dataset.id = data.item.id;
            tr.innerHTML = `
                <td>${rowCount}</td>
                <td class="kategori-nama">${data.item.nama}</td>
                <td>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ri-more-2-line"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button type="button" class="dropdown-item" onclick="editData(${data.item.id}, '${data.item.nama}')">
                                <i class="ri-pencil-line me-1"></i> Edit
                            </button>
                            <button type="button" class="dropdown-item text-danger" onclick="deleteData(${data.item.id}, '${data.item.nama}')">
                                <i class="ri-delete-bin-6-line me-1"></i> Hapus
                            </button>
                        </div>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
            showAlert('success', data.message);
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
function editData(id, nama) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    
    var modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));
    modalEdit.show();
}

document.getElementById('formEdit').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const namaInput = this.querySelector('input[name="nama"]');
    namaInput.classList.remove('is-invalid');
    
    if (!namaInput.value.trim()) {
        namaInput.classList.add('is-invalid');
        namaInput.nextElementSibling.textContent = 'Nama kategori wajib diisi.';
        return;
    }
    
    const id = document.getElementById('edit_id').value;
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    
    fetch("{{ route('admin.kategori-usaha.index') }}/" + id, {
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
            // Update baris di tabel langsung tanpa reload
            const id = document.getElementById('edit_id').value;
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                row.querySelector('.kategori-nama').textContent = data.item.nama;
            } else {
                location.reload();
            }
            showAlert('success', data.message);
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
        title: 'Hapus Kategori?',
        text: `Yakin ingin menghapus kategori "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch("{{ route('admin.kategori-usaha.index') }}/" + id, {
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
