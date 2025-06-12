<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Artisanal Marketplace')</title>
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
</head>
<body>

    <nav class="bg-gray-100 p-4 mb-6">
        <a href="/" class="pr-10">Strona główna</a>
        <a href="{{ route('registerForm') }}" class="pr-10">Register</a>
        <a href="{{ route('loginForm') }}">Login</a>
        @if(session('user_id'))
        <div class="text-sm text-gray-600 text-center mt-4">
            Zalogowany użytkownik: {{ session('user_name') }}
            <a href="{{ route('logout') }}">Wyloguj</a>
        </div>
    @endif
    </nav>

    @yield('content')

    
    
</body>
</html>