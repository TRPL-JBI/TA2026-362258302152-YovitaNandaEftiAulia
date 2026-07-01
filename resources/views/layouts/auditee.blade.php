<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPMI Auditee</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- LOGO -->
        <div class="logo">

            <img src="{{ asset('images/poliwangi.png') }}"
                 alt="Logo"
                 class="logo-img">

            <div class="logo-text">

                <strong>
                    Sistem Informasi SPMI
                </strong>

                <small>
                    Politeknik Negeri Banyuwangi
                </small>

            </div>

        </div>

        <!-- MENU -->
        <ul>

            <!-- DASHBOARD -->
 <ul>

    <!-- DASHBOARD -->
    <li>
        <a href="{{ route('dashboard.auditee') }}">
            <i class="bi bi-house-door"></i>
            Dashboard
        </a>
    </li>

    <!-- STANDAR PENDIDIKAN -->
    <li>

        <a href="#" class="menu-utama">

            <span>
                <i class="bi bi-journal-text"></i>
                Standar Pendidikan 2026
            </span>

            <i class="bi bi-chevron-down"></i>

        </a>

        <ul class="menu-level-1">

            <!-- STANDAR MASUKAN -->
            <li>

                <a href="#" class="menu-anak">

                    <span>
                        Standar Masukan
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </a>

                <ul class="menu-level-2">

                    <li>
                        <a href="#">• Standar Dosen</a>
                    </li>

                    <li>
                        <a href="#">• Standar Mahasiswa</a>
                    </li>

                </ul>

            </li>

            <!-- STANDAR MASUKAN 2 -->
            <li>

                <a href="#" class="menu-anak">

                    <span>
                        Standar Masukan
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </a>

                <ul class="menu-level-2">

                    <li>
                        <a href="#">• Standar Pembelajaran</a>
                    </li>

                    <li>
                        <a href="#">• Standar Penilaian</a>
                    </li>

                </ul>

            </li>

            <!-- STANDAR LUARAN -->
            <li>

                <a href="#" class="menu-anak">

                    <span>
                        Standar Luaran
                    </span>

                    <i class="bi bi-chevron-down"></i>

                </a>

                <ul class="menu-level-2">

                    <li>
                        <a href="#">
                            • Standar Kompetensi Lulusan
                        </a>
                    </li>

                </ul>

            </li>

        </ul>

    </li>

    <!-- AUDIT AMI -->
    <li>

        <a href="#" class="menu-utama">

            <span>
                <i class="bi bi-people-fill"></i>
                Audit AMI
            </span>

            <i class="bi bi-chevron-down"></i>

        </a>

        <ul class="menu-level-1">

            <li><a href="#">• Temuan</a></li>

            <li><a href="#">• Tanggapan</a></li>

            <li><a href="#">• Rekomendasi</a></li>

            <li><a href="#">• Kesimpulan</a></li>

            <li><a href="#">• Lampiran</a></li>

        </ul>

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

        </ul>

    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- NAVBAR -->
        <div class="navbar">

            <div class="search-box">

                <i class="bi bi-search"></i>

                <input type="text"
                       placeholder="Search...">

            </div>

            <div class="profile-dropdown">

                <button class="profile-icon"
                        id="profileBtn">

                    <i class="bi bi-person-circle"></i>

                </button>

                <div class="dropdown-menu"
                     id="profileMenu">

                    <p class="user-name">
                        {{ session('user')['nama'] ?? '-' }}
                    </p>

                    <p class="user-role">
                        Auditee
                    </p>

                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="content">

            @yield('content')

        </div>

    </div>

</div>

<script>

document.querySelectorAll('.menu-utama')
.forEach(function(item){

    item.addEventListener('click', function(e){

        e.preventDefault();

        let submenu =
            this.nextElementSibling;

        submenu.classList.toggle('show');

    });

});


document.querySelectorAll('.menu-anak')
.forEach(function(item){

    item.addEventListener('click', function(e){

        e.preventDefault();

        e.stopPropagation();

        let submenu =
            this.nextElementSibling;

        submenu.classList.toggle('show');

    });

});

</script>

</body>
</html>