<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Professeurs Management</title>
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
        .modal-content {
            max-width: 800px;
            margin: 0 auto;
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
    .modal-content {
        max-width: 800px;
        margin: 0 auto;
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
                <div class="card my-4">
                    <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 d-flex justify-content-between align-items-center">
                        <div>
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#profAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                            </button>
                            <a href="{{ route('export.professeurs') }}" class="btn btn-success">Exporter</a>
                        </div>
                        <form action="/search4" method="get" class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search4" id="search_bar" class="form-control" placeholder="Rechercher..." value="{{ isset($search4) ? $search4 : ''}}">
                            </div>
                            <div id="search_list"></div>
                        </form>
                    </div>
                    <div class="me-3 my-3 text-end"></div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="professors-table">
                            @include('livewire.example-laravel.professeur-list', ['profs' => $profs])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
<div class="modal fade" id="profAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel Professeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="prof-add-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="image" class="form-label">Image:</label>
                            <input type="file" class="form-control" id="new-prof-image" name="image">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nomprenom" class="form-label required">Nom & Prénom:</label>
                            <input type="text" class="form-control" id="new-prof-nomprenom" placeholder="Nom & Prénom" name="nomprenom">
                            <div class="text-danger" id="nomprenom-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="diplome" class="form-label">Diplôme:</label>
                            <input type="text" class="form-control" id="new-prof-diplome" placeholder="Diplôme" name="diplome">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="lieunaissance" class="form-label">Lieu de naissance:</label>
                            <input type="text" class="form-control" id="new-prof-lieunaissance" placeholder="Lieu de naissance" name="lieunaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="country_id" class="form-label required">Nationalité</label>
                            <select class="form-control" id="new-prof-country_id" name="country_id">
                                <option value="">Choisir la nationalité</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->name == 'Mauritania' ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="country_id-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="type_id" class="form-label required">Type de contrat</label>
                            <select class="form-control" id="new-prof-type_id" name="type_id">
                                <option value="">Choisir le type de contrat</option>
                                @foreach ($typeymntprofs as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="type_id-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label required">Genre:</label>
                            <div>
                                <input type="radio" id="male" name="genre" value="Male">
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="genre" value="Female">
                                <label for="female">Female</label>
                            </div>
                            <div class="text-danger" id="genre-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="datenaissance" class="form-label">Date de naissance:</label>
                            <div class="text-danger" id="datenaissance-warning"></div> <!-- Zone pour le message d'erreur -->
                            <input type="date" class="form-control" id="new-prof-datenaissance" placeholder="Date de naissance" name="datenaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="new-prof-email" placeholder="email@example.com" name="email">
                            <div class="text-danger" id="email-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="number" class="form-control" id="new-prof-phone" placeholder="Portable" name="phone">
                            <div class="text-danger" id="phone-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="number" class="form-control" id="new-prof-wtsp" placeholder="WhatsApp" name="wtsp">
                            <div class="text-danger" id="wtsp-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="adress" class="form-label">Adresse:</label>
                            <input type="text" class="form-control" id="new-prof-adress" placeholder="Adresse" name="adress">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="dateninscrip" class="form-label">Date d'inscription:</label>
                            <input type="date" class="form-control" id="new-prof-dateninscrip" placeholder="Date d'inscription" name="dateninscrip">
                            <div class="text-danger" id="dateninscrip-warning"></div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-prof">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="profEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Professeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="prof-edit-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="prof-id" name="id">
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="image" class="form-label">Image:</label>
                            <img src="" id="imagePreview" class="imgUpload" alt="">
                            <div>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nomprenom" class="form-label required">Nom & Prénom:</label>
                            <input type="text" class="form-control" id="prof-nomprenom" placeholder="Nom & Prénom" name="nomprenom">
                            <div class="text-danger" id="edit-nomprenom-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="diplome" class="form-label">Diplôme:</label>
                            <input type="text" class="form-control" id="prof-diplome" placeholder="Diplôme" name="diplome">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="lieunaissance" class="form-label">Lieu de naissance:</label>
                            <input type="text" class="form-control" id="prof-lieunaissance" placeholder="Lieu de naissance" name="lieunaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="country_id" class="form-label required">Nationalité</label>
                            <select class="form-control" id="prof-country_id" name="country_id">
                                <option value="">Choisir la nationalité</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="edit-country_id-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="type_id" class="form-label required">Type de contrat</label>
                            <select class="form-control" id="prof-type_id" name="type_id">
                                <option value="">Choisir le type de contrat</option>
                                @foreach ($typeymntprofs as $type)
                                    <option value="{{ $type->id }}">{{ $type->type }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="edit-type_id-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label required">Genre:</label>
                            <div>
                                <input type="radio" id="male" name="genre" value="Male">
                                <label for="male">Male</label>
                                <input type="radio" id="female" name="genre" value="Female">
                                <label for="female">Female</label>
                            </div>
                            <div class="text-danger" id="edit-genre-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="datenaissance" class="form-label">Date de naissance:</label>
                            <div class="text-danger" id="datenaissance-warning"></div> <!-- Zone pour le message d'erreur -->
                            <input type="date" class="form-control" id="new-prof-datenaissance" placeholder="Date de naissance" name="datenaissance">
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="prof-email" placeholder="email@example.com" name="email">
                            <div class="text-danger" id="edit-email-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="number" class="form-control" id="prof-phone" placeholder="Portable" name="phone">
                            <div class="text-danger" id="edit-phone-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="number" class="form-control" id="prof-wtsp" placeholder="WhatsApp" name="wtsp">
                            <div class="text-danger" id="edit-wtsp-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="adress" class="form-label">Adresse:</label>
                            <input type="text" class="form-control" id="prof-adress" placeholder="Adresse" name="adress">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="prof-update">Modifier</button>
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
            $(function () {
                $('[data-toggle="tooltip"]').tooltip();
            });

            // Recherche AJAX
            $('#search_bar').on('keyup', function(){
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('search4') }}",
                    type: "GET",
                    data: {'search': query},
                    success: function(data){
                        $('#professors-table').html(data.html);
                    }
                });
            });
            $('body').on('click', '.detail-prof', function() { // Change to class selector
                var profId = $(this).data('id');
                fetchProfDetails(profId);
            });

            function fetchProfDetails(profId) {
                $.ajax({
                    url: `/profs/${profId}/details`,
                    type: 'GET',
                    success: function(response) {
                        if (response.error) {
                            iziToast.error({ message: response.error, position: 'topRight' });
                            return;
                        }

                        let formationsHtml = '';
                        if (response.formations.length > 0) {
                            formationsHtml = `
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Formation</th>
                                            <th>Montant à Payer</th>
                                            <th>Montant Payé</th>
                                            <th>Reste à Payer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${response.formations.map(formation => `
                                            <tr>
                                                <td>${formation.nom} <span style="font-style: italic; color: ${formation.statut === 'En cours' ? 'green' : 'red'};">(${formation.statut})</span></td>
                                                <td>${formation.montant_a_paye}</td>
                                                <td>${formation.montant_paye}</td>
                                                <td>${formation.reste_a_payer}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            `;
                        } else {
                            formationsHtml = '<div class="row"><div class="col-md-12"><center>Aucune formation inscrite.</center></div></div>';
                        }

                        var detailsHtml = `
                            <div class="modal fade" id="profDetailsModal" tabindex="-1" aria-labelledby="profDetailsModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="profDetailsModalLabel">Détails duprofesseur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-2">
                                                <div class="col-md-6"><strong>Nom & Prénom:</strong> ${response.prof.nomprenom}</div>
                                                <div class="col-md-6"><strong>Numéro de Téléphone:</strong> ${response.prof.phone}</div>
                                            </div>
                                            <hr>
                                            ${formationsHtml}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Remove any existing modals before appending a new one
                        $('#profDetailsModal').remove();
                        $('body').append(detailsHtml);
                        $('#profDetailsModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        iziToast.error({ message: 'Erreur lors de la récupération des détails: ' + error, position: 'topRight' });
                    }
                });
            }

            function phoneExists(phone, profId = null) {
                let exists = false;
                $.ajax({
                    url: "{{ route('check.phone') }}",
                    type: 'GET',
                    async: false,
                    data: { phone: phone, prof_id: profId },
                    success: function(response) {
                        exists = response.exists;
                    }
                });
                return exists;
            }

            function emailExists(email, profId = null) {
                let exists = false;
                $.ajax({
                    url: "{{ route('check.email') }}",
                    type: 'GET',
                    async: false,
                    data: { email: email, prof_id: profId },
                    success: function(response) {
                        exists = response.exists;
                    }
                });
                return exists;
            }

            function wtspExists(wtsp, profId = null) {
                let exists = false;
                $.ajax({
                    url: "{{ route('check.wtsp') }}",
                    type: 'GET',
                    async: false,
                    data: { wtsp: wtsp, prof_id: profId },
                    success: function(response) {
                        exists = response.exists;
                    }
                });
                return exists;
            }


            function validateForm(formId, warnings, profId = null) {
                let isValid = true;

                for (let field in warnings) {
                    const input = $(formId + ' #' + field);
                    const warning = $(warnings[field]);

                    if (input.length === 0 && field !== 'genre' && field !== 'new-prof-email' && field !== 'prof-email' && field !== 'new-prof-wtsp' && field !== 'prof-wtsp') {
                        console.warn(`No input found with ID: ${field}`);
                        continue;
                    }

                    if (field === 'genre') {
                        if (!$('input[name="genre"]:checked').val()) {
                            warning.text('Ce champ est requis.');
                            isValid = false;
                        } else {
                            warning.text('');
                        }
                    } else if (field === 'datenaissance') {
                        const today = new Date().toISOString().split('T')[0]; // Date d'aujourd'hui au format YYYY-MM-DD
                        if (input.val() > today) {
                            warning.text('La date de naissance ne peut pas être une date future.');
                            isValid = false;
                        } else {
                            warning.text('');
                        }
                    } else if (input.attr('type') === 'radio') {
                        if (!$('input[name="' + field + '"]:checked').val()) {
                            warning.text('Ce champ est requis.');
                            isValid = false;
                        } else {
                            warning.text('');
                        }
                    } else if (input.val().trim() === '' && field !== 'new-prof-email' && field !== 'prof-email' && field !== 'new-prof-wtsp' && field !== 'prof-wtsp') {
                        warning.text('Ce champ est requis.');
                        isValid = false;
                    } else if (field === 'new-prof-phone' || field === 'prof-phone') {
                        if (!/^\d{8}$/.test(input.val())) {
                            warning.text('Ce champ doit comporter 8 chiffres.');
                            isValid = false;
                        } else if (phoneExists(input.val(), profId)) {
                            warning.text('Ce numéro de téléphone existe déjà.');
                            isValid = false;
                        } else {
                            warning.text('');
                        }
                    } else if ((field === 'new-prof-email' || field === 'prof-email') && input.val().trim() !== '') {
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(input.val())) {
                            warning.text('Veuillez entrer une adresse e-mail valide.');
                            isValid = false;
                        } else if (emailExists(input.val(), profId)) {
                            warning.text('Cet e-mail existe déjà.');
                            isValid = false;
                        } else {
                            warning.text('');
                        }
                    } else if ((field === 'new-prof-wtsp' || field === 'prof-wtsp') && input.val().trim() !== '') {
                        if (!/^\d+$/.test(input.val())) {
                            warning.text('Veuillez entrer un numéro WhatsApp valide.');
                            isValid = false;
                        } else if (wtspExists(input.val(), profId)) {
                            warning.text('Ce numéro WhatsApp existe déjà.');
                            isValid = false;
                        } else {
                            warning.text('');
                        }
                    } else {
                        warning.text('');
                    }
                }

                return isValid;
            }

            $('#new-prof-phone').on('input', function () {
                $('#new-prof-wtsp').val($(this).val());
            });

            $("#add-new-prof").click(function (e) {
    e.preventDefault();

    // Reset warning messages
    $('.text-danger').text('');
    $('input, select').removeClass('is-invalid');

    // Prepare form data
    let form = $('#prof-add-form')[0];
    let formData = new FormData(form);

    // AJAX request to store the professor
    $.ajax({
        url: "{{ route('prof.store') }}",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            if (response.success) {
                iziToast.success({
                    message: 'Professeur ajouté avec succès',
                    position: 'topRight'
                });

                // Add the professor to the table dynamically
                addProfessorToTable(response.prof);

                // Reset the form and close the modal
                $('#prof-add-form')[0].reset();
                $('#profAddModal').modal('hide');
            } else {
                iziToast.error({
                    message: response.error,
                    position: 'topRight'
                });
            }
        },
        error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Show validation errors
                $.each(xhr.responseJSON.errors, function (field, messages) {
                    $(`#${field.replace('.', '-')}-warning`).text(messages[0]);
                    $(`#new-prof-${field.replace('.', '-')}`).addClass('is-invalid');
                });
            } else {
                iziToast.error({
                    message: 'Une erreur inattendue est survenue',
                    position: 'topRight'
                });
            }
        }
    });
});

// Add professor dynamically to the table
function addProfessorToTable(prof) {
    let tableBody = $("table tbody");

    let newRow = `
        <tr>
            <td>${prof.id}</td>
            <td>
                ${prof.image ? `<img src="/images/${prof.image}" alt="Image" width="60px">` : 'N/A'}
            </td>
            <td>${prof.nomprenom}</td>
            <td>${prof.country ? prof.country.name : 'N/A'}</td>
            <td>${prof.type ? prof.type.type : 'N/A'}</td>
            <td>${prof.diplome ?? 'N/A'}</td>
            <td>${prof.genre}</td>
            <td>${prof.lieunaissance ?? 'N/A'}</td>
            <td>${prof.adress ?? 'N/A'}</td>
            <td>${prof.datenaissance ?? 'N/A'}</td>
            <td>${prof.email ?? 'N/A'}</td>
            <td>${prof.phone}</td>
            <td>${prof.wtsp ?? 'N/A'}</td>
            <td>${prof.dateninscrip}</td>
            <td>${prof.created_at}</td>
            <td>${prof.created_by ? prof.created_by.name : 'Non défini'}</td>
            <td>
                <a href="javascript:void(0)" id="edit-prof" data-id="${prof.id}" class="btn btn-info">
                    <i class="material-icons opacity-10">border_color</i>
                </a>
                <a href="javascript:void(0)" id="delete-prof" data-id="${prof.id}" class="btn btn-danger">
                    <i class="material-icons opacity-10">delete</i>
                </a>
                <a href="javascript:void(0)" class="btn btn-info detail-prof" data-id="${prof.id}" data-toggle="tooltip" title="Détails du Professeur">
                    <i class="material-icons opacity-10">info</i>
                </a>
            </td>
        </tr>
    `;

    tableBody.prepend(newRow); // Add new row to the top of the table
}

            $('body').on('click', '#edit-prof', function () {
                var tr = $(this).closest('tr');
                $('#prof-id').val($(this).data('id'));
                $('#prof-nomprenom').val(tr.find("td:nth-child(3)").text());
                $('#prof-type_id').val(tr.find("td:nth-child(4)").data('type-id'));
                $('#prof-country_id').val(tr.find("td:nth-child(5)").data('country-id'));
                $('#prof-diplome').val(tr.find("td:nth-child(6)").text());
                var genre = tr.find("td:nth-child(7)").text();
                $('input[name="genre"][value="' + genre + '"]').prop('checked', true);
                $('#prof-lieunaissance').val(tr.find("td:nth-child(8)").text());
                $('#prof-adress').val(tr.find("td:nth-child(9)").text());
                $('#prof-datenaissance').val(tr.find("td:nth-child(10)").text());
                $('#prof-email').val(tr.find("td:nth-child(11)").text());
                $('#prof-phone').val(tr.find("td:nth-child(12)").text());
                $('#prof-wtsp').val(tr.find("td:nth-child(13)").text());
                $('#imagePreview').attr('src', tr.find("td:nth-child(2) img").attr('src'));
                

                $('#profEditModal').modal('show');
            });

            $('body').on('click', '#prof-update', function () {
                var id = $('#prof-id').val();
                if (!validateForm('#prof-edit-form', {
                    'prof-nomprenom': '#edit-nomprenom-warning',
                    'prof-country_id': '#edit-country_id-warning',
                    'prof-type_id': '#edit-type_id-warning',
                    'genre': '#edit-genre-warning',
                    'prof-phone': '#edit-phone-warning',
                    'prof-email': '#edit-email-warning',
                    'prof-wtsp': '#edit-wtsp-warning'
                }, id)) {
                    return;
                }
                var formData = new FormData($('#prof-edit-form')[0]);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('prof.update', '') }}/" + id,
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#profEditModal').modal('hide');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                        if (response.success) {
                            // iziToast.success({
                            //     message: response.success,
                            //     position: 'topRight'
                            // });
                            updateStudentInTable(response.prof);
                        } else {
                            iziToast.error({
                                message: response.error,
                                position: 'topRight'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMsg = '';
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            $.each(xhr.responseJSON.errors, function(field, errors) {
                                $.each(errors, function(index, error) {
                                    errorMsg += error + '<br>';
                                });
                            });
                        } else {
                            errorMsg = 'Une erreur est survenue : ' + error;
                        }
                    }
                });
            });

            function addStudentToTable(prof) {
                var newRow = `<tr id="student-${prof.id}">
                    <td>${prof.id}</td>
                    <td><img src="{{ asset('images/') }}/${prof.image}" alt="" width="60px"></td>
                    <td>${prof.nomprenom}</td>
                    <td data-type-id="${prof.type_id}">${prof.type ? prof.type.type : 'N/A'}</td>
                    <td data-country-id="${prof.country_id}">${prof.country ? prof.country.name : 'N/A'}</td>
                    <td>${prof.diplome}</td>
                    <td>${prof.genre}</td>
                    <td>${prof.lieunaissance}</td>
                    <td>${prof.adress}</td>
                    <td>${prof.datenaissance}</td>
                    <td>${prof.dateninscrip}</td>
                    <td>${prof.email}</td>
                    <td>${prof.phone}</td>
                    <td>${prof.wtsp}</td>
                    <td>
                        <a href="javascript:void(0)" id="edit-prof" data-id="${prof.id}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                        <a href="javascript:void(0)" id="delete-prof" data-id="${prof.id}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
                    </td>
                </tr>`;
                $('table tbody').append(newRow);
            }

            function updateStudentInTable(prof) {
                var row = $('#student-' + prof.id);
                row.find('td:nth-child(2) img').attr('src', '{{ asset("images") }}/' + prof.image);
                row.find('td:nth-child(3)').text(prof.nomprenom);
                row.find('td:nth-child(4)').text(prof.type ? prof.type.type : 'N/A').attr('data-type-id', prof.type_id);
                row.find('td:nth-child(5)').text(prof.country ? prof.country.name : 'N/A').attr('data-country-id', prof.country_id);
                row.find('td:nth-child(6)').text(prof.diplome);
                row.find('td:nth-child(7)').text(prof.genre);
                row.find('td:nth-child(8)').text(prof.lieunaissance);
                row.find('td:nth-child(9)').text(prof.adress);
                row.find('td:nth-child(10)').text(prof.datenaissance);
                row.find('td:nth-child(11)').text(prof.email);
                row.find('td:nth-child(12)').text(prof.phone);
                row.find('td:nth-child(13)').text(prof.wtsp);
            }

            // Delete prof
            $('body').on('click', '#delete-prof', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    
    // Étape 1 : Vérifier si le professeur peut être supprimé
    $.ajax({
        url: "{{ route('prof.delete', '') }}/" + id,
        type: 'GET',
        success: function(response) {
            if (response.status === 200 && response.confirm_deletion) {
                // Demander une confirmation avant de procéder à la suppression
                var confirmation = confirm(response.message);
                if (confirmation) {
                    // Étape 2 : Confirmer la suppression
                    $.ajax({
                        url: "{{ route('prof.confirm_delete', '') }}/" + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            setTimeout(function () {
                                    location.reload();
                                }, 1000);
                            if (response.status === 200) {
                                iziToast.success({
                                    message: response.message,
                                    position: 'topRight'
                                });
                                removeProfFromTable(id);
                            } else {
                                iziToast.error({
                                    message: response.message,
                                    position: 'topRight'
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            iziToast.error({
                                message: 'Une erreur est survenue : ' + error,
                                position: 'topRight'
                            });
                        }
                    });
                }
            } else {
                iziToast.error({
                    message: response.message,
                    position: 'topRight'
                });
            }
        },
        error: function(xhr, status, error) {
            iziToast.error({
                message: 'Une erreur est survenue : ' + error,
                position: 'topRight'
            });
        }
    });
});

function removeProfFromTable(id) {
    $(`#prof-${id}`).remove();
}
            // $('body').on('click', '#delete-prof', function (e) {
            //     e.preventDefault();
            //     var id = $(this).data('id');
            //     var confirmation = confirm("Êtes-vous sûr de vouloir supprimer ce professeur ?");
            //     if (confirmation) {
            //         $.ajax({
            //             url: "{{ route('prof.delete', '') }}/" + id,
            //             type: 'DELETE',
            //             success: function(response) {
            //                 if (response.error) {
            //                     iziToast.error({
            //                         message: response.error,
            //                         position: 'topRight'
            //                     });
            //                 } else {
            //                     iziToast.success({
            //                         message: response.success,
            //                         position: 'topRight'
            //                     });
            //                     removeStudentFromTable(id);
            //                 }
            //             },
            //             error: function(xhr, status, error) {
            //                 iziToast.error({
            //                     message: 'Une erreur est survenue : ' + error,
            //                     position: 'topRight'
            //                 });
            //             }
            //         });
            //     }
            // });

            // function removeStudentFromTable(id) {
            //     $(`#student-${id}`).remove();
            // }

            var alertElement = document.querySelector('.fade-out');
            if (alertElement) {
                setTimeout(function() {
                    alertElement.style.display = 'none';
                }, 2000);
            }

            function setDefaultDate() {
                const today = new Date().toISOString().split('T')[0];
                document.getElementById('new-prof-dateninscrip').value = today;
            }

            window.onload = setDefaultDate;
        });
    </script>
</body>
</html>
