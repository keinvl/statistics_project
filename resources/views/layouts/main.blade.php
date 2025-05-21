<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Calculator')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts & Styles -->
    <link href="https://fonts.googleapis.com/css?family=Tangerine" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    @include('partials.header')

    <div class="content">
        @yield('content')
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    @stack('scripts')
</body>
</html>