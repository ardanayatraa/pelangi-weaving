@extends('layouts.customer')

@section('title', 'Profile Saya')

@push('styles')
<style>
    .nav-tabs .nav-link {
        color: #6B7280;
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link:hover {
        color: #DC2626;
        border-bottom-color: rgba(220, 38, 38, 0.3);
    }
    .nav-tabs .nav-link.active {
        color: #DC2626;
        border-bottom-color: #DC2626;
        background: transparent;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Header Profile -->
    <div class="card card-custom mb-4" style="background: linear-gradient(135deg, #DC2626 0%, #B91C1C 100%); border: none;">
        <div class="card-body p-4">
            <div class="row align-items-center">
                <div class="col-auto">
                    <div class="d-flex align-items-center justify-content-center" 
                         style="width: 80px; height: 80px; background: rgba(255,255,255,0.2); border-radius: 50%; backdrop-filter: blur(10px);">
                        <i class="bi bi-person-fill text-white" style="font-size: 40px;"></i>
                    </div>
                </div>
                <div class="col">
                    <h4 class="text-white fw-bold mb-1">{{ $pelanggan->nama }}</h4>
                    <p class="text-white mb-2" style="opacity: 0.9;">{{ $pelanggan->email }}</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.index') }}" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="bi bi-box-seam"></i> Pesanan
                        </a>
                        <a href="{{ route('cart.index') }}" class="btn btn-sm text-white" style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="bi bi-cart3"></i> Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <div class="card card-custom">
                <div class="card-body p-0">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs border-0" style="padding: 1rem 1rem 0 1rem;">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#profile-info" style="color: #6B7280; border: none; border-bottom: 3px solid transparent; padding-bottom: 1rem;">
                                <i class="bi bi-person-fill"></i> Informasi Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#change-password" style="color: #6B7280; border: none; border-bottom: 3px solid transparent; padding-bottom: 1rem;">
                                <i class="bi bi-shield-lock-fill"></i> Ubah Password
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content p-4">
                        <!-- Profile Info Tab -->
                        <div class="tab-pane fade show active" id="profile-info">
                            <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                                       value="{{ old('nama', $pelanggan->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $pelanggan->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nomor Telepon</label>
                                <input type="text" name="telepon" class="form-control @error('telepon') is-invalid @enderror" 
                                       value="{{ old('telepon', $pelanggan->telepon) }}" required>
                                @error('telepon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Kode Pos</label>
                                <input type="text" name="kode_pos" class="form-control @error('kode_pos') is-invalid @enderror" 
                                       value="{{ old('kode_pos', $pelanggan->kode_pos) }}">
                                @error('kode_pos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-check-circle"></i> Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="change-password">
                            <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Password Saat Ini</label>
                                <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Password Baru</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 8 karakter</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                        </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary-custom">
                                        <i class="bi bi-shield-check"></i> Ubah Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Stats -->
        <div class="col-lg-4">
            <div class="card card-custom mb-3">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-box-seam" style="font-size: 28px; color: #DC2626;"></i>
                    <h4 class="fw-bold mt-2 mb-1" style="color: #1F2937;">{{ $pelanggan->orders()->count() }}</h4>
                    <p class="text-muted small mb-0">Total Pesanan</p>
                </div>
            </div>

            <div class="card card-custom mb-3">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-clock-history" style="font-size: 28px; color: #DC2626;"></i>
                    <h4 class="fw-bold mt-2 mb-1" style="color: #1F2937;">{{ $pelanggan->orders()->where('status_pesanan', 'pending')->count() }}</h4>
                    <p class="text-muted small mb-0">Pesanan Pending</p>
                </div>
            </div>

            <div class="card card-custom">
                <div class="card-body p-3 text-center">
                    <i class="bi bi-check-circle-fill" style="font-size: 28px; color: #10B981;"></i>
                    <h4 class="fw-bold mt-2 mb-1" style="color: #1F2937;">{{ $pelanggan->orders()->where('status_pesanan', 'selesai')->count() }}</h4>
                    <p class="text-muted small mb-0">Pesanan Selesai</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
