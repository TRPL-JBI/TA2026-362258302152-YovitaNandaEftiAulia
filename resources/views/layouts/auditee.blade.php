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
            <li class="{{ request()->routeIs('dashboard.auditee') ? 'active' : '' }}">
                <a href="{{ route('dashboard.auditee') }}">
                    <i class="bi bi-house-door"></i>
                    Dashboard
                </a>
            </li>

            <!-- STANDAR MUTU -->

            @foreach($sidebarStandar as $standar)
                <li class="{{
                    request()->routeIs('auditee.standar.index') &&
                    (string) request()->route('id') === (string) $standar->id
                        ? 'active'
                        : ''
                }}">
                    <a href="{{ route('auditee.standar.index', $standar->id) }}">
                        <i class="bi bi-journal-check"></i>
                        <span>{{ $standar->nama_standar_mutu }}</span>
                    </a>
                </li>
            @endforeach

           

          <!-- AUDIT AMI -->
<li class="sidebar-dropdown-item">

    <a
        href="#"
        class="menu-utama
        {{
            request()->routeIs('auditee.audit.*')
                ? 'active-parent'
                : ''
        }}"
    >

        <span>
            <i class="bi bi-clipboard-check"></i>
            Audit AMI
        </span>

        <i class="bi bi-chevron-down menu-arrow"></i>

    </a>

    <ul
        class="menu-level-1
        {{
            request()->routeIs('auditee.audit.*')
                ? 'show'
                : ''
        }}"
    >

        <!-- TEMUAN AUDIT -->
        <li
            class="{{
                request()->routeIs('auditee.audit.temuan.*')
                    ? 'active'
                    : ''
            }}"
        >
            <a href="{{ route('auditee.audit.temuan.index') }}">
                <i class="bi bi-search"></i>

                <span>
                    Temuan Audit
                </span>
            </a>
        </li>

        <!-- TIM AUDIT -->
        <li
            class="{{
                request()->routeIs('auditee.audit.tim.*')
                    ? 'active'
                    : ''
            }}"
        >
            <a href="{{ route('auditee.audit.tim.index') }}">
                <i class="bi bi-people"></i>

                <span>
                    Tim Audit
                </span>
            </a>
        </li>

        <!-- JADWAL AUDIT -->
        <li
            class="{{
                request()->routeIs('auditee.audit.jadwal.*')
                    ? 'active'
                    : ''
            }}"
        >
            <a href="{{ route('auditee.audit.jadwal.index') }}">
                <i class="bi bi-calendar-week"></i>

                <span>
                    Jadwal Audit
                </span>
            </a>
        </li>

    </ul>

</li>

            <!-- LOGOUT -->
            
<li class="sidebar-logout-item">

    <form
        action="{{ route('logout') }}"
        method="POST"
        class="logout-form"
        onsubmit="return confirm('Apakah Anda yakin ingin keluar dari sistem?')"
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

// MENU UTAMA
document.querySelectorAll('.menu-utama').forEach(function(menu){

    menu.addEventListener('click',function(e){

        e.preventDefault();

        const submenu = this.nextElementSibling;

        if(submenu){

            submenu.classList.toggle('show');

        }

    });

});

// MENU ANAK (RECURSIVE)
document.querySelectorAll('.menu-anak').forEach(function(menu){

    menu.addEventListener('click',function(e){

        e.preventDefault();

        e.stopPropagation();

        const submenu = this.nextElementSibling;

        if(submenu){

            submenu.classList.toggle('show');

        }

    });

});

</script>

</body>
</html>