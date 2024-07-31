


<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Contenus Programmes Management</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script> -->
    <link href="{{ asset('assets/css/material-icons.css') }}" rel="stylesheet">
    <!-- iziToast CSS -->
    <link href="{{ asset('assets/css/iziToast.min.css') }}" rel="stylesheet">
    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <!-- Popper.js -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <!-- Bootstrap Bundle JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <!-- SweetAlert2 -->
    <script src="{{ asset('assets/js/sweetalert2.min.js') }}"></script>
    <!-- iziToast JS -->
    <script src="{{ asset('assets/js/iziToast.min.js') }}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .imgUpload {
            max-width: 90px;
            max-height: 70px;
            min-width: 50px;
            min-height: 50px;
        }
        .required::after {
            content: " *";
            color: red;
        }
        .form-control {
            border: 1px solid #ccc;
        }
        .form-control:focus {
            border-color: #66afe9;
            outline: 0;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(102, 175, 233, 0.6);
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                @if (session('status'))
                <div class="alert alert-success fade-out">
                    {{ session('status')}}
                </div>
                @endif
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
                @endif
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('formations-management') }}" class="btn bg-gradient-dark material-icons text-sm">arrow_back</a>
                            
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#contenueAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter un contenu
                            </button>
                            <a href="{{ route('contenues.export') }}" class="btn btn-success">Exporter Contenus</a>
                        </div>
                        <form class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search3" id="search_bar" class="form-control" placeholder="Rechercher...">
                            </div>
                        </form>
                    </div>
                    <div class="me-3 my-3 text-end "></div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="contenus-table">
                            @include('livewire.example-laravel.contenus-list', ['contenues' => $contenues])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

                    <div class="me-3 my-3 text-end "></div>

  
    <div class="modal fade" id="formationDetailModal" tabindex="-1" aria-labelledby="formationDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="width: 10cm; height:8cm;  ">
            <div class="modal-header">
                <h5 class="modal-title" id="formationDetailModalLabel">Détails de la Programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="formation-details">
                    <!-- <h4>Detaille de la Formation</h4> -->
                    <ul id="formation-contents-details"></ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Ajouter Contenu -->
    <div class="modal fade" id="contenueAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouveau contenu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="contenue-add-form">
                        @csrf
                        <div class="row mb-2">
                        <div class="form-group col-md-6">
                            <label for="formation_id" class="form-label required">Programme</label>
                            <select class="form-control" id="new-contenue-formation_id" name="formation_id">
                                <option value="">Sélectionner Programme</option>
                                @foreach ($formations as $formation)
                                    <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="formation_id-warning"></div>

                        </div>
                        <div class="col-md-6">
                            <label for="nomchap" class="form-label required">Nom du Chapitre:</label>
                            <input type="text" class="form-control" id="new-contenue-nomchap" placeholder="Nom du chapitre" name="nomchap">
                            <div class="text-danger" id="nomchap-warning"></div>

                        </div>
                        </div>
                        <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="nomunite" class="form-label required">Nom de l'unité:</label>
                            <input type="text" class="form-control" id="new-contenue-nomunite" placeholder="Nom de l'unité" name="nomunite">
                            <div class="text-danger" id="nomunite-warning"></div>

                        </div>
                        <div class="col-md-6">
                            <label for="nombreheures" class="form-label required">Nombre Heures:</label>
                            <input type="number" class="form-control" id="new-contenue-nombreheures" placeholder="Nombre Heures" name="nombreheures">
                            <div class="text-danger" id="nombreheures-warning"></div>

                        </div>
                        </div>
                        <div class="col-md-6">
                            <label for="description" class="form-label">Description:</label>
                            <input type="text" class="form-control" id="new-contenue-description" placeholder="Description" name="description">
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="add-new-contenue">Ajouter</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Contenu -->
    <div class="modal fade" id="contenueEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modifier contenu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="contenue-edit-form">
                        @csrf
                        <input type="hidden" id="contenue-id" name="id">
                        <div class="form-group row mb-2">
                            <div class="col-md-6">

                                <label for="formation_id" class="form-label required">Programme</label>
                                <select class="form-control" id="contenue-formation_id" name="formation_id">
                                    <option value="">Sélectionner Programme</option>
                                    @foreach ($formations as $formation)
                                        <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                                    @endforeach
                                </select>
                                <div class="text-danger" id="edit-formation_id-warning"></div>

                            </div>
                        <!-- <br> -->
                        
                            <div class="col-md-6">
                                <label for="nomchap" class="form-label required">Nom du Chapitre:</label>
                                <input type="text" class="form-control" id="contenue-nomchap" placeholder="Nom du chapitre" name="nomchap">
                                <div class="text-danger" id="edit-nomchap-warning"></div>

                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="nomunite" class="form-label required">Nom de l'unité:</label>
                                <input type="text" class="form-control" id="contenue-nomunite" placeholder="Nom de l'unité" name="nomunite">
                                <div class="text-danger" id="edit-nomunite-warning"></div>

                            </div>
                            

                        <!-- </div> -->
                        <!-- <div class="row mb-2"> -->
                            <div class="col-md-6">
                                <label for="nombreheures" class="form-label required">Nombre Heures:</label>
                                <input type="number" class="form-control" id="contenue-nombreheures" placeholder="Nombre Heures" name="nombreheures">
                                <div class="text-danger" id="edit-nombreheures-warning"></div>
                            </div>

                        </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description:</label>
                                <input type="text" class="form-control" id="contenue-description" placeholder="Description" name="description">
                            </div>

                        <!-- </div> -->
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info" id="contenue-update">Modifier</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Ajouter un contenu
    $("#add-new-contenu").click(function(e){
        e.preventDefault();
        let form = $('#contenue-add-form')[0];
        let data = new FormData(form);

        $.ajax({
            url: "{{ route('contenus.store') }}",
            type: "POST",
            data: data,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.errors) {
                    iziToast.error({
                        message: response.errors.join('<br>'),
                        position: 'topRight'
                    });
                } else {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    $('#contenueAddModal').modal('hide');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'An error occurred: ' + error,
                    position: 'topRight'
                });
            }
        });
    });

    // Modifier un contenu
    $('body').on('click', '#edit-contenue', function () {
        var id = $(this).data('id');
        $.get('/contenus/' + id, function (data) {
            $('#contenue-id').val(data.contenu.id);
            $('#contenue-formation_id').val(data.contenu.formation_id);
            $('#contenue-nomchap').val(data.contenu.nomchap);
            $('#contenue-nomunite').val(data.contenu.nomunite);
            $('#contenue-nombreheures').val(data.contenu.nombreheures);
            $('#contenue-description').val(data.contenu.description);
            $('#contenueEditModal').modal('show');
        });
    });

    $('#contenue-update').click(function (e) {
        e.preventDefault();
        var id = $('#contenue-id').val();
        var formData = new FormData($('#contenue-edit-form')[0]);
        formData.append('_method', 'POST');

        $.ajax({
            url: '/contenus/' + id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#contenueEditModal').modal('hide');
                iziToast.success({
                    message: response.success,
                    position: 'topRight'
                });
                setTimeout(function () {
                    location.reload();
                }, 1000);
            },
            error: function(xhr, status, error) {
                iziToast.error({
                    message: 'An error occurred: ' + error,
                    position: 'topRight'
                });
            }
        });
    });

    // Supprimer un contenu
    $('body').on('click', '#delete-contenue', function (e) {
        e.preventDefault();
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce contenu ?");
        if (confirmation) {
            var id = $(this).data('id');
            $.ajax({
                url: '/contenus/' + id,
                type: 'DELETE',
                success: function(response) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr, status, error) {
                    iziToast.error({
                        message: 'An error occurred: ' + error,
                        position: 'topRight'
                    });
                }
            });
        }
    });
});
