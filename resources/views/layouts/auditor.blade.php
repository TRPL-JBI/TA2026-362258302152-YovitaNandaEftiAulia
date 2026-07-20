<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        SPMI Auditor
    </title>

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

    {{-- CSS khusus halaman --}}
    @stack('styles')

    {{-- Bootstrap Icons --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css"
        rel="stylesheet"
    >


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

        {{-- Menu --}}
        <ul>

            {{-- Dashboard --}}
            <li
                class="{{
                    request()->routeIs('dashboard.auditor')
                    ? 'active'
                    : ''
                }}"
            >

                <a href="{{ route('dashboard.auditor') }}">

                    <i class="bi bi-house-door"></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </li>

            {{-- Standar Mutu --}}
            <li
                class="{{
                    request()->routeIs('auditor.standarmutu.*')
                    || request()->routeIs('auditor.isi.*')
                    || request()->routeIs('auditor.indikator.*')
                    ? 'active'
                    : ''
                }}"
            >

                <a href="{{ route('auditor.standarmutu.index') }}">

                    <i class="bi bi-journal-text"></i>

                    <span>
                        Standar Mutu
                    </span>

                </a>

            </li>

            {{-- Periode AMI --}}
            <li
                class="{{
                    request()->routeIs('auditor.periode.*')
                    ? 'active'
                    : ''
                }}"
            >

                <a href="{{ route('auditor.periode.index') }}">

                    <i class="bi bi-calendar-event"></i>

                    <span>
                        Periode AMI
                    </span>

                </a>

            </li>

            {{-- Audit Mutu Internal --}}
            <li
                class="{{
                    request()->routeIs('auditor.temuan.*')
                    ? 'active'
                    : ''
                }}"
            >

                <a href="{{ route('auditor.temuan.index') }}">

                    <i class="bi bi-clipboard-check"></i>

                    <span>
                        Audit Mutu Internal
                    </span>

                </a>

            </li>

            {{-- Laporan AMI --}}
            <li
                class="{{
                    request()->routeIs('auditor.laporan.*')
                    ? 'active'
                    : ''
                }}"
            >

                <a href="{{ route('auditor.laporan.index') }}">

                    <i class="bi bi-file-earmark-text"></i>

                    <span>
                        Laporan AMI
                    </span>

                </a>

            </li>

            {{-- Logout --}}
            <li>

                <form
                    action="{{ route('logout') }}"
                    method="POST"
                    class="logout-form"
                    onsubmit="
                        return confirm(
                            'Apakah Anda yakin ingin keluar dari sistem?'
                        );
                    "
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
         MAIN
    ====================================================== --}}

    <main class="main">

        {{-- Navbar --}}
        <div class="navbar">

            {{-- Search --}}
            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    placeholder="Search..."
                    aria-label="Search"
                >

            </div>

            {{-- Dropdown profil baru --}}
            <x-profile-dropdown />

        </div>

        {{-- Content --}}
        <div class="content">

            @yield('content')

        </div>

    </main>

</div>

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {

        const profileButton =
            document.getElementById('profileBtn');

        const profileMenu =
            document.getElementById('profileMenu');

        if (!profileButton || !profileMenu) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | BUKA / TUTUP PROFILE
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | CEGAH MENU TERTUTUP SAAT DIKLIK
        |--------------------------------------------------------------------------
        */

        profileMenu.addEventListener(
            'click',
            function (event) {

                event.stopPropagation();
            }
        );

        /*
        |--------------------------------------------------------------------------
        | TUTUP SAAT KLIK DI LUAR
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | TUTUP SAAT TEKAN ESC
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function (event) {

                if (event.key !== 'Escape') {
                    return;
                }

                profileMenu.classList.remove(
                    'is-open'
                );

                profileButton.setAttribute(
                    'aria-expanded',
                    'false'
                );
            }
        );

    }
);

</script>

</body>

</html>