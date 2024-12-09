<!DOCTYPE html>
<html>
<head>
    <title>Laravel AJAX Etudiants Management</title>
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
        max-width: 100%;
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
                            <button type="button" class="btn bg-gradient-dark" data-bs-toggle="modal" data-bs-target="#etudiantAddModal">
                                <i class="material-icons text-sm">add</i>&nbsp;&nbsp;Ajouter 
                            </button>
                            <a href="{{ route('export.etudiants') }}" class="btn btn-success">Exporter </a>
                        </div>
                        <form class="d-flex align-items-center ms-auto">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" name="search" id="search_bar" class="form-control" placeholder="Rechercher...">
                            </div>
                        </form>
                    </div>
                    <div class="me-3 my-3 text-end "></div>
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-0" id="etudiants-table">
                            @include('livewire.example-laravel.etudiants-list', ['etudiants' => $etudiants])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Add Student Modal -->
<div class="modal fade" id="etudiantAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajouter un nouvel étudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="etudiant-add-form" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="image" class="form-label">Image:</label>
                            <input type="file" class="form-control" id="new-etudiant-image" name="image">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nni" class="form-label required">NNI:</label>
                            <input type="number" class="form-control" id="new-etudiant-nni" placeholder="NNI" name="nni">
                            <div class="text-danger" id="nni-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nomprenom" class="form-label required">Nom & Prénom:</label>
                            <input type="text" class="form-control" id="new-etudiant-nomprenom" placeholder="Nom & Prénom" name="nomprenom">
                            <div class="text-danger" id="nomprenom-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="lieunaissance" class="form-label">Lieu de naissance:</label>
                            <input type="text" class="form-control" id="new-etudiant-lieunaissance" placeholder="Lieu de naissance" name="lieunaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="country_id" class="form-label required">Nationalité</label>
                            <select class="form-control" id="new-etudiant-country_id" name="country_id">
                                <option value="">Choisir la nationalité</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->name == 'Mauritania' ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="country_id-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="diplome" class="form-label">Diplôme:</label>
                            <input type="text" class="form-control" id="new-etudiant-diplome" placeholder="Diplôme" name="diplome">
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
                            <input type="date" class="form-control" id="new-etudiant-datenaissance" name="datenaissance">
                            <div class="text-danger" id="datenaissance-warning"></div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="new-etudiant-email" placeholder="email@example.com" name="email">
                            <div class="text-danger" id="email-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="number" class="form-control" id="new-etudiant-phone" placeholder="Portable" name="phone">
                            <div class="text-danger" id="phone-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="number" class="form-control" id="new-etudiant-wtsp" placeholder="WhatsApp" name="wtsp">
                            <div class="text-danger" id="wtsp-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-2">
                            <label for="adress" class="form-label">Adresse:</label>
                            <input type="text" class="form-control" id="new-etudiant-adress" placeholder="Adresse" name="adress">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="dateninscrip" class="form-label">Date d'inscription:</label>
                            <input type="date" class="form-control" id="new-etudiant-dateninscrip" name="dateninscrip">
                            <div class="text-danger" id="dateninscrip-warning"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="add-new-etudiant">Ajouter</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="etudiantEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modifier Etudiant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="etudiant-edit-form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="etudiant-id" name="id">
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="image" class="form-label">Image:</label>
                            <img src="" id="imagePreview" class="imgUpload" alt="">
                            <div>
                                <input type="file" class="form-control" id="image" name="image">
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nni" class="form-label required">NNI:</label>
                            <input type="text" class="form-control" id="etudiant-nni" placeholder="NNI" name="nni">
                            <div class="text-danger" id="edit-nni-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="nomprenom" class="form-label required">Nom & Prénom:</label>
                            <input type="text" class="form-control" id="etudiant-nomprenom" placeholder="Nom & Prénom" name="nomprenom">
                            <div class="text-danger" id="edit-nomprenom-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="lieunaissance" class="form-label">Lieu de naissance:</label>
                            <input type="text" class="form-control" id="etudiant-lieunaissance" placeholder="Lieu de naissance" name="lieunaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="country_id" class="form-label required">Nationalité</label>
                            <select class="form-control" id="etudiant-country_id" name="country_id">
                                <option value="">Choisir la nationalité</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger" id="edit-country_id-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="diplome" class="form-label">Diplôme:</label>
                            <input type="text" class="form-control" id="etudiant-diplome" placeholder="Diplôme" name="diplome">
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label class="form-label required">Genre:</label>
                            <div>
                                <input type="radio" id="edit-male" name="genre" value="Male">
                                <label for="edit-male">Male</label>
                                <input type="radio" id="edit-female" name="genre" value="Female">
                                <label for="edit-female">Female</label>
                            </div>
                            <div class="text-danger" id="edit-genre-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="datenaissance" class="form-label">Date de naissance:</label>
                            <input type="date" class="form-control" id="etudiant-datenaissance" name="datenaissance">
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 col-lg-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="etudiant-email" placeholder="email@example.com" name="email">
                            <div class="text-danger" id="edit-email-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="phone" class="form-label required">Portable:</label>
                            <input type="number" class="form-control" id="etudiant-phone" placeholder="Portable" name="phone">
                            <div class="text-danger" id="edit-phone-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="wtsp" class="form-label">WhatsApp:</label>
                            <input type="number" class="form-control" id="etudiant-wtsp" placeholder="WhatsApp" name="wtsp">
                            <div class="text-danger" id="edit-wtsp-warning"></div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <label for="adress" class="form-label">Adresse:</label>
                            <input type="text" class="form-control" id="etudiant-adress" placeholder="Adresse" name="adress">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="etudiant-update">Modifier</button>
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
    

            $('#search_bar').on('keyup', function(){
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('search') }}",
                    type: "GET",
                    data: {'search': query},
                    success: function(data){
                        $('#etudiants-table').html(data.html);
                    }
                });
            });

    $(document).ready(function() {
        $('body').on('click', '.detail-etudiant', function() { // Change to class selector
            var etudiantId = $(this).data('id');
            fetchEtudiantDetails(etudiantId);
        });
    });


    function fetchEtudiantDetails(etudiantId) {
        $.ajax({
            url: `/etudiants/${etudiantId}/details`,
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
                                        <td>${formation.prix_reel}</td>
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
                    <div class="modal fade" id="etudiantDetailsModal" tabindex="-1" aria-labelledby="etudiantDetailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="etudiantDetailsModalLabel">Détails de l'étudiant</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-2">
                                        <div class="col-md-6"><strong>Nom & Prénom:</strong> ${response.etudiant.nomprenom}</div>
                                        <div class="col-md-6"><strong>Numéro de Téléphone:</strong> ${response.etudiant.phone}</div>
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
                $('#etudiantDetailsModal').remove();
                $('body').append(detailsHtml);
                $('#etudiantDetailsModal').modal('show');
            },
            error: function(xhr, status, error) {
                iziToast.error({ message: 'Erreur lors de la récupération des détails: ' + error, position: 'topRight' });
            }
        });
    }




    // Initialize DataTable
    $(document).ready(function() {
        $('#etudiants-table').DataTable({
            "order": [[4, "asc"]] // Ordre alphabétique sur la colonne "Nom & Prénom"
        });
    });


    function nniExists(nni, id = null) {
        let exists = false;
        $.ajax({
            url: "{{ route('check.nni') }}",
            type: 'GET',
            async: false,
            data: { nni: nni, id: id },
            success: function(response) {
                exists = response.exists;
            }
        });
        return exists;
    }


    function emailExists(email, id = null) {
        let exists = false;
        $.ajax({
            url: "{{ route('checkprof.email') }}",
            type: 'GET',
            async: false,
            data: { email: email, id: id },
            success: function(response) {
                exists = response.exists;
            }
        });
        return exists;
    }

    function phoneExists(phone, etudiantId = null) {
                let exists = false;
                $.ajax({
                    url: "{{ route('checketud.phone') }}",
                    type: 'GET',
                    async: false,
                    data: { phone: phone, etudiant_id: etudiantId },
                    success: function(response) {
                        exists = response.exists;
                    }
                });
                return exists;
            }
            function wtspExists(wtsp, etudiantId = null) {
                let exists = false;
                $.ajax({
                    url: "{{ route('checketud.wtsp') }}",
                    type: 'GET',
                    async: false,
                    data: { wtsp: wtsp, etudiant_id: etudiantId },
                    success: function(response) {
                        exists = response.exists;
                    }
                });
                return exists;
            }

    function validateForm(formId, warnings, id = null) {
        let isValid = true;
        for (let field in warnings) {
            const input = $(formId + ' #' + field);
            const warning = $(warnings[field]);

            if (input.length === 0 && field !== 'genre' && field !== 'new-etudiant-email' && field !== 'etudiant-email' && field !== 'new-etudiant-wtsp' && field !== 'etudiant-wtsp') {
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
            } else if (input.attr('type') === 'radio') {
                if (!$('input[name="' + field + '"]:checked').val()) {
                    warning.text('Ce champ est requis.');
                    isValid = false;
                } else {
                    warning.text('');
                }
            } else if (input.val().trim() === '') {
                if (field !== 'new-etudiant-email' && field !== 'etudiant-email') {
                    warning.text('Ce champ est requis.');
                    isValid = false;
                } else {
                    warning.text('');
                }
            }else if (field === 'datenaissance') {
                const today = new Date().toISOString().split('T')[0]; // Date d'aujourd'hui au format YYYY-MM-DD
                if (input.val() > today) {
                    warning.text('La date de naissance ne peut pas être une date future.');
                    isValid = false;
                } else {
                    warning.text('');
                }
            }  else if (field === 'new-etudiant-phone' || field === 'etudiant-phone') {
                if (!/^\d{8}$/.test(input.val())) {
                    warning.text('Ce champ doit comporter 8 chiffres.');
                    isValid = false;
                } else if (phoneExists(input.val(), id)) {
                            warning.text('Ce numéro de téléphone existe déjà.');
                            isValid = false;
                        } else {
                    warning.text('');
                }
            } else if (field === 'new-etudiant-nni' || field === 'etudiant-nni') {
                if (!/^\d{10}$/.test(input.val())) {
                    warning.text('Ce champ doit comporter 10 chiffres.');
                    isValid = false;
                } else if (nniExists(input.val(), id)) {
                    warning.text('Cet NNI existe déjà.');
                    isValid = false;
                } else {
                    warning.text('');
                }
            } else if (field === 'new-etudiant-email' || field === 'etudiant-email') {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(input.val())) {
                    warning.text('Veuillez entrer une adresse e-mail valide.');
                    isValid = false;
                } else if (emailExists(input.val(), id)) {
                    warning.text('Cet e-mail existe déjà.');
                    isValid = false;
                } else {
                    warning.text('');
                }
            } else if ((field === 'new-etudiant-wtsp' || field === 'etudiant-wtsp') && input.val().trim() !== '') {
                        if (!/^\d+$/.test(input.val())) {
                            warning.text('Veuillez entrer un numéro WhatsApp valide.');
                            isValid = false;
                        } else if (wtspExists(input.val(), id)) {
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

    $('#new-etudiant-phone').on('input', function () {
        $('#new-etudiant-wtsp').val($(this).val());
    });

    

    $(document).ready(function () {
    // Click handler for adding a new student
    $("#add-new-etudiant").click(function (e) {
        e.preventDefault();

        // Reset warning messages and validation states
        $('.text-danger').text('');
        $('input, select').removeClass('is-invalid');

        // Prepare form data
        let form = $('#etudiant-add-form')[0];
        let formData = new FormData(form);

        // AJAX request to store the student
        $.ajax({
            url: "{{ route('etudiant.store') }}", // Replace with the actual route
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });

                    // Dynamically add the student to the table
                    addStudentToTable(response.etudiant);

                    // Reset the form and close the modal
                    $('#etudiant-add-form')[0].reset();
                    $('#etudiantAddModal').modal('hide');
                } else {
                    iziToast.error({
                        message: response.error,
                        position: 'topRight'
                    });
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // Display validation errors
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        let warningElement = $(`#${field.replace('.', '-')}-warning`);
                        let inputElement = $(`#new-etudiant-${field.replace('.', '-')}`);
                        warningElement.text(messages[0]);
                        inputElement.addClass('is-invalid');
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

    // Function to dynamically add a student to the table
    function addStudentToTable(etudiant) {
        let tableBody = $("table tbody");

        let newRow = `
            <tr>
                <td>${etudiant.id}</td>
                <td>
                    ${etudiant.image ? `<img src="/images/${etudiant.image}" alt="Image" width="60px">` : 'N/A'}
                </td>
                <td>${etudiant.nni}</td>
                <td>${etudiant.nomprenom}</td>
                <td>${etudiant.country ? etudiant.country.name : 'N/A'}</td>
                <td>${etudiant.diplome ?? 'N/A'}</td>
                <td>${etudiant.genre}</td>
                <td>${etudiant.lieunaissance ?? 'N/A'}</td>
                <td>${etudiant.adress ?? 'N/A'}</td>
                <td>${etudiant.datenaissance ?? 'N/A'}</td>
                <td>${etudiant.email ?? 'N/A'}</td>
                <td>${etudiant.phone}</td>
                <td>${etudiant.wtsp ?? 'N/A'}</td>
                <td>${etudiant.dateninscrip}</td>
                <td>${etudiant.created_at}</td>
                <td>${etudiant.created_by ? etudiant.created_by.name : 'Non défini'}</td>
                <td>
                    <a href="javascript:void(0)" id="edit-etudiant" data-id="${etudiant.id}" class="btn btn-info">
                        <i class="material-icons opacity-10">border_color</i>
                    </a>
                    <a href="javascript:void(0)" id="delete-etudiant" data-id="${etudiant.id}" class="btn btn-danger">
                        <i class="material-icons opacity-10">delete</i>
                    </a>
                    <a href="javascript:void(0)" class="btn btn-info detail-etudiant" data-id="${etudiant.id}" data-toggle="tooltip" title="Détails de l'Étudiant">
                        <i class="material-icons opacity-10">info</i>
                    </a>
                </td>
            </tr>
        `;

        tableBody.prepend(newRow); // Add new row to the top of the table
    }
});


    function addStudentToTable(etudiant) {
        let newRow = `<tr>
            <td>${etudiant.nni}</td>
            <td>${etudiant.nomprenom}</td>
            <td>${etudiant.genre}</td>
            <td>${etudiant.phone}</td>
            <td>${etudiant.email}</td>
            <td>${etudiant.country_id}</td>
            <td>
                <!-- Add any other necessary fields and actions here -->
            </td>
        </tr>`;

        $('#students-table tbody').append(newRow);
    }

    // Populate the edit modal with the selected student's data
    $('body').on('click', '#edit-etudiant', function () {
        var tr = $(this).closest('tr');
        $('#etudiant-id').val($(this).data('id'));
        $('#etudiant-nni').val(tr.find("td:nth-child(3)").text());
        $('#etudiant-nomprenom').val(tr.find("td:nth-child(4)").text());
        $('#etudiant-country_id').val(tr.find("td:nth-child(5)").data('country-id'));
        $('#etudiant-diplome').val(tr.find("td:nth-child(6)").text());
        var genre = tr.find("td:nth-child(7)").text();
        $('input[name="genre"][value="' + genre + '"]').prop('checked', true);
        $('#etudiant-lieunaissance').val(tr.find("td:nth-child(8)").text());
        $('#etudiant-adress').val(tr.find("td:nth-child(9)").text());
        $('#etudiant-datenaissance').val(tr.find("td:nth-child(10)").text());
        $('#etudiant-email').val(tr.find("td:nth-child(11)").text());
        $('#etudiant-phone').val(tr.find("td:nth-child(12)").text());
        $('#etudiant-wtsp').val(tr.find("td:nth-child(13)").text());
        $('#imagePreview').attr('src', tr.find("td:nth-child(2) img").attr('src'));

        $('#etudiantEditModal').modal('show');
    });

    $('body').on('click', '#etudiant-update', function () {
        var id = $('#etudiant-id').val();
        if (!validateForm('#etudiant-edit-form', {
            'etudiant-nni': '#edit-nni-warning',
            'etudiant-nomprenom': '#edit-nomprenom-warning',
            'etudiant-country_id': '#edit-country_id-warning',
            'etudiant-phone': '#edit-phone-warning',
            'etudiant-email': '#edit-email-warning',
            'genre': '#edit-genre-warning',
            'etudiant-wtsp': '#edit-wtsp-warning'

        }, id)) {
            return;
        }
        
        var formData = new FormData($('#etudiant-edit-form')[0]);
        formData.append('_method', 'PUT');

        $.ajax({
            url: "{{ route('etudiant.update', '') }}/" + id,
            type: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#etudiantEditModal').modal('hide');
                setTimeout(function () {
                    location.reload();
                }, 1000);
                if (response.success) {
                    iziToast.success({
                        message: response.success,
                        position: 'topRight'
                    });
                    updateStudentInTable(response.etudiant);
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
                iziToast.error({
                    message: errorMsg,
                    position: 'topRight'
                });
            }
        });
    });

    // AJAX call for deleting a student with validation and confirmation
    $('body').on('click', '#delete-etudiant', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        
        // Step 1: Check if the student can be deleted
        $.ajax({
            url: "{{ route('etudiant.delete', '') }}/" + id,
            type: 'GET',
            success: function(response) {
                if (response.status === 200 && response.confirm_deletion) {
                    // Ask for confirmation before proceeding with deletion
                    var confirmation = confirm(response.message);
                    if (confirmation) {
                        // Step 2: Confirm deletion
                        $.ajax({
                            url: "{{ route('etudiant.confirm_delete', '') }}/" + id,
                            type: 'DELETE',
                            success: function(response) {
                                setTimeout(function () {
                                    location.reload();
                                }, 1000);
                                if (response.status === 200) {
                                    iziToast.success({
                                        message: response.message,
                                        position: 'topRight'
                                    });
                                    removeStudentFromTable(id);
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


    function addStudentToTable(etudiant) {
        var newRow = `<tr id="student-${etudiant.id}">
            <td>${etudiant.id}</td>
            <td><img src="{{ asset('images/') }}/${etudiant.image}" alt="" width="60px"></td>
            <td>${etudiant.nni}</td>
            <td>${etudiant.nomprenom}</td>
            <td data-country-id="${etudiant.country_id}">${etudiant.country ? etudiant.country.name : 'N/A'}</td>
            <td>${etudiant.diplome}</td>
            <td>${etudiant.genre}</td>
            <td>${etudiant.lieunaissance}</td>
            <td>${etudiant.adress}</td>
            <td>${etudiant.datenaissance}</td>
            <td>${etudiant.dateninscrip}</td>
            <td>${etudiant.email}</td>
            <td>${etudiant.phone}</td>
            <td>${etudiant.wtsp}</td>
            <td>
                <a href="javascript:void(0)" id="edit-etudiant" data-id="${etudiant.id}" class="btn btn-info"><i class="material-icons opacity-10">border_color</i></a>
                <a href="javascript:void(0)" id="delete-etudiant" data-id="${etudiant.id}" class="btn btn-danger"><i class="material-icons opacity-10">delete</i></a>
            </td>
        </tr>`;
        $('table tbody').append(newRow);
    }

    function updateStudentInTable(etudiant) {
        var row = $('#student-' + etudiant.id);
        row.find('td:nth-child(2) img').attr('src', '{{ asset("images") }}/' + etudiant.image);
        row.find('td:nth-child(3)').text(etudiant.nni);
        row.find('td:nth-child(4)').text(etudiant.nomprenom);
        row.find('td:nth-child(5)').text(etudiant.country ? etudiant.country.name : 'N/A').attr('data-country-id', etudiant.country_id);
        row.find('td:nth-child(6)').text(etudiant.diplome);
        row.find('td:nth-child(7)').text(etudiant.genre);
        row.find('td:nth-child(8)').text(etudiant.lieunaissance);
        row.find('td:nth-child(9)').text(etudiant.adress);
        row.find('td:nth-child(10)').text(etudiant.datenaissance);
        row.find('td:nth-child(11)').text(etudiant.email);
        row.find('td:nth-child(12)').text(etudiant.phone);
        row.find('td:nth-child(13)').text(etudiant.wtsp);
    }

    function removeStudentFromTable(id) {
        $('#student-' + id).remove();
    }

    var alertElement = document.querySelector('.fade-out');
    if (alertElement) {
        setTimeout(function() {
            alertElement.style.display = 'none';
        }, 2000);
    }

    function setDefaultDate() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('new-etudiant-dateninscrip').value = today;
    }

    window.onload = setDefaultDate;

});
</script>

</body>
</html>