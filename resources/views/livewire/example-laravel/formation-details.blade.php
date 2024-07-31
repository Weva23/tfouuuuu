<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la Formation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
            background-color: #f8f9fa;
        }
        .sidenav {
            width: 250px;
            background-color: #343a40;
            color: #ffffff;
        }
        .sidenav .nav-link {
            color: #dfe6e9;
            font-weight: 500;
            display: flex;
            align-items: center;
            padding: 10px 20px;
            transition: background-color 0.3s, color 0.3s;
        }
        .sidenav .nav-link:hover {
            background-color: #636e72;
            color: white;
        }
        .sidenav .nav-link.active {
            background-color: #e91e63;
            color: white;
        }
        .sidenav .nav-link .material-icons {
            margin-right: 10px;
            font-size: 20px;
        }
        .main-content {
            flex: 1;
            margin-left: 250px; /* Same width as the sidebar */
            padding: 20px;
        }
        .navbar-brand-img {
            max-height: 40px;
        }
        .sidenav-header .navbar-brand {
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        .sidenav-header .navbar-brand img {
            margin-right: 10px;
        }
        h6 {
            font-size: 0.75rem;
            margin-top: 20px;
            margin-bottom: 10px;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href="{{ route('dashboard') }}">
                <img src="{{ asset('assets/img/ipf.jpg') }}" class="navbar-brand-img" alt="main_logo">
                <span class="ms-2 font-weight-bold text-white">Institut Pédagogique de Formation (IPF)</span>
            </a>
        </div>
        <hr class="horizontal light mt-0 mb-2">
        <ul class="navbar-nav nav nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">Pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="material-icons opacity-10">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'etudiant-management' ? 'active' : '' }}" href="{{ route('etudiant-management') }}">
                    <i class="material-icons opacity-10">format_list_bulleted</i>
                    <span class="nav-link-text ms-1">Liste des Étudiants</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'prof-management' ? 'active' : '' }}" href="{{ route('prof-management') }}">
                    <i class="material-icons opacity-10">format_list_bulleted</i>
                    <span class="nav-link-text ms-1">Liste des Professeurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'formations-management' ? 'active' : '' }}" href="{{ route('formations-management') }}">
                    <i class="material-icons opacity-10">format_list_bulleted</i>
                    <span class="nav-link-text ms-1">Programmes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'contenusformation-management' ? 'active' : '' }}" href="{{ route('contenusformation-management') }}">
                    <i class="material-icons opacity-10">format_list_bulleted</i>
                    <span class="nav-link-text ms-1">Contenus de programme</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('static-sign-in') }}">
                    <i class="material-icons opacity-10">login</i>
                    <span class="nav-link-text ms-1">Sign In</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="{{ route('static-sign-up') }}">
                    <i class="material-icons opacity-10">assignment</i>
                    <span class="nav-link-text ms-1">Sign Up</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content container">
        <h1>Détails de la Formation</h1>
        <p><strong>Code:</strong> {{ $formation->code }}</p>
        <p><strong>Nom:</strong> {{ $formation->nom }}</p>
        <p><strong>Durée:</strong> {{ $formation->duree }} heures</p>
        <p><strong>Prix:</strong> {{ $formation->prix }} $</p>

        <h2>Contenus de la Formation</h2>
        @if($contenus->isEmpty())
            <p>Aucun contenu trouvé pour cette formation.</p>
        @else
            <ul>
                @foreach($contenus as $contenu)
                    <li>
                        <strong>Chapitre {{ $contenu->NumChap }}, Unité {{ $contenu->NumUnite }}</strong>: {{ $contenu->description }} ({{ $contenu->NombreHeures }} heures)
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('formations-management') }}" class="btn btn-primary mt-3">Retour à la liste des formations</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>