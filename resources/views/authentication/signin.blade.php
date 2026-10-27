<!DOCTYPE html>
<html lang="id" data-theme="light">

<link rel="shortcut icon" href="{{ asset($configuration->favicon_login ?? '') }}" type="image/x-icon">
<title>{{ $configuration->title_login ?? 'WAN Teknologi Internasional' }}</title>

<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

<style>
    :root {
        --wan-navy: #071b3b;
        --wan-navy-dark: #031128;
        --wan-blue: #1769e0;
        --wan-blue-dark: #0c58cc;
        --wan-cyan: #14c6e8;
        --wan-magenta: #d82b7d;
        --wan-text: #101828;
        --wan-muted: #667085;
        --wan-border: #d0d5dd;
        --wan-danger: #dc3545;
    }

    * {
        box-sizing: border-box;
    }

    html,
    body {
        width: 100%;
        min-height: 100%;
        margin: 0;
    }

    body {
        overflow-x: hidden;
        background: var(--wan-navy-dark);
        color: var(--wan-text);
    }

    .wan-login-page {
        position: relative;
        isolation: isolate;
        display: flex;

        width: 100%;
        height: 100vh;
        height: 100dvh;
        overflow: hidden;

        background-color: var(--wan-navy-dark);
        background-image:
            linear-gradient(90deg,
                rgba(3, 17, 40, .96) 0%,
                rgba(7, 27, 59, .82) 36%,
                rgba(7, 27, 59, .54) 61%,
                rgba(3, 17, 40, .86) 100%),
            var(--wan-login-background);

        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    .wan-login-page::before {
        position: absolute;
        z-index: -1;
        inset: 0;
        background:
            radial-gradient(circle at 19% 17%, rgba(20, 198, 232, .11), transparent 28%),
            radial-gradient(circle at 93% 86%, rgba(23, 105, 224, .14), transparent 30%);
        content: "";
        pointer-events: none;
    }

    .wan-login-shell {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(430px, 520px);        
        width: min(100%, 1600px);
        min-height: 100svh;
        margin: 0 auto;
        padding: clamp(38px, 5vw, 80px);
        gap: clamp(48px, 7vw, 110px);
    }

    .wan-brand-panel {
        position: relative;
        display: flex;
        min-height: 650px;
        flex-direction: column;
        justify-content: center;
        color: #ffffff;
    }

    .wan-brand-logo {
        position: absolute;
        top: 0;
        left: 0;
        display: inline-flex;
        align-items: center;
        max-width: 260px;
        padding: 10px 16px;
        border: 1px solid rgba(255, 255, 255, .58);
        border-radius: 14px;
        background: rgba(255, 255, 255, .94);
        box-shadow: 0 14px 35px rgba(0, 10, 36, .16);
        text-decoration: none;
    }

    .wan-brand-logo img {
        display: block;
        width: auto;
        max-width: 230px;
        height: 48px;
        object-fit: contain;
        object-position: left center;
    }

    .wan-brand-content {
        max-width: 760px;
    }

    .wan-brand-title {
        max-width: 750px;
        margin: 0;
        color: #ffffff;
        font-size: clamp(46px, 4vw, 60px)!important;
        font-weight: 600;
        letter-spacing: -.035em;
        line-height: 1.08;
        text-transform: uppercase;
        text-wrap: balance;
    }

    .wan-brand-accent {
        width: 165px;
        height: 6px;
        margin: 32px 0 34px;
        overflow: hidden;
        border-radius: 999px;
        background: linear-gradient(90deg,
                var(--wan-cyan) 0%,
                var(--wan-blue) 52%,
                var(--wan-magenta) 100%);
    }

    .wan-welcome-title {
        margin: 0 0 10px;
        color: #ffffff;
        font-size: clamp(22px, 2vw, 30px);
        font-weight: 700;
        line-height: 1.3;
    }

    .wan-welcome-text {
        max-width: 620px;
        margin: 0;
        color: rgba(255, 255, 255, .84);
        font-size: clamp(16px, 1.4vw, 20px);
        line-height: 1.7;
    }

    .wan-login-panel {
        width: 100%;
    }

    .wan-login-card {
        width: 100%;
        padding: clamp(34px, 4vw, 54px);
        border: 1px solid rgba(255, 255, 255, .68);
        border-radius: 26px;
        background: rgba(255, 255, 255, .97);
        box-shadow: 0 30px 80px rgba(0, 10, 36, .32);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    .wan-mobile-brand {
        display: none;
        margin-bottom: 30px;
    }

    .wan-mobile-brand img {
        display: block;
        width: auto;
        max-width: 180px;
        height: 44px;
        margin-bottom: 20px;
        object-fit: contain;
        object-position: left center;
    }

    .wan-mobile-brand h1 {
        margin: 0 0 8px;
        color: var(--wan-navy);
        font-size: 26px;
        font-weight: 800;
        line-height: 1.2;
    }

    .wan-mobile-brand p {
        margin: 0;
        color: var(--wan-muted);
        font-size: 15px;
        line-height: 1.6;
    }

    .wan-form-group {
        margin-bottom: 26px;
    }

    .wan-form-label {
        display: block;
        margin-bottom: 10px;
        color: #344054;
        font-size: 15px;
        font-weight: 600;
    }

    .wan-input-wrap {
        position: relative;
    }

    .wan-input-icon {
        position: absolute;
        z-index: 2;
        top: 50%;
        left: 18px;
        display: inline-flex;
        color: #475467;
        font-size: 24px;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .wan-form-control {
        display: block;
        width: 100%;
        height: 64px;
        padding: 0 56px 0 58px;
        border: 1.5px solid var(--wan-border);
        border-radius: 12px;
        outline: none;
        background: #ffffff;
        color: var(--wan-text);
        font-size: 16px;
        line-height: 1.4;
        transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
    }

    .wan-form-control::placeholder {
        color: #98a2b3;
    }

    .wan-form-control:hover {
        border-color: #98a2b3;
    }

    .wan-form-control:focus {
        border-color: var(--wan-blue);
        background: #ffffff;
        box-shadow: 0 0 0 4px rgba(23, 105, 224, .12);
    }

    .wan-form-control.is-invalid {
        border-color: var(--wan-danger);
    }

    .wan-password-toggle {
        position: absolute;
        z-index: 3;
        top: 50%;
        right: 12px;
        display: inline-flex;
        width: 42px;
        height: 42px;
        align-items: center;
        justify-content: center;
        padding: 0;
        border: 0;
        border-radius: 9px;
        background: transparent;
        color: #475467;
        font-size: 23px;
        transform: translateY(-50%);
        cursor: pointer;
        transition: color .2s ease, background-color .2s ease;
    }

    .wan-password-toggle:hover {
        background: #f2f4f7;
        color: var(--wan-blue);
    }

    .wan-invalid-feedback {
        display: block;
        margin-top: 7px;
        color: var(--wan-danger);
        font-size: 13px;
    }

    .wan-form-options {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 4px 0 30px;
        gap: 18px;
    }

    .wan-remember {
        display: inline-flex;
        align-items: center;
        margin: 0;
        color: #344054;
        font-size: 14px;
        cursor: pointer;
        gap: 9px;
    }

    .wan-remember input {
        width: 18px;
        height: 18px;
        margin: 0;
        accent-color: var(--wan-blue);
        cursor: pointer;
    }

    .wan-forgot-link {
        color: var(--wan-blue);
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .wan-forgot-link:hover {
        color: var(--wan-blue-dark);
        text-decoration: underline;
    }

    .wan-login-button {
        display: inline-flex;
        width: 100%;
        min-height: 62px;
        align-items: center;
        justify-content: center;
        padding: 15px 24px;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(100deg, var(--wan-blue-dark), var(--wan-blue));
        box-shadow: 0 14px 30px rgba(23, 105, 224, .24);
        color: #ffffff;
        font-size: 16px;
        font-weight: 700;
        line-height: 1.4;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease, filter .2s ease;
    }

    .wan-login-button:hover:not(:disabled) {
        box-shadow: 0 18px 36px rgba(23, 105, 224, .32);
        filter: brightness(1.05);
        transform: translateY(-1px);
    }

    .wan-login-button:focus-visible {
        outline: 3px solid rgba(20, 198, 232, .45);
        outline-offset: 3px;
    }

    .wan-login-button:disabled {
        cursor: wait;
        opacity: .78;
    }

    .wan-login-copyright {
        margin: 58px 0 0;
        color: #667085;
        font-size: 13px;
        line-height: 1.6;
        text-align: center;
    }

    .swal2-title {
        font-size: 20px !important;
    }

    @media (max-width: 1199.98px) {
        .wan-login-shell {
            grid-template-columns: minmax(0, 1fr) minmax(400px, 470px);
            padding: 44px;
            gap: 50px;
        }

        .wan-brand-title {
            font-size: clamp(42px, 5vw, 62px);
        }
    }

    @media (max-width: 991.98px) {
        .wan-login-page {
            background-image:
                linear-gradient(rgba(3, 17, 40, .78), rgba(3, 17, 40, .86)),
                var(--wan-login-background);
        }

        .wan-login-shell {
            display: flex;
            min-height: 100svh;
            align-items: center;
            justify-content: center;
            padding: 30px 20px;
        }

        .wan-brand-panel {
            display: none;
        }

        .wan-login-panel {
            max-width: 520px;
        }

        .wan-mobile-brand {
            display: block;
        }
    }

    @media (max-width: 575.98px) {
        .wan-login-shell {
            padding: 18px 12px;
        }

        .wan-login-card {
            padding: 28px 20px;
            border-radius: 20px;
        }

        .wan-form-control {
            height: 58px;
            padding-left: 52px;
            font-size: 15px;
        }

        .wan-form-options {
            align-items: flex-start;
            flex-direction: column;
            margin-bottom: 26px;
            gap: 12px;
        }

        .wan-login-copyright {
            margin-top: 36px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            scroll-behavior: auto !important;
            transition: none !important;
        }
    }
</style>

<x-head />

<body>
    <main class="wan-login-page" style="--wan-login-background: url('{{ asset($configuration->image_login ?? '') }}');">
        <div class="wan-login-shell">
            <section class="wan-brand-panel" aria-labelledby="wan-brand-title">
                <a href="{{ url('/') }}" class="wan-brand-logo" aria-label="Halaman utama WAN Teknologi">
                    <img src="{{ asset($configuration->logo_login ?? '') }}"
                        alt="{{ $configuration->title_login ?? 'WAN Teknologi Internasional' }}">
                </a>

                <div class="wan-brand-content">
                    <h1 class="wan-brand-title" id="wan-brand-title">
                        {{ $configuration->title_login ?? 'WAN Teknologi Internasional' }}
                    </h1>

                    <div class="wan-brand-accent" aria-hidden="true"></div>

                    <p class="wan-welcome-text">
                        {{ $configuration->name_login ?? 'Masuk untuk mengakses dashboard perusahaan.' }}
                    </p>
                </div>
            </section>

            <section class="wan-login-panel" aria-label="Form login">
                <div class="wan-login-card">
                    <div class="wan-mobile-brand">
                        <img src="{{ asset($configuration->logo_login ?? '') }}"
                            alt="{{ $configuration->title_login ?? 'WAN Teknologi Internasional' }}">
                        <p>{{ $configuration->name_login ?? 'Masuk untuk mengakses dashboard perusahaan.' }}</p>
                    </div>

                    <form action="{{ route('postLogin') }}" method="POST" id="login-form">
                        @csrf

                        <div class="wan-form-group">
                            <label class="wan-form-label" for="email">Email</label>
                            <div class="wan-input-wrap">
                                <span class="wan-input-icon" aria-hidden="true">
                                    <iconify-icon icon="solar:letter-linear"></iconify-icon>
                                </span>
                                <input type="email" id="email" name="email"
                                    class="wan-form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}" placeholder="nama@perusahaan.com" autocomplete="email"
                                    inputmode="email" required autofocus>
                            </div>
                            @error('email')
                                <span class="wan-invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="wan-form-group">
                            <label class="wan-form-label" for="password">Password</label>
                            <div class="wan-input-wrap">
                                <span class="wan-input-icon" aria-hidden="true">
                                    <iconify-icon icon="solar:lock-password-linear"></iconify-icon>
                                </span>
                                <input type="password" id="password" name="password"
                                    class="wan-form-control @error('password') is-invalid @enderror"
                                    placeholder="Masukkan password" autocomplete="current-password" required>
                                <button type="button" class="wan-password-toggle" id="password-toggle"
                                    aria-label="Tampilkan password" aria-pressed="false">
                                    <iconify-icon icon="solar:eye-linear" id="password-toggle-icon"></iconify-icon>
                                </button>
                            </div>
                            @error('password')
                                <span class="wan-invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="wan-form-options">
                            <label class="wan-remember" for="remember">
                                <input type="checkbox" id="remember" name="remember" value="1"
                                    @checked(old('remember'))>
                                <span>Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="wan-forgot-link">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" id="SignIn" class="wan-login-button">
                            <span class="wan-button-label">Masuk</span>
                        </button>
                    </form>

                    <p class="wan-login-copyright">
                        {{ $configuration->copyright ?? '© ' . date('Y') . ' WAN Teknologi. All Rights Reserved.' }}
                    </p>
                </div>
            </section>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');
            const submitButton = document.getElementById('SignIn');
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');
            const passwordToggleIcon = document.getElementById('password-toggle-icon');

            passwordToggle?.addEventListener('click', function() {
                const showPassword = passwordInput.type === 'password';

                passwordInput.type = showPassword ? 'text' : 'password';
                passwordToggle.setAttribute('aria-pressed', String(showPassword));
                passwordToggle.setAttribute(
                    'aria-label',
                    showPassword ? 'Sembunyikan password' : 'Tampilkan password'
                );
                passwordToggleIcon.setAttribute(
                    'icon',
                    showPassword ? 'solar:eye-closed-linear' : 'solar:eye-linear'
                );
            });

            loginForm?.addEventListener('submit', function(event) {
                if (!loginForm.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    loginForm.classList.add('was-validated');
                    return;
                }

                submitButton.disabled = true;
                submitButton.setAttribute('aria-busy', 'true');
                submitButton.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" aria-hidden="true"></span>
                    <span>Memproses...</span>
                `;
            });
        });
    </script>

    @if (session('error'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Login gagal',
                text: @json(session('error')),
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        </script>
    @endif
</body>

</html>
