<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPMI</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Bootstrap Icon -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- LOGO -->
        <div class="logo">
            <img src="{{ asset('images/poliwangi.png') }}" alt="Logo" class="logo-img">

            <div class="logo-text">
                <strong>Sistem Informasi SPMI</strong>
                <small>Politeknik Negeri Banyuwangi</small>
            </div>
        </div>

        <!-- MENU -->
        <ul>

            <!-- DASHBOARD -->
            <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>

            <!-- STANDAR MUTU -->
            <li class="{{ request()->is('standarmutu*') ? 'active' : '' }}">
                <a href="{{ route('standarmutu.index') }}">
                    <i class="bi bi-journal-text"></i>
                    Standar Mutu
                </a>
            </li>

            <!-- PERIODE AMI -->
            <li>
                <a href="{{ route('periode-ami.index') }}">
                    <i class="bi bi-calendar-event"></i>
                    Periode AMI
                </a>
            </li>

            <!-- UNIT KERJA -->
            <li class="{{ request()->is('unit-kerja*') ? 'active' : '' }}">
                <a href="{{ route('unit-kerja.index') }}">
                    <i class="bi bi-building"></i>
                    Unit Kerja
                </a>
            </li>

            <!-- USER -->
            <li class="{{ request()->is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="bi bi-people"></i>
                    User
                </a>
            </li>

            <!-- LAPORAN -->
            <li>
                <a href="#">
                    <i class="bi bi-file-earmark-text"></i>
                    Laporan AMI
                </a>
            </li>

            <!-- LOGOUT -->
            <li>
                <a href="#">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </a>
            </li>

        </ul>

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- NAVBAR -->
        <div class="navbar">

            <!-- SEARCH -->
            <div class="search-box">
                <i class="bi bi-search"></i>

                <input type="text" placeholder="Search...">
            </div>

            <!-- PROFILE -->
            <div class="profile-dropdown">

                <button class="profile-icon" id="profileBtn">
                    <i class="bi bi-person-circle"></i>
                </button>

                @php
                    $user = session('user');
                @endphp

                <div class="dropdown-menu" id="profileMenu">

                    @if($user)

                        <p class="user-name">
                            {{ is_array($user) ? $user['nama'] : $user->nama }}
                        </p>

                        <p class="user-role">
                            Role:
                            {{ is_array($user) ? $user['status'] : $user->status }}
                        </p>

                    @else

                        <p>Guest</p>

                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="btn-logout">
                            Logout
                        </button>
                    </form>

                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="content">
            @yield('content')
        </div>

    </div>

</div>

<!-- DROPDOWN JS -->
<script>

const btn = document.getElementById('profileBtn');
const menu = document.getElementById('profileMenu');

btn.addEventListener('click', function(e) {

    e.stopPropagation();

    menu.style.display =
        menu.style.display === 'block'
        ? 'none'
        : 'block';
});

document.addEventListener('click', function(e) {

    if (!e.target.closest('.profile-dropdown')) {
        menu.style.display = 'none';
    }

});

</script>

</body>
</html>