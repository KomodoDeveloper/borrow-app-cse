<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>CSE-Prêt</title>
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" >
    @yield('additionnalCSS')
    <script src="https://kit.fontawesome.com/d346694c57.js" crossorigin="anonymous"></script>
</head>

<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color: #008CCC;">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">UNIL</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalog.index') }}">Inventaire</a>
                </li>
                @if (Auth::user()->is_cse_member == 1)
                 <li class="nav-item">
                     <a class="nav-link" href="{{ route('catalog.indexIntern') }}">Interne</a>
                  </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('help') }}">Comment faire</a>
                </li>
                @if (Auth::user()->is_admin == 1)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.index') }}">Admin</a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('myborrow.index') }}">Mes emprunts</a>
                </li>
                <li class="nav-item">
                    <span class="navbar-text">{{ Auth::user()->name }}</span>
                </li>
            </ul>
            <a href="{{ route('logout') }}"><img src="https://img.icons8.com/color/48/000000/exit.png" class="img-responsive" width="25" height="25" alt="logout img"></a>
        </div>
    </div>
</nav>

<!-- Page Content -->
@yield('content')

<!-- Footer -->
<footer class="py-3 fixed-bottom" style="background-color: #008CCC;">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; CSE 2023</p>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
@yield('additionnalScript')
</body>
</html>
