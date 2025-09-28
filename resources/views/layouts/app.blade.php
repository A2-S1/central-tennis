<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @if (file_exists(public_path('images/favicon.ico')))
        <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    @endif

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @endif
    <!-- Bootstrap CDN fallback (para ambientes sem NPM/Vite) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    <style>
      /* Alternativa B: aumentar visualmente 2x sem alterar o fluxo/layout da navbar */
      .navbar { overflow: visible; }
      .ct-logo {
        height: 36px; /* reduz o tamanho base */
        width: auto;
        object-fit: contain;
        transform: scale(1.6); /* aumenta visualmente, mas menor que antes */
        transform-origin: left center;
        display: inline-block;
      }
      @media (max-width: 576px) {
        .ct-logo { height: 32px; transform: scale(1.4); }
      }
      .navbar-brand { padding-top: 0.25rem; padding-bottom: 0.25rem; margin-right: 120px; }
      @media (max-width: 576px) {
        .navbar-brand { margin-right: 72px; }
      }
      .avatar-thumb { width: 28px; height: 28px; border-radius: 50%; object-fit: contain; background:#fff; margin-right: 8px; }
    </style>
</head>
<body>
    <div id="app">
        @empty($hideNavbarEntire)
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    @if (file_exists(public_path('images/centraltennis-logo.png')))
                        <img src="{{ asset('images/centraltennis-logo.png') }}?v={{ filemtime(public_path('images/centraltennis-logo.png')) }}" alt="Central Tennis" class="ct-logo">
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @empty($hideNavbarLinks)
                          <li class="nav-item"><a class="nav-link" href="{{ route('pages.about') }}">Quem Somos</a></li>
                          <li class="nav-item"><a class="nav-link" href="{{ route('community.index') }}">Comunidade</a></li>
                          <li class="nav-item"><a class="nav-link" href="{{ route('courts.index') }}">Onde Jogar</a></li>
                          <li class="nav-item"><a class="nav-link" href="{{ route('news.index') }}">Notícias</a></li>
                          <li class="nav-item"><a class="nav-link" href="{{ route('store.index') }}">Loja</a></li>
                          <li class="nav-item"><a class="nav-link" href="{{ route('classifieds.index') }}">Classificados</a></li>
                        @endempty
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @auth
                          @if(Auth::user()->is_admin)
                            <li class="nav-item me-2"><a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="bi bi-shield-lock me-1"></i>Admin</a></li>
                          @endif
                        @endauth
                        @auth
                          <li class="nav-item"><a class="nav-link" href="{{ route('home') }}"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
                        @endauth
                        <!-- Authentication Links -->
                        @guest
                            @empty($hideNavbarLinks)
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                            @endempty
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    @php($u = Auth::user())
                                    @if($u && $u->avatar)
                                        <img class="avatar-thumb" src="{{ asset('storage/'.$u->avatar) }}" alt="avatar">
                                    @endif
                                    <span>{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Meu Perfil</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @endempty

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>

