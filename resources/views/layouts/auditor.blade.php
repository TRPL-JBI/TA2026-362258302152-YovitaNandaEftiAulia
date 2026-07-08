<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SPMI Auditor</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="wrapper">

    <!-- ================= SIDEBAR ================= -->

    <div class="sidebar">

        <div class="logo">

            <img src="{{ asset('images/poliwangi.png') }}"
                 class="logo-img">

            <div class="logo-text">

                <strong>Sistem Informasi SPMI</strong>

                <small>Politeknik Negeri Banyuwangi</small>

            </div>

        </div>

        <ul>

    <!-- DASHBOARD -->
    <li class="{{ request()->routeIs('dashboard.auditor') ? 'active' : '' }}">
        <a href="{{ route('dashboard.auditor') }}">
            <i class="bi bi-house-door"></i>
            Dashboard
        </a>
    </li>

    <!-- STANDAR MUTU -->
    <li class="{{ request()->routeIs('auditor.standarmutu.*') || request()->routeIs('auditor.isi.*') || request()->routeIs('auditor.indikator.*') ? 'active' : '' }}">
        <a href="{{ route('auditor.standarmutu.index') }}">
            <i class="bi bi-journal-text"></i>
            Standar Mutu
        </a>
    </li>

    <!-- PERIODE AMI -->
    <li class="{{ request()->routeIs('auditor.periode.*') ? 'active' : '' }}">
        <a href="{{ route('auditor.periode.index') }}">
            <i class="bi bi-calendar-event"></i>
            Periode AMI
        </a>
    </li>

    <!-- AUDIT MUTU INTERNAL -->
    <li class="{{ request()->routeIs('auditor.temuan.*') ? 'active' : '' }}">
        <a href="{{ route('auditor.temuan.index') }}">
            <i class="bi bi-clipboard-check"></i>
            Audit Mutu Internal
        </a>
    </li>

    <!-- LAPORAN AMI -->
    <li>
        <a href="#">
            <i class="bi bi-file-earmark-text"></i>
            Laporan AMI
        </a>
    </li>

    <!-- LOGOUT -->
    <li>

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button type="submit" class="sidebar-logout">

                <i class="bi bi-box-arrow-right"></i>

                Logout

            </button>

        </form>

    </li>

</ul>

    </div>

    <!-- ================= MAIN ================= -->

    <div class="main">

        <div class="navbar">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input
                    type="text"
                    placeholder="Search...">

            </div>

            <div class="profile-dropdown">

                <button class="profile-icon"
                        id="profileBtn">

                    <i class="bi bi-person-circle"></i>

                </button>

                @php

                    $user = session('user');

                @endphp

                <div class="dropdown-menu"
                     id="profileMenu">

                    @if($user)

                        <p class="user-name">

                            {{ is_array($user) ? $user['nama'] : $user->nama }}

                        </p>

                        <p class="user-role">

                            Auditor Kepala

                        </p>

                    @endif

                    <form action="{{ route('logout') }}"
                          method="POST">

                        @csrf

                        <button type="submit"
                                class="btn-logout">

                            Logout

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <div class="content">

            @yield('content')

        </div>

    </div>

</div>

<script>

const btn = document.getElementById('profileBtn');

const menu = document.getElementById('profileMenu');

btn.addEventListener('click', function(e){

    e.stopPropagation();

    menu.style.display =
        menu.style.display === 'block'
        ? 'none'
        : 'block';

});

document.addEventListener('click', function(e){

    if(!e.target.closest('.profile-dropdown')){

        menu.style.display = 'none';

    }

});

</script>

</body>

</html>