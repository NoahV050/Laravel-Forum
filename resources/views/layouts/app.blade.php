<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevPraat') · DevPraat Forum</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/forum.css') }}">
</head>
<body>
    <header class="site-header">
        <div class="nav-wrap">
            <a class="brand" href="{{ route('home') }}"><span class="brand-mark">&lt;/&gt;</span><span>DevPraat<span class="brand-dot">.</span></span></a>
            <nav class="main-nav" aria-label="Hoofdnavigatie">
                <a href="{{ route('home') }}">Forum</a>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('users.index') }}">Gebruikers</a>
                        <a href="{{ route('threads.create') }}">Nieuw onderdeel</a>
                    @endif
                    <span class="nav-user">Hoi, {{ auth()->user()->username }}</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button class="nav-button" type="submit">Uitloggen</button></form>
                @else
                    <a href="{{ route('login') }}">Inloggen</a>
                    <a class="button button-small" href="{{ route('register') }}">Account maken</a>
                @endauth
            </nav>
        </div>
    </header>
    <main class="page-wrap">
        @if (session('status'))
            <div class="alert-success" role="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
    <footer class="site-footer"><span>DevPraat</span> · Samen leren, samen bouwen.</footer>
</body>
</html>
