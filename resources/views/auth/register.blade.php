@extends('authentication')

@section('content')
<div class="login-page">

    <h1 class="header-text">Register!</h1>

    <div class="login-box">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name" class="login-label">Name</label>
            <input id="name" type="text" class="login-input" name="name" placeholder="Enter your name" required>

            <label for="email" class="login-label">Email</label>
            <input id="email" type="email" class="login-input" name="email" placeholder="Enter your email" required>

            <label for="password" class="login-label">Password</label>
            <input id="password" type="password" class="login-input" name="password" placeholder="Enter your password" required>

            <button type="submit" class="login-button">Register</button>
        </form>

        <p class="register-text">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
    </div>

</div>
@endsection