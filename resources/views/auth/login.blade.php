@extends('authentication')

@section('content')
<div class="login-page">

    <h1 class="header-text">WELCOME !</h1>

    <div class="login-box">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email" class="login-label">Email</label>
            <input id="email" type="email" class="login-input" name="email" placeholder="Email" required autofocus>

            <label for="password" class="login-label">Password</label>
            <input id="password" type="password" class="login-input" name="password" placeholder="Password" required>

            <button type="submit" class="login-button">Login</button>
        </form>

        <p class="register-text">Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    </div>

</div>
@endsection