<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'SPMI Auditor')
    </title>

    {{-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    {{-- =====================================================
         CSS UTAMA SISTEM
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    {{-- =====================================================
         CSS DROPDOWN PROFIL
    ====================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/profile-dropdown.css') }}"
    >

    {{-- =====================================================
         CSS KHUSUS SETIAP HALAMAN
    ====================================================== --}}

    @stack('styles')

</head>

<body>

<div class="wrapper">

    {{-- =====================================================
         SIDEBAR
    ====================================================== --}}

    <aside class="sidebar">

        {{-- =================================================
             LOGO
        ================================================== --}}

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

        {{-- =================================================
             MENU SIDEBAR
        ================================================== --}}

        <ul>

            {{-- =============================================
                 DASHBOARD
            ============================================== --}}

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

            {{-- =============================================
                 STANDAR MUTU
            ============================================== --}}

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

            {{-- =============================================
                 PERIODE AMI
            ============================================== --}}

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

            {{-- =============================================
                 AUDIT MUTU INTERNAL
            ============================================== --}}

            <li
                class="{{
                    request()->routeIs('auditor.temuan.*')
                    || request()->routeIs('auditor.tanggapan.*')
                    || request()->routeIs('auditor.akarmasalah.*')
                    || request()->routeIs('auditor.rekomendasi.*')
                    || request()->routeIs('auditor.kesimpulan.*')
                    || request()->routeIs('auditor.lampiran.*')
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

            {{-- =============================================
                 LAPORAN AMI
            ============================================== --}}

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

            {{-- =============================================
                 LOGOUT
            ============================================== --}}

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
         KONTEN UTAMA
    ====================================================== --}}

    <main class="main">

        {{-- =================================================
             NAVBAR
        ================================================== --}}

        <div class="navbar">

            {{-- =============================================
                 SEARCH
            ============================================== --}}

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    id="globalSearch"
                    placeholder="Cari data..."
                    aria-label="Cari data"
                    autocomplete="off"
                >

            </div>

            {{-- =============================================
                 PROFILE DROPDOWN
            ============================================== --}}

            <x-profile-dropdown />

        </div>

        {{-- =================================================
             CONTENT
        ================================================== --}}

        <div class="content">

            @yield('content')

        </div>

    </main>

</div>

{{-- =========================================================
     JAVASCRIPT DROPDOWN PROFIL
========================================================== --}}

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const profileButton =
                document.getElementById('profileBtn');

            const profileMenu =
                document.getElementById('profileMenu');

            /*
            |--------------------------------------------------------------------------
            | HENTIKAN JIKA ELEMEN PROFILE TIDAK ADA
            |--------------------------------------------------------------------------
            */

            if (!profileButton || !profileMenu) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | BUKA ATAU TUTUP PROFILE
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
            | CEGAH MENU TERTUTUP SAAT ISINYA DIKLIK
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
            | TUTUP SAAT MENEKAN ESCAPE
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

                    profileButton.focus();
                }
            );
        }
    );
</script>

{{-- =========================================================
     JAVASCRIPT PENCARIAN SEDERHANA
========================================================== --}}

<script>
    document.addEventListener(
        'DOMContentLoaded',
        function () {
            const searchInput =
                document.getElementById('globalSearch');

            if (!searchInput) {
                return;
            }

            searchInput.addEventListener(
                'input',
                function () {
                    const keyword =
                        searchInput.value
                            .toLowerCase()
                            .trim();

                    const tableRows =
                        document.querySelectorAll(
                            'table tbody tr'
                        );

                    tableRows.forEach(
                        function (row) {
                            /*
                            |--------------------------------------------------------------------------
                            | JANGAN SEMBUNYIKAN EMPTY STATE
                            |--------------------------------------------------------------------------
                            */

                            if (
                                row.querySelector(
                                    '.auditor-empty-state'
                                )
                            ) {
                                return;
                            }

                            const rowText =
                                row.textContent
                                    .toLowerCase()
                                    .trim();

                            row.style.display =
                                rowText.includes(keyword)
                                    ? ''
                                    : 'none';
                        }
                    );
                }
            );
        }
    );
</script>

{{-- =========================================================
     JAVASCRIPT KHUSUS SETIAP HALAMAN
========================================================== --}}

@stack('scripts')

</body>

</html>