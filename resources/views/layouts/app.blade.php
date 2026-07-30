<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Sistem Informasi SPMI
    </title>

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- CSS utama --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    {{-- CSS dropdown profil --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/profile-dropdown.css') }}"
    >

    {{-- CSS form user --}}
    @php
        $userFormCssPath = public_path(
            'css/app/16-admin-user-form.css'
        );
    @endphp

    @if (file_exists($userFormCssPath))
        <link
            rel="stylesheet"
            href="{{ asset('css/app/16-admin-user-form.css') }}?v={{ filemtime($userFormCssPath) }}"
        >
    @endif

    {{-- CSS daftar Unit Kerja --}}
    @php
        $unitIndexCssPath = public_path(
            'css/app/15-admin-unit-index.css'
        );
    @endphp

    @if (file_exists($unitIndexCssPath))
        <link
            rel="stylesheet"
            href="{{ asset('css/app/15-admin-unit-index.css') }}?v={{ filemtime($unitIndexCssPath) }}"
        >
    @endif

    {{-- CSS tambahan dari halaman tertentu --}}
    @stack('styles')
</head>

<body>

<div class="wrapper">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">

        {{-- Logo --}}
        <div class="logo">

            <img
                src="{{ asset('images/poliwangi.png') }}"
                alt="Logo Politeknik Negeri Banyuwangi"
                class="logo-img"
            >

            <div class="logo-text">

                <strong>
                    Sistem Informasi SPMI
                </strong>

                <small>
                    Politeknik Negeri Banyuwangi
                </small>

            </div>

        </div>

        {{-- Menu sidebar --}}
        <ul>

            {{-- Dashboard --}}
            <li
                class="{{
                    request()->routeIs('dashboard')
                        ? 'active'
                        : ''
                }}"
            >
                <a href="{{ route('dashboard') }}">

                    <i class="bi bi-house-door"></i>

                    <span>
                        Dashboard
                    </span>

                </a>
            </li>

            {{-- Standar Mutu --}}
            <li
                class="{{
                    request()->routeIs('standarmutu.*')
                    || request()->routeIs('isi.*')
                    || request()->routeIs('isi-standar.*')
                    || request()->routeIs('indikator.*')
                        ? 'active'
                        : ''
                }}"
            >
                <a href="{{ route('standarmutu.index') }}">

                    <i class="bi bi-journal-text"></i>

                    <span>
                        Standar Mutu
                    </span>

                </a>
            </li>

            {{-- Periode AMI --}}
            <li
                class="{{
                    request()->routeIs('periode-ami.*')
                    || request()->routeIs('penerapan.*')
                    || request()->routeIs('tim-ami.*')
                    || request()->routeIs('jadwal.*')
                        ? 'active'
                        : ''
                }}"
            >
                <a href="{{ route('periode-ami.index') }}">

                    <i class="bi bi-calendar-event"></i>

                    <span>
                        Periode AMI
                    </span>

                </a>
            </li>

            {{-- Unit Kerja --}}
            <li
                class="{{
                    request()->routeIs('unit-kerja.*')
                        ? 'active'
                        : ''
                }}"
            >
                <a href="{{ route('unit-kerja.index') }}">

                    <i class="bi bi-building"></i>

                    <span>
                        Unit Kerja
                    </span>

                </a>
            </li>

            {{-- User --}}
            <li
                class="{{
                    request()->routeIs('user.*')
                        ? 'active'
                        : ''
                }}"
            >
                <a href="{{ route('user.index') }}">

                    <i class="bi bi-people"></i>

                    <span>
                        User
                    </span>

                </a>
            </li>

            {{-- Laporan AMI --}}
            <li
                class="{{
                    request()->routeIs('laporan.*')
                        ? 'active'
                        : ''
                }}"
            >
                <a href="{{ route('laporan.index') }}">

                    <i class="bi bi-file-earmark-text"></i>

                    <span>
                        Laporan AMI
                    </span>

                </a>
            </li>

            {{-- Logout sidebar --}}
            <li>
                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="logout-form"
                >
                    @csrf

                    <button
                        type="submit"
                        class="logout-btn"
                    >
                        <i class="bi bi-box-arrow-right"></i>

                        <span>
                            Logout
                        </span>
                    </button>
                </form>
            </li>

        </ul>

    </aside>

    {{-- =====================================================
         MAIN CONTENT
    ====================================================== --}}

    <main class="main">

        {{-- Navbar --}}
        <div class="navbar">

            {{-- Search --}}
            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="globalSearch"
                    placeholder="Search..."
                    aria-label="Search"
                    autocomplete="off"
                >

            </div>

            {{-- Dropdown profil --}}
            <x-profile-dropdown />

        </div>

        {{-- Isi halaman --}}
        <div class="content">
            @yield('content')
        </div>

    </main>

</div>

{{-- JavaScript tambahan dari halaman tertentu --}}
@stack('scripts')

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const profileButton =
                document.getElementById('profileBtn');

            const profileMenu =
                document.getElementById('profileMenu');

            if (profileButton && profileMenu) {
                profileButton.addEventListener(
                    'click',
                    function (event) {
                        event.stopPropagation();

                        const isOpen =
                            profileMenu.classList.contains(
                                'is-open'
                            );

                        profileMenu.classList.toggle(
                            'is-open',
                            !isOpen
                        );

                        profileButton.setAttribute(
                            'aria-expanded',
                            String(!isOpen)
                        );
                    }
                );

                profileMenu.addEventListener(
                    'click',
                    function (event) {
                        event.stopPropagation();
                    }
                );

                document.addEventListener(
                    'click',
                    function () {
                        profileMenu.classList.remove(
                            'is-open'
                        );

                        profileButton.setAttribute(
                            'aria-expanded',
                            'false'
                        );
                    }
                );

                document.addEventListener(
                    'keydown',
                    function (event) {
                        if (event.key === 'Escape') {
                            profileMenu.classList.remove(
                                'is-open'
                            );

                            profileButton.setAttribute(
                                'aria-expanded',
                                'false'
                            );
                        }
                    }
                );
            }

            /*
             * Search sederhana untuk tabel pada halaman aktif.
             * Mencari isi seluruh baris tabel.
             */
            const globalSearch =
                document.getElementById('globalSearch');

            if (globalSearch) {
                globalSearch.addEventListener(
                    'input',
                    function () {
                        const keyword =
                            globalSearch.value
                                .toLowerCase()
                                .trim();

                        const tableRows =
                            document.querySelectorAll(
                                'table tbody tr'
                            );

                        tableRows.forEach(
                            function (row) {
                                const rowText =
                                    row.textContent
                                        .toLowerCase();

                                row.style.display =
                                    rowText.includes(keyword)
                                        ? ''
                                        : 'none';
                            }
                        );
                    }
                );
            }
        }
    );
</script>

</body>

</html>