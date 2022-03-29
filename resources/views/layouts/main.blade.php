<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale() ) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- CSS da aplicação -->
    <link rel="stylesheet" href="./css/app.css">

    <!-- CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>

</head>

<body>
    <header class="p-3 bg-dark text-white" style="padding: 0.5rem !important;">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <img src="/img/icon.png" alt="icon" class="bi me-2" width="40" height="40" role="img" aria-label="Bootstrap">
                </a>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="/" class="nav-link px-2 text-white">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="/audit" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Auditoria
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/audit">Upload</a></li>
                            <li><a class="dropdown-item" href="/audit/list">Consultar Auditoria</a></li>
                            <li><a class="dropdown-item" href="#">Realizar Auditoria</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Produtos
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/products">Criar Produto</a></li>
                            <li><a class="dropdown-item" href="/products/show">Consultar Produto</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-danger" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cartons
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/cartons">Criar Caixa</a></li>
                            <li><a class="dropdown-item" href="/cartons/show">Consultar Caixas</a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="nav-link px-2 text-danger">About</a></li>
                </ul>
                @if (Route::has('login') && Auth::check())

                <div class="dropdown" >
                    
                    <button 
                        style="display:flex; align-items: center; justify-content: space-between; flex-direction: row;" 
                        class="btn btn-light dropdown-toggle" 
                        type="button" 
                        id="logout-button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false">
                        <i data-feather="user" style="height: 24px; width: 24px; padding: -5px"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <li>
                             <p class="dropdown-item">{{ Auth::user()->name}}</p>
                            </li>
                            <li>
                             <input class="dropdown-item" type="submit" value="Logout">
                            </li>
                        </form>
                    </ul>
                </div>

                @elseif (Route::has('login') && !Auth::check())
                <div class="text-end">
                    <a href="{{ url('/login') }}"><button type="button" class="btn btn-outline-light me-2">Login</button></a>
                    <a href="{{ url('/register') }}"><button type="button" class="btn btn-info">Sign-up</button></a>
                </div>
                @endif

            </div>
        </div>
    </header>

    <div class="container">
        @yield('content')
    </div>
    
    <footer class="fixed-bottom" 
    style="max-height: 50px">
        <div class="container">
            <footer class="d-flex flex-wrap justify-content-between align-items-center border-top">
                <p class="col-md-4 mb-0 text-muted">© 2021 Bosch</p>
            </footer>
        </div>
    </footer>
    <script>
        feather.replace()
    </script>
</body>

</html>