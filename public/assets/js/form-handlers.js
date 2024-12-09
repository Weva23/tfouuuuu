// Common form handling functions
function handleFormSubmission(formId, submitUrl, successCallback, modalId) {
    // Reset warning messages and validation states
    $('.text-danger').text('');
    $('input, select').removeClass('is-invalid');

    // Show loading state on submit button
    const submitButton = $(`#${formId}`).find('button[type="submit"]');
    const originalText = submitButton.text();
    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...');

    // Prepare form data
    let form = $(`#${formId}`)[0];
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
                    message: response.message || 'Opération réussie',
                    position: 'topRight',
                    timeout: 5000
                });

                // Execute success callback if provided
                if (typeof successCallback === 'function') {
                    successCallback(response);
                }

                // Reset form and close modal
                form.reset();
                if (modalId) {
                    $(`#${modalId}`).modal('hide');
                }

                // Reload the page to show updated data
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
            submitButton.prop('disabled', false).text(originalText);
        }
    });
}

// Common form validation function
function validateFormFields(formId, validations) {
    let isValid = true;
    const form = $(`#${formId}`);

    // Reset all warnings
    form.find('.text-danger').text('');
    form.find('.is-invalid').removeClass('is-invalid');

    // Validate each field
    for (let field in validations) {
        const input = form.find(`[name="${field}"]`);
        const warning = form.find(`#${field}-warning`);
        const rules = validations[field];

        // Skip validation if field is optional and empty
        if (!rules.required && (!input.val() || input.val().trim() === '')) {
            continue;
        }

        // Required field validation
        if (rules.required && (!input.val() || input.val().trim() === '')) {
            warning.text('Ce champ est obligatoire');
            input.addClass('is-invalid');
            isValid = false;
            continue;
        }

        // Pattern validation
        if (rules.pattern && !rules.pattern.test(input.val())) {
            warning.text(rules.message || 'Format invalide');
            input.addClass('is-invalid');
            isValid = false;
            continue;
        }

        // Custom validation function
        if (rules.validate && typeof rules.validate === 'function') {
            const validationResult = rules.validate(input.val());
            if (validationResult !== true) {
                warning.text(validationResult);
                input.addClass('is-invalid');
                isValid = false;
            }
        }
    }

    return isValid;
}

// Export functions if using modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        handleFormSubmission,
        validateFormFields
    };
}
