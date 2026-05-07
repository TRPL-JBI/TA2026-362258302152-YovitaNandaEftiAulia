<!DOCTYPE html>
<html>
<head>
    <title>Login SPMI</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            <input type="password" name="password" placeholder="Password">

            <button type="submit">LOGIN</button>
        </form>
    </div>

</div>

</body>
</html>