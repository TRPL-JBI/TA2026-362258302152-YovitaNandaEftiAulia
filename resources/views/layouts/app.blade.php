<!DOCTYPE html>
<html>
<head>
    <title>SPMI</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/poliwangi.png') }}" alt="Logo" class="logo-img">
            <div>
                <strong>Sistem Informasi SPMI</strong><br>
                <small>Politeknik Negeri Banyuwangi</small>
            </div>
        </div>

        <ul>

    <li>
        <a href="#">🏠 Dashboard</a>
    </li>

    <li class="{{ request()->is('standarmutu*') ? 'active' : '' }}">
        <a href="{{ route('standarmutu.index') }}">📚 Standar Mutu</a>
    </li>

    <li>
        <a href="#">📅 Periode AMI</a>
    </li>

    <li class="{{ request()->is('unit-kerja*') ? 'active' : '' }}">
        <a href="{{ route('unit-kerja.index') }}">🏢 Unit Kerja</a>
    </li>

    <li class="{{ request()->is('user*') ? 'active' : '' }}">
    <a href="{{ route('user.index') }}">👤 User</a>
    </li>

    <li>
        <a href="#">📄 Laporan AMI</a>
    </li>

    <li>
        <a href="#">🚪 Logout</a>
    </li>

</ul>
    </div>

    <!-- MAIN -->
    <div class="main">

        <!-- NAVBAR -->
        <div class="navbar">
            <input type="text" placeholder="Search...">

            <div class="profile-dropdown">
                <button class="profile-icon" id="profileBtn">
                    <i class="bi bi-person-circle"></i>
                </button>

                @php $user = session('user'); @endphp

                <div class="dropdown-menu" id="profileMenu">
                    @if($user)
                        <p class="user-name">
                            {{ is_array($user) ? $user['nama'] : $user->nama }}
                        </p>
                        <p class="user-role">
                            Role: {{ is_array($user) ? $user['status'] : $user->status }}
                        </p>
                    @else
                        <p>Guest</p>
                    @endif

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
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

<!-- JS DROPDOWN (VERSI LEBIH STABIL) -->
<script>
const btn = document.getElementById('profileBtn');
const menu = document.getElementById('profileMenu');

btn.addEventListener('click', function(e) {
    e.stopPropagation();
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.profile-dropdown')) {
        menu.style.display = 'none';
    }
});
</script>

</body>
</html>