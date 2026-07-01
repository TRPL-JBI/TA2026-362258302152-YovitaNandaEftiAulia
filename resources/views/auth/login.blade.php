<!DOCTYPE html>
<html>
<head>
    <title>Login SPMI</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="login-page">

<div class="login-container">

    <img src="{{ asset('images/poliwangi.png') }}" class="login-logo">

    <h2>Selamat Datang!</h2>
    <p>SISTEM INFORMASI SPMI POLITEKNIK NEGERI BANYUWANGI</p>

    <div class="login-card">
        <h3>Login to your account</h3>

        @if(session('error'))
            <p style="color:red">{{ session('error') }}</p>
        @endif

        <form method="POST" action="/login">
            @csrf

            <input type="text" name="username" placeholder="Username">

            <!-- Password Input -->
            <div class="password-wrapper">
                <input type="password" name="password" id="password" placeholder="Password">

                <i class="fa-solid fa-eye" id="togglePassword"></i>
            </div>

            <button type="submit">LOGIN</button>
        </form>
    </div>

</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    togglePassword.addEventListener('click', function () {

        const type = password.getAttribute('type') === 'password'
            ? 'text'
            : 'password';

        password.setAttribute('type', type);

        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
</script>

</body>
</html>