@extends('layout.layout')

@php
    $title = 'User';
    $subTitle = 'Edit User';
@endphp

@section('content')
    <form id="formUser" action="{{ route('karyawans.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row gy-4">

            <div class="col-lg-12">
                <div class="card">

                    <div class="card-header">
                        <h6 class="card-title mb-0">Edit Karyawan</h6>
                    </div>

                    <div class="card-body">
                        <div class="row gy-3">

                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir"
                                    value="{{ old('tempat_lahir', $user->tempat_lahir) }}" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    value="{{ old('tanggal_lahir', \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') ?? '') }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Pekerjaan</label>
                                <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $user->pekerjaan) }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Provinsi</label>
                                <select id="provinsi_id" name="provinsi_id" class="form-select" required>

                                    <option value="">-- Pilih Provinsi --</option>

                                    @foreach ($provinsi as $item)
                                        <option value="{{ $item->id }}"
                                            {{ $user->provinsi_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kota / Kabupaten</label>
                                <select id="kabupaten_id" name="kabupaten_id" class="form-select" required>
                                    <option value="{{ $user->kabupaten_id }}">
                                        {{ $user->kabupaten->nama ?? '-- Pilih Kota / Kabupaten --' }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Kecamatan</label>
                                <select id="kecamatan_id" name="kecamatan_id" class="form-select" required>
                                    <option value="{{ $user->kecamatan_id }}">
                                        {{ $user->kecamatan->nama ?? '-- Pilih Kecamatan --' }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Desa / Kelurahan</label>
                                <select id="desa_id" name="desa_id" class="form-select" required>
                                    <option value="{{ $user->desa_id }}">
                                        {{ $user->desa->nama ?? '-- Pilih Desa --' }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat_rumah" class="form-control" required>{{ old('alamat_rumah', $user->alamat_rumah) }}</textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">RT</label>
                                <input type="text" name="rt" value="{{ old('rt', $user->rt) }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">RW</label>
                                <input type="text" name="rw" value="{{ old('rw', $user->rw) }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">NIK</label>
                                <input type="text" name="nik" value="{{ old('nik', $user->nik) }}" minlength="15" maxlength="17"
                                    pattern="[0-9]{15,17}" inputmode="numeric"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload KTP</label>
                                <input type="file" name="foto_ktp" class="form-control">

                                @if ($user->foto_ktp)
                                    <a href="{{ asset($user->foto_ktp) }}" target="_blank"
                                        class="text-primary mt-2 d-block">
                                        Lihat KTP
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Upload NPWP</label>
                                <input type="file" name="npwp" class="form-control">

                                @if ($user->npwp)
                                    <a href="{{ asset($user->npwp) }}" target="_blank" class="text-primary mt-2 d-block">
                                        Lihat NPWP
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">No Handphone</label>
                                <input type="text" name="handphone" value="{{ old('handphone', $user->handphone) }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Status</label>

                                <select name="status" class="form-select" required>

                                    <option value="Aktif" {{ $user->status == 'Aktif' ? 'selected' : '' }}>Aktif
                                    </option>

                                    <option value="Mengundurkan Diri"
                                        {{ $user->status == 'Mengundurkan Diri' ? 'selected' : '' }}>
                                        Mengundurkan Diri
                                    </option>

                                    <option value="Tidak Aktif" {{ $user->status == 'Tidak Aktif' ? 'selected' : '' }}>
                                        Tidak Aktif
                                    </option>

                                </select>

                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Password</label>

                                <div class="input-group">

                                    <input type="password" name="password" id="password" class="form-control">

                                    <span class="input-group-text" onclick="togglePassword('password', this)"
                                        style="cursor:pointer;">
                                        <i class="fa fa-eye"></i>
                                    </span>

                                </div>

                                <small class="text-secondary-light d-block mt-2">Kosongkan jika tidak ingin mengganti password</small>

                            </div>

                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-12">
                <div class="card">

                    <div class="card-body text-end">

                        <a href="{{ route('users.index') }}" class="btn btn-light me-2 px-32 py-12 radius-8">
                            Kembali
                        </a>

                        <button id="btnSubmit" type="submit" class="btn btn-primary px-32 py-12 radius-8">
                            <span id="textBtn">Update Karyawan</span>
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
    <script>
        const urlKabupaten = "{{ route('get.kabupaten', ':id') }}";
        const urlKecamatan = "{{ route('get.kecamatan', ':id') }}";
        const urlDesa = "{{ route('get.desa', ':id') }}";
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const form = document.getElementById("formUser");
            const btnSubmit = document.getElementById("btnSubmit");
            const textBtn = document.getElementById("textBtn");

            /* =========================
               VALIDASI FORM
            ========================= */

            form.addEventListener("submit", function(e) {

                const requiredFields = form.querySelectorAll("[required]");
                let valid = true;

                requiredFields.forEach(field => {

                    if (!field.value.trim()) {
                        field.classList.add("is-invalid");
                        valid = false;
                    } else {
                        field.classList.remove("is-invalid");
                    }

                });

                if (!valid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "warning",
                        title: "Form belum lengkap",
                        text: "Silakan isi semua field yang wajib diisi."
                    });
                    return;
                }

                btnSubmit.disabled = true;
                textBtn.innerHTML = "Menyimpan...";
            });


            /* =========================
               CASCADE WILAYAH
            ========================= */

            const provinsi = document.getElementById("provinsi_id");
            const kabupaten = document.getElementById("kabupaten_id");
            const kecamatan = document.getElementById("kecamatan_id");
            const desa = document.getElementById("desa_id");

            function reset(select, text) {
                select.innerHTML = `<option value="">-- ${text} --</option>`;
            }

            function loading(select) {
                select.innerHTML = `<option>Loading...</option>`;
            }

            function populate(select, data, text) {

                let option = `<option value="">-- ${text} --</option>`;

                data.forEach(item => {
                    option += `<option value="${item.id}">${item.nama}</option>`;
                });

                select.innerHTML = option;
            }


            /* PROVINSI -> KABUPATEN */

            provinsi.addEventListener("change", async function() {

                const id = this.value;

                reset(kecamatan, "Pilih Kecamatan");
                reset(desa, "Pilih Desa");

                if (!id) {
                    reset(kabupaten, "Pilih Kabupaten");
                    return;
                }

                loading(kabupaten);

                const url = urlKabupaten.replace(':id', id);
                const res = await fetch(url);
                const data = await res.json();

                populate(kabupaten, data, "Pilih Kabupaten");

            });


            /* KABUPATEN -> KECAMATAN */

            kabupaten.addEventListener("change", async function() {

                const id = this.value;

                reset(desa, "Pilih Desa");

                if (!id) {
                    reset(kecamatan, "Pilih Kecamatan");
                    return;
                }

                loading(kecamatan);

                const url = urlKecamatan.replace(':id', id);
                const res = await fetch(url);
                const data = await res.json();

                populate(kecamatan, data, "Pilih Kecamatan");

            });


            /* KECAMATAN -> DESA */

            kecamatan.addEventListener("change", async function() {

                const id = this.value;

                if (!id) {
                    reset(desa, "Pilih Desa");
                    return;
                }

                loading(desa);

                const url = urlDesa.replace(':id', id);
                const res = await fetch(url);
                const data = await res.json();

                populate(desa, data, "Pilih Desa");

            });

        });
    </script>
@endsection
