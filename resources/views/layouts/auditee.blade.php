<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        SPMI Auditee
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
                    request()->routeIs('dashboard.auditee')
                    ? 'active'
                    : ''
                }}"
            >

                <a href="{{ route('dashboard.auditee') }}">

                    <i class="bi bi-house-door"></i>

                    <span>
                        Dashboard
                    </span>

                </a>

            </li>

            {{-- Standar Mutu --}}
            @foreach($sidebarStandar as $standar)

                <li
                    class="{{
                        request()->routeIs('auditee.standar.index')
                        && (string) request()->route('id')
                            === (string) $standar->id
                        ? 'active'
                        : ''
                    }}"
                >

                    <a
                        href="{{ route(
                            'auditee.standar.index',
                            $standar->id
                        ) }}"
                    >

                        <i class="bi bi-journal-check"></i>

                        <span>
                            {{ $standar->nama_standar_mutu }}
                        </span>

                    </a>

                </li>

            @endforeach


            {{-- Logout --}}
            <li class="sidebar-logout-item">

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

        /*
        |--------------------------------------------------------------------------
        | DROPDOWN MENU AUDIT AMI
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.menu-utama')
            .forEach(function (menu) {

                menu.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        const submenu =
                            this.nextElementSibling;

                        if (!submenu) {
                            return;
                        }

                        submenu.classList.toggle('show');

                        this.classList.toggle(
                            'is-open',
                            submenu.classList.contains('show')
                        );
                    }
                );
            });

        /*
        |--------------------------------------------------------------------------
        | MENU ANAK REKURSIF
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll('.menu-anak')
            .forEach(function (menu) {

                menu.addEventListener(
                    'click',
                    function (event) {

                        event.preventDefault();

                        event.stopPropagation();

                        const submenu =
                            this.nextElementSibling;

                        if (!submenu) {
                            return;
                        }

                        submenu.classList.toggle('show');
                    }
                );
            });

        /*
        |--------------------------------------------------------------------------
        | PROFILE DROPDOWN
        |--------------------------------------------------------------------------
        */

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

    }
);

</script>

</body>

</html>