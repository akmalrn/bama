@extends('layout.layout')

@php
    $title = 'User';
    $subTitle = 'Edit User';
@endphp

@section('content')
    <form action="{{ route('dashboard-admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Edit User</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-6">
                                <label class="form-label">Nama</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password Baru</label>

                                <div class="input-group">
                                    <input type="password" name="password" id="password" class="form-control"
                                        autocomplete="new-password">

                                    <button type="button" class="input-group-text bg-white toggle-password"
                                        data-target="password" style="cursor: pointer;">
                                        <iconify-icon icon="mdi:eye-outline" id="icon-password"></iconify-icon>
                                    </button>
                                </div>

                                <small class="text-secondary-light d-block mt-2">
                                    Kosongkan jika tidak ingin mengubah password
                                </small>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    @foreach ($roles as $role)
                                        @if ($role->name != 'karyawan')
                                            <option value="{{ $role->name }}">
                                                {{ $role->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
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

                        <button type="submit" id="btnUpdate" class="btn btn-primary px-32 py-12 radius-8">
                            Update User
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {

            button.addEventListener('click', function() {

                const input = document.getElementById(this.dataset.target);
                const icon = this.querySelector('iconify-icon');

                if (input.type === "password") {
                    input.type = "text";
                    icon.setAttribute("icon", "mdi:eye-off-outline");
                } else {
                    input.type = "password";
                    icon.setAttribute("icon", "mdi:eye-outline");
                }

            });

        });
    </script>
@endsection
