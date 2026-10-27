<div class="navbar-header">
    <div class="row align-items-center justify-content-between">
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-4">
                <button type="button" class="sidebar-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon text-2xl non-active"></iconify-icon>
                    <iconify-icon icon="iconoir:arrow-right" class="icon text-2xl active"></iconify-icon>
                </button>
                <button type="button" class="sidebar-mobile-toggle">
                    <iconify-icon icon="heroicons:bars-3-solid" class="icon"></iconify-icon>
                </button>
                {{-- <form class="navbar-search">
                    <input type="text" name="search" placeholder="Search">
                    <iconify-icon icon="ion:search-outline" class="icon"></iconify-icon>
                </form> --}}
            </div>
        </div>
        <div class="col-auto">
            <div class="d-flex flex-wrap align-items-center gap-3">

                <button type="button" data-theme-toggle aria-label="Ganti tema" aria-pressed="false"
                    class="w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"></button>
                {{-- @can('GOD-To_aDmIn')
                    <div class="dropdown d-none d-sm-inline-block">
                        <button
                            class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                            type="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('assets/images/lang-flag.png') }}" alt="image"
                                class="w-24 h-24 object-fit-cover rounded-circle">
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-sm">
                            <div
                                class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Choose Your Language</h6>
                                </div>
                            </div>

                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-8">
                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="english">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag1.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">English</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="english">
                                </div>

                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="japan">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag2.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">Japan</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="japan">
                                </div>

                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="france">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag3.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">France</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="france">
                                </div>

                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="germany">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag4.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">Germany</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="germany">
                                </div>

                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="korea">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag5.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">South Korea</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="korea">
                                </div>

                                <div class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="bangladesh">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag6.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">Bangladesh</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="bangladesh">
                                </div>

                                <div
                                    class="form-check style-check d-flex align-items-center justify-content-between mb-16">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="india">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag7.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">India</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="india">
                                </div>
                                <div class="form-check style-check d-flex align-items-center justify-content-between">
                                    <label class="form-check-label line-height-1 fw-medium text-secondary-light"
                                        for="canada">
                                        <span
                                            class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                            <img src="{{ asset('assets/images/flags/flag8.png') }}" alt=""
                                                class="w-36-px h-36-px bg-success-subtle text-success-main rounded-circle flex-shrink-0">
                                            <span class="text-md fw-semibold mb-0">Canada</span>
                                        </span>
                                    </label>
                                    <input class="form-check-input" type="radio" name="crypto" id="canada">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button
                            class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                            type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="mage:email" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div
                                class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Message</h6>
                                </div>
                                <span
                                    class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                            </div>

                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                            <img src="{{ asset('assets/images/notification/profile-3.png') }}"
                                                alt="">
                                            <span
                                                class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                        <span
                                            class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                    </div>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                            <img src="{{ asset('assets/images/notification/profile-4.png') }}"
                                                alt="">
                                            <span
                                                class="w-8-px h-8-px  bg-neutral-300 rounded-circle position-absolute end-0 bottom-0"></span>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                        <span
                                            class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">2</span>
                                    </div>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                            <img src="{{ asset('assets/images/notification/profile-5.png') }}"
                                                alt="">
                                            <span
                                                class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                        <span
                                            class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
                                    </div>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                            <img src="{{ asset('assets/images/notification/profile-6.png') }}"
                                                alt="">
                                            <span
                                                class="w-8-px h-8-px bg-neutral-300 rounded-circle position-absolute end-0 bottom-0"></span>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                        <span
                                            class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-neutral-400 rounded-circle">0</span>
                                    </div>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span class="w-40-px h-40-px rounded-circle flex-shrink-0 position-relative">
                                            <img src="{{ asset('assets/images/notification/profile-7.png') }}"
                                                alt="">
                                            <span
                                                class="w-8-px h-8-px bg-success-main rounded-circle position-absolute end-0 bottom-0"></span>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Kathryn Murphy</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-100-px">hey! there i’m...
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column align-items-end">
                                        <span class="text-sm text-secondary-light flex-shrink-0">12:30 PM</span>
                                        <span
                                            class="mt-4 text-xs text-base w-16-px h-16-px d-flex justify-content-center align-items-center bg-warning-main rounded-circle">8</span>
                                    </div>
                                </a>

                            </div>
                            <div class="text-center py-12 px-16">
                                <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All
                                    Message</a>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button
                            class="has-indicator w-40-px h-40-px bg-neutral-200 rounded-circle d-flex justify-content-center align-items-center"
                            type="button" data-bs-toggle="dropdown">
                            <iconify-icon icon="iconoir:bell" class="text-primary-light text-xl"></iconify-icon>
                        </button>
                        <div class="dropdown-menu to-top dropdown-menu-lg p-0">
                            <div
                                class="m-16 py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2">
                                <div>
                                    <h6 class="text-lg text-primary-light fw-semibold mb-0">Notifications</h6>
                                </div>
                                <span
                                    class="text-primary-600 fw-semibold text-lg w-40-px h-40-px rounded-circle bg-base d-flex justify-content-center align-items-center">05</span>
                            </div>

                            <div class="max-h-400-px overflow-y-auto scroll-sm pe-4">
                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span
                                            class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                            <iconify-icon icon="bitcoin-icons:verify-outline"
                                                class="icon text-xxl"></iconify-icon>
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Congratulations</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">Your profile has
                                                been Verified. Your profile has been Verified</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span
                                            class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                            <img src="{{ asset('assets/images/notification/profile-1.png') }}"
                                                alt="">
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Ronald Richards</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">You can stitch
                                                between artboards</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span
                                            class="w-44-px h-44-px bg-info-subtle text-info-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                            AM
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Arlene McCoy</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">Invite you to
                                                prototyping</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between bg-neutral-50">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span
                                            class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                            <img src="{{ asset('assets/images/notification/profile-2.png') }}"
                                                alt="">
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Annette Black</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">Invite you to
                                                prototyping</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                </a>

                                <a href="javascript:void(0)"
                                    class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">
                                    <div
                                        class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
                                        <span
                                            class="w-44-px h-44-px bg-info-subtle text-info-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
                                            DR
                                        </span>
                                        <div>
                                            <h6 class="text-md fw-semibold mb-4">Darlene Robertson</h6>
                                            <p class="mb-0 text-sm text-secondary-light text-w-200-px">Invite you to
                                                prototyping</p>
                                        </div>
                                    </div>
                                    <span class="text-sm text-secondary-light flex-shrink-0">23 Mins ago</span>
                                </a>
                            </div>

                            <div class="text-center py-12 px-16">
                                <a href="javascript:void(0)" class="text-primary-600 fw-semibold text-md">See All
                                    Notification</a>
                            </div>

                        </div>
                    </div><!-- Notification dropdown end -->
                @endcan --}}
                @php
                    $user = auth()->user();
                @endphp

                <div class="dropdown position-relative" id="profileDropdownWrapper">
                    <button class="d-flex justify-content-center align-items-center rounded-circle border-0 p-0 bg-transparent cursor-pointer" 
                        type="button" 
                        id="headerProfileDropdown" 
                        aria-expanded="false"
                        aria-haspopup="true"
                        title="Profil Pengguna"
                        style="cursor: pointer; outline: none; border-radius: 50%;">

                        @if ($user->foto)
                            <img src="{{ asset($user->foto) }}"
                                class="w-40-px h-40-px object-fit-cover rounded-circle" alt="{{ $user->name }}" style="pointer-events: none;">
                        @else
                            <div class="w-40-px h-40-px rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold"
                                style="background:#FF7800; pointer-events: none;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </button>

                    <div class="dropdown-menu dropdown-menu-end to-top dropdown-menu-sm" 
                        id="headerProfileMenu"
                        aria-labelledby="headerProfileDropdown"
                        role="menu">
                        <div class="py-12 px-16 radius-8 bg-primary-50 mb-16 d-flex align-items-center justify-content-between gap-2 user-header-info">
                            <div>
                                <h6 class="text-lg text-primary-light fw-semibold mb-2">
                                    {{ $user->name }}
                                </h6>

                                <span class="text-secondary-light fw-medium text-sm">
                                    {{ $user->email }}
                                </span>
                            </div>

                            <button type="button" class="hover-text-danger border-0 bg-transparent p-0" id="closeProfileDropdown" aria-label="Tutup">
                                <iconify-icon icon="radix-icons:cross-1" class="icon text-xl"></iconify-icon>
                            </button>
                        </div>

                        <ul class="to-top-list list-unstyled mb-0">
                            <li>
                                <a class="dropdown-item px-0 py-8 hover-bg-transparent d-flex align-items-center gap-3 user-dropdown-link"
                                    href="{{ route('dashboard-admin.profile') }}">
                                    <iconify-icon icon="solar:user-linear" class="icon text-xl"></iconify-icon>
                                    Profil Saya
                                </a>
                            </li>

                            @php
                                $isImpersonating = app('impersonate')->isImpersonating();
                            @endphp

                            <li>
                                <a href="#" id="{{ $isImpersonating ? '' : 'logout-button' }}"
                                    class="dropdown-item px-0 py-8 hover-bg-transparent d-flex align-items-center gap-3 user-dropdown-link user-dropdown-logout {{ $isImpersonating ? 'btn-leave-impersonate' : '' }}">
                                    <iconify-icon icon="lucide:power" class="icon text-xl"></iconify-icon>
                                    {{ $isImpersonating ? 'Leave' : 'Log Out' }}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <style>
                    #profileDropdownWrapper {
                        position: relative !important;
                    }

                    #headerProfileMenu {
                        display: none !important;
                        position: absolute !important;
                        right: 0 !important;
                        left: auto !important;
                        top: calc(100% + 12px) !important;
                        min-width: 270px;
                        border-radius: 16px;
                        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.16);
                        border: 1px solid rgba(0, 0, 0, 0.08);
                        padding: 16px;
                        z-index: 9999 !important;
                        background-color: var(--white, #ffffff) !important;
                    }

                    #headerProfileMenu.show {
                        display: block !important;
                        visibility: visible !important;
                        opacity: 1 !important;
                        pointer-events: auto !important;
                    }

                    .user-dropdown-link {
                        color: var(--text-primary-light, #1e293b) !important;
                        font-weight: 500;
                        transition: all 0.2s ease;
                    }
                    .user-dropdown-link:hover {
                        color: #2563eb !important;
                        padding-left: 4px !important;
                    }
                    .user-dropdown-logout:hover {
                        color: #dc2626 !important;
                    }
                    html[data-theme="dark"] #headerProfileMenu {
                        background-color: var(--admin-surface, #1e293b) !important;
                        border-color: var(--admin-border, rgba(148, 163, 184, 0.24)) !important;
                        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45) !important;
                    }
                    html[data-theme="dark"] #headerProfileMenu .user-header-info {
                        background-color: rgba(37, 99, 235, 0.15) !important;
                    }
                    html[data-theme="dark"] .user-dropdown-link {
                        color: #f8fafc !important;
                    }
                </style>

                <script>
                    (function() {
                        function initProfileDropdown() {
                            const btn = document.getElementById('headerProfileDropdown');
                            const menu = document.getElementById('headerProfileMenu');
                            const closeBtn = document.getElementById('closeProfileDropdown');
                            const wrapper = document.getElementById('profileDropdownWrapper');

                            if (!btn || !menu) return;

                            function toggleProfileMenu(forceOpen) {
                                const isOpen = menu.classList.contains('show');
                                const willOpen = (typeof forceOpen === 'boolean') ? forceOpen : !isOpen;

                                if (willOpen) {
                                    menu.classList.add('show');
                                    btn.classList.add('show');
                                    btn.setAttribute('aria-expanded', 'true');
                                } else {
                                    menu.classList.remove('show');
                                    btn.classList.remove('show');
                                    btn.setAttribute('aria-expanded', 'false');
                                }
                            }

                            // Toggle klik pada tombol avatar profile
                            btn.addEventListener('click', function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                toggleProfileMenu();
                            });

                            // Tombol close silang di dalam dropdown
                            if (closeBtn) {
                                closeBtn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    toggleProfileMenu(false);
                                });
                            }

                            // Klik di luar dropdown untuk menutup
                            document.addEventListener('click', function(e) {
                                if (wrapper && !wrapper.contains(e.target)) {
                                    toggleProfileMenu(false);
                                }
                            });

                            // Tombol Escape untuk menutup
                            document.addEventListener('keydown', function(e) {
                                if (e.key === 'Escape') {
                                    toggleProfileMenu(false);
                                }
                            });
                        }

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', initProfileDropdown, { once: true });
                        } else {
                            initProfileDropdown();
                        }
                    })();
                </script>

                @if (app('impersonate')->isImpersonating())
                    <form id="leave-impersonate-form" action="{{ route('dashboard-admin.impersonate.leave') }}"
                        method="POST" class="d-none">
                        @csrf
                    </form>
                @else
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
