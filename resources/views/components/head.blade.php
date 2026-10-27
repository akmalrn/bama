<head>
    <script>
        (function() {
            const root = document.documentElement;
            const storageKey = 'theme';
            let storedTheme = null;

            try {
                storedTheme = window.localStorage.getItem(storageKey);
            } catch (error) {
                storedTheme = null;
            }

            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const initialTheme = storedTheme === 'dark' || storedTheme === 'light' ? storedTheme : systemTheme;

            root.classList.add('theme-initializing');
            root.setAttribute('data-theme', initialTheme);
            root.style.colorScheme = initialTheme;

            document.addEventListener('DOMContentLoaded', function() {
                window.requestAnimationFrame(function() {
                    window.requestAnimationFrame(function() {
                        root.classList.remove('theme-initializing');
                    });
                });
            }, { once: true });
        })();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1800">
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> --}}
    <title>{{ $configuration->title_dashboard ?? '' }}</title>
    <link rel="icon" type="image/png" href="{{ asset($configuration->favicon_dashboard ?? '') }}" sizes="16x16">
    <!-- remix icon font css  -->
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <!-- BootStrap css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/bootstrap.min.css') }}">
    <!-- Apex Chart css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
    <!-- Data Table css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}">
    <!-- Text Editor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor-katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.quill.snow.css') }}">
    <!-- Date picker css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">
    <!-- Calendar css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}">
    <!-- Vector Map css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
    <!-- Popup css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}">
    <!-- Slick Slider css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}">
    <!-- prism css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/prism.css') }}">
    <!-- file upload css -->
    <link rel="stylesheet" href="{{ asset('assets/css/lib/file-upload.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/lib/audioplayer.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-theme.css') }}">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <style>
        .swal2-title {
            font-size: 20px !important;
        }

        #nprogress .spinner {
            bottom: 20px !important;
            right: 20px !important;
            left: auto !important;
            transform: translate(50%, -50%) !important;
        }

        .cke_notification {
            display: none;
        }
    </style>
</head>
