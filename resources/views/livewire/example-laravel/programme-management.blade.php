
<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Programmes Management</title>
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
        #programmeContentContainer {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div id="programmeContentContainer" style="display:none;">
            <!-- <button onclick="$('#programmeContentContainer').hide();" class="btn btn-secondary">Fermer</button> -->
            <div id="programmeContents"></div>
        </div>
        <div class="row">
            <div class="col-12">
                @if (session('status'))
                <div class="alert alert-success fade-out">
                    {{ session('status')}}
                </div>
                @endif
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#programmeAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                            </button>
                            <a href="{{ route('programmes.export') }}" class="btn btn-success">Exporter </a>
                        </div>
                        <form action="/search1" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search1" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search1) ? $search1 : ''}}">
                            </div>
                            <div id="search_list"></div>
                        </form>
                    </div>
                    <!-- <div class="me-3 my-3 text-end"></div> -->
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="programmes-table">
                            @include('livewire.example-laravel.programmes-list', ['programmes' => $programmes])
                        </div>
                    </div>
                </div>

    <!-- Modal Ajouter programme -->
    <div class="modal fade" id="programmeAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter une nouvelle Programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="programme-add-form">
                    @csrf
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="code" class="form-label required">Code:</label>
                            <input type="text" class="form-control" id="new-programme-code" placeholder="Code du programme" name="code">
                            <div class="text-danger" id="code-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label required">Nom :</label>
                            <input type="text" class="form-control" id="new-programme-nom" placeholder="Nom du programme" name="nom">
                            <div class="text-danger" id="nom-warning"></div>
                        </div>
                    </div>
                    <br>
                    
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-programme">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>







    <!-- Modal Modifier programme -->
    <div class="modal fade" id="programmeEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Programme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="programme-edit-form">
                    @csrf
                    <input type="hidden" id="programme-id" name="id">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <label for="code" class="form-label required">Code:</label>
                            <input type="text" class="form-control" id="programme-code" placeholder="Code du programme" name="code" required>
                            <div class="text-danger" id="edit-code-warning"></div>
                        </div>
                        <div class="col-md-6">
                            <label for="nom" class="form-label required">Nom:</label>
                            <input type="text" class="form-control" id="programme-nom" placeholder="Nom du programme" name="nom" required>
                            <div class="text-danger" id="edit-nom-warning"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="programme-update">Modifier</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>









    <script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#search_bar').on('keyup', function(){
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('search1') }}",
                    type: "GET",
                    data: {'search1': query},
                    success: function(data){
                        $('#programmes-table').html(data.html);
                    }
                });
            });

        window.hideContents = function() {
            $('#programmeContentContainer').hide();
            $('html, body').animate({ scrollTop: 0 }, 'slow');
        };

        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
       
        // Ajouter une programme
        $("#add-new-programme").click(function(e) {
    e.preventDefault();

    // Clear any previous validation errors
    $('.is-invalid').removeClass('is-invalid');
    $('.text-danger').text('');

    // Validate required fields
    let isValid = true;

    const codeField = $('#new-programme-code');
    const nomField = $('#new-programme-nom');
    const codeWarning = $('#code-warning');
    const nomWarning = $('#nom-warning');

    if (codeField.val().trim() === '') {
        isValid = false;
        codeField.addClass('is-invalid');
        codeWarning.text('Ce champ est requis.');
    }

    if (nomField.val().trim() === '') {
        isValid = false;
        nomField.addClass('is-invalid');
        nomWarning.text('Ce champ est requis.');
    }

    if (!isValid) {
        return;
    }

    // Prepare form data
    let form = $('#programme-add-form')[0];
    let data = new FormData(form);

    // AJAX request to store the programme
    $.ajax({
        url: "{{ route('programme.store') }}", // Update the route if necessary
        type: "POST",
        data: data,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.error) {
                // Handle validation errors
                if (response.error === 'Le code de programme existe déjà.') {
                    codeField.addClass('is-invalid');
                    codeWarning.text(response.error);
                } else {
                    iziToast.error({
                        title: 'Erreur',
                        message: response.error,
                        position: 'topRight'
                    });
                }
            } else {
                // Success message
                iziToast.success({
                    title: 'Succès',
                    message: response.success,
                    position: 'topRight'
                });
                $('#programmeAddModal').modal('hide');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            // Handle HTTP errors
            if (xhr.status === 409) { // Conflict
                codeField.addClass('is-invalid');
                codeWarning.text(xhr.responseJSON.error);
            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Validation errors
                let errors = xhr.responseJSON.errors;
                for (let field in errors) {
                    let warningField = `#${field}-warning`;
                    $(`#new-programme-${field}`).addClass('is-invalid');
                    $(warningField).text(errors[field][0]);
                }
            } else {
                iziToast.error({
                    title: 'Erreur',
                    message: `Une erreur est survenue : ${error}`,
                    position: 'topRight'
                });
            }
        }
    });
});

        $('body').on('click', '#edit-programme', function () {
    var id = $(this).data('id'); // Assurez-vous que data-id est bien défini dans le HTML
    $.get('/programmes/' + id, function (data) {
        if (data.programme) { // Vérifie si le programme existe
            $('#programme-id').val(data.programme.id);
            $('#programme-code').val(data.programme.code);
            $('#programme-nom').val(data.programme.nom);
            $('#programmeEditModal').modal('show'); // Affiche le formulaire de modification
        } else {
            iziToast.error({
                title: 'Erreur',
                message: 'Programme introuvable.',
                position: 'topRight'
            });
        }
    }).fail(function () {
        iziToast.error({
            title: 'Erreur',
            message: 'Impossible de récupérer les données du programme.',
            position: 'topRight'
        });
    });
});

$('#programme-update').click(function (e) {
    e.preventDefault();
    let id = $('#programme-id').val();
    let form = $('#programme-edit-form')[0];
    let data = new FormData(form);
    data.append('_method', 'PUT');

    $.ajax({
        url: '/programmes/' + id,
        type: 'POST',
        data: data,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.status === 404) {
                iziToast.error({
                    title: 'Erreur',
                    message: response.message,
                    position: 'topRight'
                });
            } else if (response.status === 200) {
                iziToast.success({
                    title: 'Succès',
                    message: response.message,
                    position: 'topRight'
                });
                $('#programmeEditModal').modal('hide');
                setTimeout(function () {
                    location.reload();
                }, 1000);
            }
        },
        error: function () {
            iziToast.error({
                title: 'Erreur',
                message: 'Une erreur est survenue lors de la mise à jour.',
                position: 'topRight'
            });
        }
    });
});


        // Supprimer une programme
// Supprimer une programme
$('body').on('click', '#delete-programme', function () {
    var id = $(this).data('id'); // Récupérer l'ID du programme

    // Demander une confirmation
    if (confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')) {
        $.ajax({
            url: '/programmes/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content') // Ajoute le token CSRF
            },
            success: function (response) {
                if (response.status === 200) {
                    iziToast.success({
                        title: 'Succès',
                        message: response.message,
                        position: 'topRight'
                    });
                    setTimeout(function () {
                        location.reload(); // Recharger la page
                    }, 1000);
                } else {
                    iziToast.error({
                        title: 'Erreur',
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            error: function () {
                iziToast.error({
                    title: 'Erreur',
                    message: 'Une erreur est survenue lors de la suppression.',
                    position: 'topRight'
                });
            }
        });
    }
});





      
        window.setProgrammeId = function(programmeId) {
            $('#programme-id-contenu').val(programmeId);
        };

});


    </script>
</body>
</html>
