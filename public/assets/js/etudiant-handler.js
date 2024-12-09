// Validation rules for student form
const studentValidations = {
    'nomprenom': { required: true },
    'phone': { 
        required: true,
        pattern: /^\d{8}$/,
        message: 'Le numéro de téléphone doit comporter 8 chiffres',
        validate: function(value) {
            return phoneExists(value) ? 'Ce numéro de téléphone existe déjà' : true;
        }
    },
    'email': {
        required: true,
        pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
        message: 'Veuillez entrer une adresse e-mail valide',
        validate: function(value) {
            return emailExists(value) ? 'Cet e-mail existe déjà' : true;
        }
    },
    'nni': {
        required: true,
        pattern: /^\d{10}$/,
        message: 'Le NNI doit comporter 10 chiffres',
        validate: function(value) {
            return nniExists(value) ? 'Cet NNI existe déjà' : true;
        }
    }
};

// Handle student form submission
$(document).ready(function() {
    $("#add-new-etudiant").click(function (e) {
        e.preventDefault();

        // Validate form
        if (!validateFormFields('etudiant-add-form', studentValidations)) {
            return false;
        }

        // Reset warning messages and validation states
        $('.text-danger').text('');
        $('input, select').removeClass('is-invalid');

        // Show loading state
        const submitButton = $(this);
        submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Ajout en cours...');

        // Prepare form data
        let form = $('#etudiant-add-form')[0];
        let formData = new FormData(form);

        // AJAX request
        $.ajax({
            url: submitUrl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    // Show success message
                    iziToast.success({
                        title: 'Succès',
                        message: response.message || 'Étudiant ajouté avec succès',
                        position: 'topRight',
                        timeout: 5000
                    });

                    // Add student to table if provided
                    if (response.etudiant) {
                        addStudentToTable(response.etudiant);
                    }

                    // Reset form and close modal
                    form.reset();
                    $('#etudiantAddModal').modal('hide');

                    // Reload page to show updated data
                    location.reload();
                } else {
                    // Show error message
                    iziToast.error({
                        title: 'Erreur',
                        message: response.error || 'Une erreur est survenue',
                        position: 'topRight',
                        timeout: 5000
                    });
                }
            },
            error: function (xhr) {
                // Handle validation errors
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (field, messages) {
                        let warningElement = $(`#${field.replace('.', '-')}-warning`);
                        let inputElement = $(`[name="${field}"]`);
                        warningElement.text(messages[0]);
                        inputElement.addClass('is-invalid');
                    });

                    iziToast.error({
                        title: 'Erreur de validation',
                        message: 'Veuillez corriger les erreurs dans le formulaire',
                        position: 'topRight',
                        timeout: 5000
                    });
                } else {
                    // Handle other errors
                    iziToast.error({
                        title: 'Erreur',
                        message: xhr.responseJSON?.message || 'Une erreur inattendue est survenue',
                        position: 'topRight',
                        timeout: 5000
                    });
                }
            },
            complete: function () {
                // Reset button state
                submitButton.prop('disabled', false).text('Ajouter');
            }
        });
    });

    // Auto-fill WhatsApp number when phone number changes
    $('#new-etudiant-phone').on('input', function () {
        $('#new-etudiant-wtsp').val($(this).val());
    });
});
