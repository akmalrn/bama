<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        /* ===============================
           REUSABLE SWEET ALERT CONFIRM
        ================================ */
        function confirmAction(options, callback) {
            Swal.fire({
                title: options.title || "Yakin?",
                text: options.text || "",
                icon: options.icon || "warning",
                showCancelButton: true,
                confirmButtonColor: options.confirmColor || "#3085d6",
                confirmButtonText: options.confirmText || "Ya",
                cancelButtonText: "Batal"
            }).then(result => {
                if (result.isConfirmed) callback();
            });
        }

        /* ===============================
           FLASH MESSAGE
        ================================ */
        @if (session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('login'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('login') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error') || (isset($errors) && $errors->any()))

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: `
                @if (session('error'))
                    {{ session('error') }}
                @else
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                @endif
            `,
                showConfirmButton: false,
                timer: 4000
            });
        @endif


        /* ===============================
           REUSABLE FORM SUBMIT
        ================================ */
        function submitForm(url, method = "POST") {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            form.innerHTML = `
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            ${method !== "POST" ? `<input type="hidden" name="_method" value="${method}">` : ""}
        `;

            document.body.appendChild(form);
            form.submit();
        }


        /* ===============================
           GLOBAL CLICK HANDLER (Delegation)
        ================================ */
        document.addEventListener("click", function(e) {

            const btn = e.target.closest("button, a");
            if (!btn) return;

            /* LOGOUT */
            if (btn.id === "logout-button") {
                e.preventDefault();
                confirmAction({
                    title: "Yakin ingin logout?",
                    text: "Sesi Anda akan berakhir.",
                    icon: "warning"
                }, () => {
                    document.getElementById("logout-form")?.submit();
                });
            }

            /* SOFT DELETE */
            if (btn.classList.contains("btn-soft-delete")) {
                confirmAction({
                    title: "Pindahkan ke Trash?",
                    text: "Data masih bisa dikembalikan nanti.",
                    confirmColor: "#ffc107"
                }, () => submitForm(btn.dataset.url, "DELETE"));
            }

            /* FORCE DELETE */
            if (btn.classList.contains("btn-force-delete")) {
                confirmAction({
                    title: "Hapus Permanen?",
                    text: "Data tidak bisa dikembalikan!",
                    icon: "error",
                    confirmColor: "#dc3545"
                }, () => submitForm(btn.dataset.url, "DELETE"));
            }

            /* RESTORE */
            if (btn.classList.contains("btn-restore")) {
                confirmAction({
                    title: "Pulihkan Data?",
                    text: "Data akan dikembalikan dari trash.",
                    icon: "question",
                    confirmColor: "#198754"
                }, () => submitForm(btn.dataset.url, "GET"));
            }

            /* DELETE BY ID */
            if (btn.classList.contains("btn-delete-id")) {
                confirmAction({
                    title: "Yakin ingin menghapus?",
                    text: "Data akan dihapus permanen!"
                }, () => {
                    document.getElementById("delete-form-" + btn.dataset.id)?.submit();
                });
            }

            if (btn.classList.contains("btn-complete-id")) {
                confirmAction({
                    title: "Selesaikan Maintenance?",
                    text: "Status akan diubah menjadi Completed."
                }, () => {
                    document.getElementById("complete-form-" + btn.dataset.id)?.submit();
                });
            }

            if (btn.classList.contains("btn-approve-id")) {

                confirmAction({
                    title: "Konfirmasi Peminjaman?",
                    text: "Status akan diubah menjadi Active dan aset menjadi Borrowed."
                }, () => {
                    document.getElementById("approve-form-" + btn.dataset.id)?.submit();
                });

            }

            /* IMPERSONATE */
            if (btn.classList.contains("btn-impersonate")) {
                confirmAction({
                    title: "Login sebagai user ini?",
                    text: "Anda akan masuk sebagai user tersebut.",
                    icon: "question",
                    confirmColor: "#0dcaf0"
                }, () => {
                    btn.closest(".impersonate-form")?.submit();
                });
            }

            if (btn.classList.contains("btn-leave-impersonate")) {
                e.preventDefault();

                confirmAction({
                    title: "Keluar dari mode impersonate?",
                    text: "Anda akan kembali ke akun admin.",
                    icon: "question",
                    confirmColor: "#ffc107"
                }, () => {
                    document.getElementById("leave-impersonate-form")?.submit();
                });
            }
            /* RETURN ASSET */
            if (btn.classList.contains("btn-return-id")) {

                confirmAction({
                    title: "Tandai Sebagai Dikembalikan?",
                    text: "Status peminjaman akan menjadi Returned dan aset kembali Available."
                }, () => {
                    document.getElementById("return-form-" + btn.dataset.id)?.submit();
                });

            }

        });

    });
</script>
<script>
    function previewImage(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);

        if (!preview) return;

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;

                // 🔥 pastikan tampil
                preview.style.display = 'block';
                preview.classList.remove('d-none');
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.style.display = 'none';
        }
    }
</script>
<script>
    function previewImageReplace(event, previewId) {
        const input = event.target;
        const preview = document.getElementById(previewId);

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<script>
    function handleButton(id, text) {
        const btn = document.getElementById(id);

        if (btn) {
            btn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    ${text}
                `;
                this.form.submit();
            });
        }
    }

    handleButton('btnSubmit', 'Sedang Menyimpan...');
    handleButton('btnSubmit2', 'Sedang Menyimpan...');
    handleButton('btnUpdate', 'Sedang Memperbarui...');
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<script>
    NProgress.configure({
        showSpinner: true,
        trickleSpeed: 100
    });

    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');

        if (!link) return;

        const href = link.getAttribute('href');

        // Abaikan link yang tidak menyebabkan perpindahan halaman
        if (
            !href ||
            href === '#' ||
            href.startsWith('#') ||
            href.startsWith('javascript:') ||
            href.startsWith('mailto:') ||
            href.startsWith('tel:') ||
            href.startsWith('https://wa.me') ||
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.hasAttribute('data-nprogress-ignore')
        ) {
            return;
        }

        const tujuan = new URL(link.href, window.location.href);
        const halamanSekarang = new URL(window.location.href);

        // Abaikan link eksternal
        if (tujuan.origin !== halamanSekarang.origin) {
            return;
        }

        // Abaikan jika masih berada di halaman yang sama
        if (
            tujuan.pathname === halamanSekarang.pathname &&
            tujuan.search === halamanSekarang.search
        ) {
            return;
        }

        NProgress.start();
    });

    // Berhenti setelah halaman baru selesai dimuat
    window.addEventListener('load', function() {
        NProgress.done();
    });

    // Mencegah loading tersangkut ketika menekan tombol Back/Forward
    window.addEventListener('pageshow', function() {
        NProgress.done();
    });
</script>
<!-- jQuery library js -->
<script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
<!-- Bootstrap js -->
<script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
<!-- Apex Chart js -->
<script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
<!-- Data Table js -->
<script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
<!-- Iconify Font js -->
<script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
<!-- jQuery UI js -->
<script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
<!-- Vector Map js -->
<script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
<script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- Popup js -->
<script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
<!-- Slick Slider js -->
<script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
<!-- prism js -->
<script src="{{ asset('assets/js/lib/prism.js') }}"></script>
<!-- file upload js -->
<script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
<!-- audioplayer -->
<script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>

<!-- main js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

<?php echo isset($script) ? $script : ''; ?>

<script>
    (function() {
        let resizeTimer = null;

        function adjustVisibleDataTables() {
            document.querySelectorAll('table.dataTable').forEach(function(table) {
                table.style.width = '100%';
                table.style.minWidth = '100%';

                const layoutCell = table.closest('.dt-layout-cell');
                const layoutRow = table.closest('.dt-layout-row');
                const container = table.closest('.dt-container');

                [layoutCell, layoutRow, container].forEach(function(element) {
                    if (!element) return;
                    element.style.width = '100%';
                    element.style.maxWidth = '100%';
                });
            });

            if (!window.DataTable || typeof window.DataTable.tables !== 'function') {
                return;
            }

            try {
                const api = window.DataTable.tables({ visible: true, api: true });
                if (api && api.columns && typeof api.columns.adjust === 'function') {
                    api.columns.adjust().draw(false);
                }
            } catch (error) {
                // A page without an initialized DataTable does not need adjustment.
            }
        }

        document.addEventListener('shown.bs.tab', function() {
            window.requestAnimationFrame(function() {
                window.requestAnimationFrame(adjustVisibleDataTables);
            });
        });

        window.addEventListener('resize', function() {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(adjustVisibleDataTables, 120);
        });

        window.addEventListener('load', adjustVisibleDataTables);
        document.addEventListener('DOMContentLoaded', adjustVisibleDataTables, { once: true });
    })();
</script>
