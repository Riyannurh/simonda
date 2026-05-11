@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card mb-6">
            <div class="card-body text-center">
                <div class="avatar avatar-xl mx-auto mb-4">
                    @if(auth()->user()->photo)
                        <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="{{ auth()->user()->name }}" class="rounded-circle">
                    @else
                        <span class="avatar-initial rounded-circle bg-label-primary" style="font-size: 2rem;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                    @endif
                </div>
                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                <p class="text-muted mb-4">
                    @if(auth()->user()->role === 'admin')
                        <span class="badge bg-label-danger">Admin</span>
                    @elseif(auth()->user()->role === 'pendamping')
                        <span class="badge bg-label-info">Pendamping</span>
                    @elseif(auth()->user()->role === 'kepala_bagian')
                        <span class="badge bg-label-warning">Kepala Bagian</span>
                    @endif
                </p>
                <div class="d-flex justify-content-around border-top pt-4">
                    <div>
                        <h6 class="mb-0">{{ auth()->user()->nip }}</h6>
                        <small class="text-muted">NIP</small>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ auth()->user()->created_at->format('Y') }}</h6>
                        <small class="text-muted">Bergabung</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible mb-4" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        
        <div class="card mb-6">
            <div class="card-header">
                <h5 class="mb-0">Informasi Profil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" value="{{ auth()->user()->nip }}" disabled>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-4">
                            <label for="role" class="form-label">Role</label>
                            <input type="text" class="form-control" id="role" value="{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}" disabled>
                        </div>
                    </div>
                    
                    @if(auth()->user()->wilayah_kode_kecamatan)
                    <div class="mb-4">
                        <label for="wilayah" class="form-label">Wilayah Kerja</label>
                        <input type="text" class="form-control" id="wilayah" value="{{ auth()->user()->wilayah?->nama ?? auth()->user()->wilayah_kode_kecamatan }}" disabled>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                        @error('new_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Minimal 8 karakter</small>
                    </div>
                    
                    <div class="mb-4">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-lock-line me-1"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
