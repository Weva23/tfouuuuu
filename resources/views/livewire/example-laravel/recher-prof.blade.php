<!DOCTYPE html>
<html>
<head>
    <title>Recherche des Étudiants</title>
    <!-- Inclure le CSS de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Inclure le CSS d'IziToast -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

    <!-- Inclure le script de jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Inclure le script d'IziToast -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if (session('status'))
                <div class="alert alert-success fade-out">
                    {{ session('status') }}
                </div>
                @endif
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <h1 class="mb-4">Résultats des Recherches</h1>
                        <div>
                            <!-- Formulaire de recherche -->
                            <form action="{{ route('search1') }}" method="GET" class="form-inline mb-4">
                                <input type="text" name="search1" class="form-control mr-2" placeholder="Recherche..." value="{{ request()->input('search1') }}">
                                <button type="submit" class="btn btn-primary">Chercher</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Affichage des résultats -->
                @if($profs->count())
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                            <th>ID</th>
                            <th>NNI</th>
                            <th>Nom Prénom</th>
                            <th>Nationalité</th>
                            <th>Diplôme</th>
                            <th>Genre</th>
                            <th>Lieu de Naissance</th>
                            <th>Adresse</th>
                            <th>Date de naissance</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>WhatsApp</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($profs as $prof)
                                <tr>
                                    <td>{{ $prof->id }}</td>
                                    <td>{{ $prof->nomprenom }}</td>
                                    <td>{{ $prof->nationalite }}</td>
                                    <td>{{ $prof->email }}</td>
                                    <td>{{ $prof->diplome }}</td>
                                    <td>{{ $prof->phone }}</td>
                                    <td>{{ $prof->wtsp }}</td>
                                    <td>
                                        <a href="javascript:void(0)" id="edit-prof" class="btn btn-info btn-sm">Modifier</a>
                                        <a href="/delete-prof/{{ $prof->id }}" id="delete-prof" class="btn btn-danger btn-sm">Supprimer</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination des résultats -->
                    {{ $profs->appends(['search1' => request()->input('search1')])->links() }}
                @else
                    <p>Aucun professeur trouvé.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Inclure le script de Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
