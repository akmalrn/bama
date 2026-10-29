@extends('layout.layout')

@php
    $title = 'Profil Saya';
    $subTitle = 'Profil Saya';
@endphp

@section('content')
    @php
        $user = auth()->user();
        $initial = strtoupper(substr($user->name, 0, 1));
    @endphp

    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="card h-100 p-24">

                <div class="text-center">

                    <div class="d-flex justify-content-center">
                        @if ($user->foto)
                            <img src="{{ asset($user->foto) }}" class="rounded-circle"
                                style="width:120px;height:120px;object-fit:cover;">
                        @else
                            <div class="w-120-px h-120-px rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                style="background:#FF7800; font-size: 60px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <h5 class="mt-16 mb-4">{{ $user->name }}</h5>
                    <span class="text-secondary-light">{{ $user->email }}</span>

                </div>

                <hr>

                <div class="mt-24">
                    <h6 class="text-xl mb-16">Personal Info</h6>
                    <ul>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Nama</span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{ $user->name ?? '-' }}
                            </span>
                        </li>

                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">Email</span>
                            <span class="w-70 text-secondary-light fw-medium">
                                : {{ $user->email ?? '-' }}
                            </span>
                        </li>

                    </ul>
                </div>

            </div>
        </div>


        {{-- ================= EDIT PROFILE ================= --}}
        @php
            $user = auth()->user();
        @endphp

        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-body p-24">

                    {{-- ================= TABS ================= --}}
                    <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" role="tablist">

                        <li class="nav-item">
                            <button class="nav-link active px-24" data-bs-toggle="pill" data-bs-target="#edit-profile"
                                type="button">
                                Edit Profile
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link px-24" data-bs-toggle="pill" data-bs-target="#change-password"
                                type="button">
                                Change Password
                            </button>
                        </li>

                        {{-- Notification dikomen --}}
                        {{--
                <li class="nav-item">
                    <button class="nav-link px-24"
                            data-bs-toggle="pill"
                            data-bs-target="#notification"
                            type="button">
                        Notification Settings
                    </button>
                </li>
                --}}

                    </ul>

                    <div class="tab-content">

                        {{-- ================= EDIT PROFILE ================= --}}
                        <div class="tab-pane fade show active" id="edit-profile">

                            <form action="{{ route('dashboard-admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <h6 class="text-md text-primary-light mb-16">Gambar Profil</h6>

                                    <div class="mb-24 mt-16">
                                        <div class="avatar-upload">

                                            <div
                                                class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                                <input type="file" id="imageUpload" name="foto"
                                                    accept=".png, .jpg, .jpeg" hidden>
                                                <label for="imageUpload"
                                                    class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                    <iconify-icon icon="solar:camera-outline"
                                                        class="icon"></iconify-icon>
                                                </label>
                                            </div>

                                            <div class="avatar-preview">
                                                <div id="imagePreview"
                                                    style="background-image: url('{{ $user->foto ? asset($user->foto) : asset('assets/images/user.png') }}');">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold">Nama</label>
                                        <input type="text" name="name" class="form-control radius-8"
                                            value="{{ old('name', $user->name) }}" required>
                                    </div>

                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" class="form-control radius-8"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-32 radius-8">
                                        Simpan Perubahan
                                    </button>
                                </div>

                            </form>

                        </div>


                        {{-- ================= CHANGE PASSWORD ================= --}}
                        <div class="tab-pane fade" id="change-password">

                            <form action="{{ route('dashboard-admin.profile.password') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold">
                                            Password Baru
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" name="password"
                                                class="form-control radius-8 password-input"
                                                placeholder="Masukkan password baru" required>

                                            <span
                                                class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
                                                style="cursor: pointer;">
                                                <iconify-icon icon="mdi:eye-outline"></iconify-icon>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-20">
                                        <label class="form-label fw-semibold">
                                            Konfirmasi Password
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" name="password_confirmation"
                                                class="form-control radius-8 password-input"
                                                placeholder="Konfirmasi password" required>

                                            <span
                                                class="toggle-password position-absolute top-50 end-0 translate-middle-y me-3"
                                                style="cursor: pointer;">
                                                <iconify-icon icon="mdi:eye-outline"></iconify-icon>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-32 radius-8">
                                        Perbarui Password
                                    </button>
                                </div>

                            </form>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- ================= NOTIFICATION TAB (DIKOMEN) ================= --}}
    {{-- <li class="nav-item" role="presentation">
    <button class="nav-link d-flex align-items-center px-24" id="pills-notification-tab" data-bs-toggle="pill"
        data-bs-target="#pills-notification" type="button" role="tab" aria-controls="pills-notification"
        aria-selected="false" tabindex="-1">
        Notification Settings
    </button>
</li>
<div class="tab-pane fade" id="pills-notification" role="tabpanel" aria-labelledby="pills-notification-tab"
    tabindex="0">
    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
        <label for="companzNew" class="position-absolute w-100 h-100 start-0 top-0"></label>
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Company
                News</span>
            <input class="form-check-input" type="checkbox" role="switch" id="companzNew">
        </div>
    </div>
    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
        <label for="pushNotifcation" class="position-absolute w-100 h-100 start-0 top-0"></label>
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Push
                Notification</span>
            <input class="form-check-input" type="checkbox" role="switch" id="pushNotifcation" checked>
        </div>
    </div>
    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
        <label for="weeklyLetters" class="position-absolute w-100 h-100 start-0 top-0"></label>
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Weekly News
                Letters</span>
            <input class="form-check-input" type="checkbox" role="switch" id="weeklyLetters" checked>
        </div>
    </div>
    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
        <label for="meetUp" class="position-absolute w-100 h-100 start-0 top-0"></label>
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Meetups
                Near you</span>
            <input class="form-check-input" type="checkbox" role="switch" id="meetUp">
        </div>
    </div>
    <div class="form-switch switch-primary py-12 px-16 border radius-8 position-relative mb-16">
        <label for="orderNotification" class="position-absolute w-100 h-100 start-0 top-0"></label>
        <div class="d-flex align-items-center gap-3 justify-content-between">
            <span class="form-check-label line-height-1 fw-medium text-secondary-light">Orders
                Notifications</span>
            <input class="form-check-input" type="checkbox" role="switch" id="orderNotification" checked>
        </div>
    </div>
</div>
--}}
    <script>
        document.addEventListener("click", function(e) {

            const toggle = e.target.closest(".toggle-password");
            if (!toggle) return;

            const input = toggle.parentElement.querySelector(".password-input");
            const icon = toggle.querySelector("iconify-icon");

            if (input.type === "password") {
                input.type = "text";
                icon.setAttribute("icon", "mdi:eye-off-outline");
            } else {
                input.type = "password";
                icon.setAttribute("icon", "mdi:eye-outline");
            }

        });
        document.getElementById("imageUpload").addEventListener("change", function() {
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById("imagePreview").style.backgroundImage =
                    "url(" + e.target.result + ")";
            };

            reader.readAsDataURL(this.files[0]);
        });
    </script>
@endsection
