<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultats de recherche - Contenus de Formation</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .sidenav {
            width: 250px;
            background-color: #1f2937;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            top: 0;
            left: 0;
            padding-top: 20px;
            padding-bottom: 20px;
            border-radius: 10px;
        }
        .sidenav .navbar-brand {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            text-decoration: none;
            color: #ffffff;
            white-space: normal;
            font-weight: bold;
            font-size: 14px;
            line-height: 1.2;
        }
        .sidenav .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        .sidenav h6 {
            padding-left: 20px;
            padding-right: 20px;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 0.75rem;
            text-transform: uppercase;
            color: #9ca3af;
        }
        .sidenav .nav-link {
            color: #d1d5db;
            font-weight: 500;
            padding: 10px 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
            border-radius: 5px;
            margin: 0 10px;
        }
        .sidenav .nav-link:hover, .sidenav .nav-link.active {
            background-color: #e91e63;
            color: white;
        }
        .sidenav .nav-link .material-icons {
            margin-right: 10px;
            font-size: 20px;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
        .table-custom {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table-custom th,
        .table-custom td {
            padding: 0.75rem;
            vertical-align: middle;
            border-top: 1px solid #dee2e6;
        }
        .table-custom thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            background-color: #f8f9fa;
        }
        .table-custom tbody tr:hover {
            background-color: #f1f1f1;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }
        .pagination {
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }
        .pagination li {
            margin: 0 5px;
        }
        .pagination li a {
            color: #007bff;
            text-decoration: none;
        }
        .pagination li.active a {
            background-color: #e91e63;
            color: white;
        }
        .container h1 {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
        }
        .form-inline .btn {
            background-color: #e91e63;
            color: white;
        }
        .form-inline .btn:hover {
            background-color: #c2185b;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .table th, .table td {
            white-space: nowrap;
        }
        .btn-group .btn {
            border-radius: 0;
        }
        .btn-group .btn:first-child {
            border-top-left-radius: 4px;
            border-bottom-left-radius: 4px;
        }
        .btn-group .btn:last-child {
            border-top-right-radius: 4px;
            border-bottom-right-radius: 4px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidenav" id="sidenav-main">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/ipf.jpg') }}" alt="main_logo">
            <span>Institut Pédagogique<br>de Formation (IPF)</span>
        </a>
        <hr class="horizontal light mt-0 mb-2">
        <ul class="navbar-nav">
            <li class="nav-item">
                <h6 class="text-uppercase text-xs font-weight-bolder opacity-8">Pages</h6>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="material-icons">dashboard</i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'etudiant-management' ? 'active' : '' }}" href="{{ route('etudiant-management') }}">
                    <i class="material-icons">format_list_bulleted</i>
                    <span>Liste des Étudiants</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'prof-management' ? 'active' : '' }}" href="{{ route('prof-management') }}">
                    <i class="material-icons">format_list_bulleted</i>
                    <span>Liste des Professeurs</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'formations-management' ? 'active' : '' }}" href="{{ route('formations-management') }}">
                    <i class="material-icons">format_list_bulleted</i>
                    <span>Programmes</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Route::currentRouteName() == 'contenusformation-management' ? 'active' : '' }}" href="{{ route('contenusformation-management') }}">
                    <i class="material-icons">format_list_bulleted</i>
                    <span>Contenus de programme</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('static-sign-in') }}">
                    <i class="material-icons">login</i>
                    <span>Sign In</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('static-sign-up') }}">
                    <i class="material-icons">assignment</i>
                    <span>Sign Up</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">
            <h1>Résultats de recherche - Contenus de Formation</h1>

            <!-- Formulaire de recherche -->
            <form action="{{ route('search3') }}" method="GET" class="form-inline mb-4">
                <input type="text" name="search3" id="sear_bar" class="form-control mr-2" placeholder="Recherche..." value="{{ request()->input('search3') }}">
            </form>

            @if($contenues->count())
                <div class="table-responsive">
                    <table class="table-custom table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du Chapitre</th>
                                <th>Nom de l'Unité</th>
                                <th>Description</th>
                                <th>Nombre d'heures</th>
                                <th>Formation</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contenues as $contenu)
                                <tr id="contenu-{{ $contenu->id }}">
                                    <td>{{ $contenu->id }}</td>
                                    <td>{{ $contenu->nomchap }}</td>
                                    <td>{{ $contenu->nomunite }}</td>
                                    <td>{{ $contenu->description }}</td>
                                    <td>{{ $contenu->nombreheures }}</td>
                                    <td>{{ $contenu->formation->nomformation ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <nav>
                    <ul class="pagination">
                        {{ $contenues->appends(['search3' => $search3])->links() }}
                    </ul>
                </nav>
            @else
                <p>Aucun contenu de formation trouvé.</p>
            @endif


            <!-- Affichage des résultats -->
            <div class="results-table">
                @include('partials.table_contenus', ['contenues' => $contenues])
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="path/to/your/js/file.js"></script>
</body>
</html>



    <!-- Main Content -->
    

</body>
<script>
    $(document).ready(function () {
    $('#sear_bar').on('keyup', function() {
        var search = $(this).val();
        $.ajax({
            url: "{{ route('search3') }}",
            type: "GET",
            data: { search3: search },
            success: function(data) {
                $('.results-table').html(data);
            }
        });
    });
});

</script>
</html> 