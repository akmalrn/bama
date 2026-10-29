@extends('layout.layout')

@php
    $title = 'User';
    $subTitle = 'Create User';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.users.store') }}" method="POST">
        @csrf

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">User Information</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror"
                                    placeholder="Masukan Nama Lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" autocomplete="off"
                                    placeholder="Masukan Alamat Email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" id="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        autocomplete="new-password" placeholder="Password Minimal 6 Huruf" required>

                                    <span class="input-group-text" onclick="togglePassword('password', this)"
                                        style="cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>

                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control" placeholder="Konfirmasi Password" required>

                                    <span class="input-group-text" onclick="togglePassword('password_confirmation', this)"
                                        style="cursor: pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>

                                    <option value="">-- Pilih Role --</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                       {{-- <option value="Client">Client</option>
                                        <option value="Admin">Admin</option>
                                        <option value="Super Admin">Super Admin</option> --}}
                                </select>

                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body text-end">
                        <a href="{{ route('dashboard-admin.users.index') }}" class="btn btn-light me-2 px-32 py-12 radius-8">
                            Kembali
                        </a>

                        <button type="submit" id="btnSubmit" class="btn btn-primary px-32 py-12 radius-8">
                            Simpan User
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>
        function togglePassword(fieldId, el) {
            let input = document.getElementById(fieldId);
            let icon = el.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
