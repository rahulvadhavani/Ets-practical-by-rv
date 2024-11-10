$(document).ready(function () {
    let module_id = $("#id").val();
    let state_id = $("#state_id").val();
    let city_id = $("#my_city_id").val();
    let default_image = $("#default_image").val();
    $('#hobbies').tagsInput({
        'delimiter': [','],
        tagClass: 'm-0',
    });

    $('#roles').select2({
        placeholder: 'Select roles',
        allowClear: true
    });

    $('.select_2_dropdown').select2({
        placeholder: 'Select',
        allowClear: true
    });

    if (module_id > 0 && state_id != undefined && state_id != null && city_id != undefined && city_id !=
        null) {
        setTimeout(() => {
            $('#state_id').trigger('change');
        }, 200);
    }

    $('#state_id').on('change', function () {

        var stateId = $(this).val();
        $('#city_id').empty().append('<option value="">Select City</option>');

        if (stateId) {
            // Get the URL from the hidden input and replace placeholder
            var url = $('#get-cities-url').val().replace('__STATE_ID__', stateId);

            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {

                    if (response.status) {
                        $('#city_id').empty(); // Clear existing options
                        $.each(response.data, function (id, city) {
                            let selected = id == city_id ? 'selected' : '';
                            $('#city_id').append(
                                `<option ${selected}  value="${id}">${city}</option>`
                            );
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Please Reload Page.');
                }
            });
        }
    });


    // ///
    // $('#file_input').on('change', function(event) {
    //     let files = event.target.files;
    //     let previewContainer = $('#file_preview');
    //     previewContainer.empty(); // Clear previous previews

    //     // Loop through selected files
    //     let pdf_img = $("#pdf_img").val();
    //     $.each(files, function(index, file) {
    //         let reader = new FileReader();
    //         reader.onload = function(e) {
    //             let previewItem = $(
    //                 '<div class="mr-2 file-preview-item position-relative rounded-square"></div>'
    //             );
    //             let fileType = file.type.split('/')[0]; // Check if file is image

    //             if (fileType === 'image') {
    //                 previewItem.append('<img src="' + e.target.result +
    //                     '" class="file-thumbnail" width="100" height="100" />');
    //             } else {
    //                 previewItem.append('<img src="' + pdf_img +
    //                     '" class="file-thumbnail" width="100" height="100" />');
    //             }

    //             // Add remove option with Font Awesome icon
    //             let removeButton = $(
    //                 '<i style="cursor:pointer" class="position-absolute remove-file-btn fa-solid fa-circle-xmark text-danger"></i>'
    //             );

    //             removeButton.on('click', function() {
    //                 previewItem.remove(); // Remove preview item
    //                 // Remove file from input (if needed)
    //                 let fileList = Array.from($('#file_input')[0].files);
    //                 fileList.splice(index,
    //                     1); // Remove file from file input list
    //                 $('#file_input')[0].files = new FileListItems(
    //                     fileList); // Update file input
    //             });

    //             previewItem.append(removeButton);
    //             previewContainer.append(previewItem);
    //         };
    //         reader.readAsDataURL(file);
    //     });
    // });

    function handleFilePreview(inputElement, previewContainer, pdfImgUrl) {
        let files = inputElement.files;
        $(previewContainer).empty(); // Clear previous previews

        $.each(files, function (index, file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                let previewItem = $(
                    '<div class="mr-2 file-preview-item position-relative rounded-square"></div>'
                );
                let fileType = file.type.split('/')[0]; // Check if file is an image

                if (fileType === 'image') {
                    previewItem.append('<img src="' + e.target.result +
                        '" class="file-thumbnail" width="100" height="100" />');
                } else {
                    previewItem.append('<img src="' + pdfImgUrl +
                        '" class="file-thumbnail" width="100" height="100" />');
                }

                // Add remove button with the 'remove-file-btn' class
                let removeButton = $(
                    '<i style="cursor:pointer" class="position-absolute remove-file-btn fa-solid fa-circle-xmark text-danger" data-index="' +
                    index + '"></i>');
                previewItem.append(removeButton);
                $(previewContainer).append(previewItem);
            };
            reader.readAsDataURL(file);
        });
    }

    // Handle file input change
    $('#file_input').on('change', function (event) {
        let pdfImgUrl = $("#pdf_img").val(); // Assuming you have this input element
        handleFilePreview(this, '#file_preview', pdfImgUrl);
    });

    // Handle click event for .remove-file-btn
    $(document).on('click', '.remove-file-btn', function () {
        // Remove the preview item
        $(this).closest('.file-preview-item').remove();

        // Remove the file from input (maintaining original functionality)
        let indexToRemove = $(this).data('index');
        let inputElement = $('#file_input')[0];
        let fileList = Array.from(inputElement.files);
        fileList.splice(indexToRemove, 1);

        // Update the input element with the new file list
        $('#file_input')[0].files = new FileListItems(fileList);
    });

    // Utility function to create a FileList from an array (same as your original approach)
    function FileListItems(files) {
        let b = new ClipboardEvent("").clipboardData || new DataTransfer();
        for (let i = 0, len = files.length; i < len; i++) b.items.add(files[i]);
        return b.files;
    }


    function FileListItems(files) {
        let b = new ClipboardEvent("").clipboardData || new DataTransfer();
        for (let i = 0, len = files.length; i < len; i++) {
            b.items.add(files[i]);
        }
        return b.files;
    }

    // ///

    $("#edit_users_form").validate({
        rules: {
            first_name: {
                required: true,
                pattern: /^[a-zA-Z0-9\s]+$/ // Allow alphanumeric and spaces
            },
            last_name: {
                required: true,
                pattern: /^[a-zA-Z0-9\s]+$/ // Allow alphanumeric and spaces
            },
            email: {
                required: true,
                email: true
            },
            contact_number: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            postcode: {
                digits: true,
                minlength: 5,
                maxlength: 10
            },
            hobbies: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            gender: {
                required: true
            },
            state_id: {
                required: false
            },
            city_id: {
                required: false
            },
            'roles[]': {
                required: true,
                minlength: 1 // Ensures at least one role is selected
            },
            password: {
                required: false,
                minlength: 6
            },
            password_confirmation: {
                required: false,
                equalTo: "#password"
            },
            image: {
                accept: "image/jpg,image/jpeg,image/png"
            },
            'files[]': {
                required: false,
                extension: "jpg|jpeg|png|pdf"
            }
        },
        messages: {
            first_name: {
                required: "Please enter firstname",
                pattern: "Please enter a valid firstname (alphanumeric and spaces allowed)"
            },
            last_name: {
                required: "Please enter lastname",
                pattern: "Please enter a valid lastname (alphanumeric and spaces allowed)"
            },
            name: {
                required: "Please enter name",
                pattern: "Name can only contain letters and spaces"
            },
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address"
            },
            contact_number: {
                digits: "Please enter only digits for contact number",
                minlength: "Contact number must be at least 10 digits",
                maxlength: "Contact number must not exceed 15 digits"
            },
            postcode: {
                digits: "Please enter only digits for postcode",
                minlength: "Postcode must be at least 5 digits",
                maxlength: "Postcode must not exceed 10 digits"
            },
            hobbies: {
                required: "Please enter your hobbies",
                minlength: "Hobbies should contain at least 2 characters",
                maxlength: "Hobbies should not exceed 100 characters"
            },
            gender: {
                required: "Please select a gender"
            },
            state_id: {
                required: "Please select a state"
            },
            city_id: {
                required: "Please select a city"
            },
            'roles[]': {
                required: "Please select at least one role",
                minlength: "Please select at least one role"
            },
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters"
            },
            password_confirmation: {
                required: "Please enter a confirm password",
                equalTo: "Password and confirmation do not match"
            },
            image: {
                accept: "Only image files (jpg, jpeg, png) are allowed"
            },
            'files[]': {
                required: "Please upload at least one document",
                extension: "Only jpg, jpeg, png, and pdf files are allowed"
            }
        },
        errorClass: 'error',
        validClass: '',
        highlight: function (element) {
            $(element).addClass('');
        },
        // unhighlight: function(element) {
        //     $(element).removeClass('is-invalid').addClass(
        //     'is-valid'); // Remove error class and optionally add a valid class
        // },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "roles[]") {
                error.insertAfter(element.closest('.form-group div').find('label')
                    .first());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            let formbtn = $('#module_form_btn');
            let formloader = $('#module_form_loader');
            $.ajax({
                url: form.action,
                type: "POST",
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                beforeSend: function () {
                    formloader.show();
                    formbtn.prop('disabled', true);
                },
                success: function (result) {
                    formloader.hide();
                    formbtn.prop('disabled', false);
                    if (result.status) {
                        $("#password_confirmation, #password, #image ,#file_input").val('');
                        toastr.success(result.message);
                    } else {
                        // Clear previous error messages
                        $('.error').text(''); // Clear any existing error text

                        // Iterate through the errors returned from the server
                        if (result.errors) {
                            let role_error = [];
                            let file_error = [];
                            toastr.error(result.message);
                            // toastr.error(
                            //     "There are some errors in the form. Please check and try again."
                            // );
                            $.each(result.errors, function (field, messages) {

                                if (field == 'roles[]') {
                                    // role_error[] = result.message
                                } else if (field == 'files[]') {
                                    // file_error[] = result.message
                                } else {
                                    // Find the label element associated with the input
                                    const errorLabel = $(
                                        `#${field}-error`);

                                    if (errorLabel.length) {
                                        // Update the error label text
                                        errorLabel.text(messages.join(
                                            ' ')).show();
                                    }
                                }
                            });
                        }
                    }
                },
                error: function () {
                    toastr.error('Please Reload Page.');
                    formloader.hide();
                    formbtn.prop('disabled', false);
                }
            });
            return false;
        }
    });

    $("#users_form").validate({
        rules: {
            first_name: {
                required: true,
                pattern: /^[a-zA-Z0-9\s]+$/ // Allow alphanumeric and spaces
            },
            last_name: {
                required: true,
                pattern: /^[a-zA-Z0-9\s]+$/ // Allow alphanumeric and spaces
            },
            email: {
                required: true,
                email: true
            },
            contact_number: {
                digits: true,
                minlength: 10,
                maxlength: 15
            },
            postcode: {
                digits: true,
                minlength: 5,
                maxlength: 10
            },
            hobbies: {
                required: true,
                minlength: 2,
                maxlength: 100
            },
            gender: {
                required: true
            },
            state_id: {
                required: false
            },
            city_id: {
                required: false
            },
            'roles[]': {
                required: true,
                minlength: 1 // Ensures at least one role is selected
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
            image: {
                accept: "image/jpg,image/jpeg,image/png"
            },
            'files[]': {
                required: false,
                extension: "jpg|jpeg|png|pdf"
            }
        },
        messages: {
            first_name: {
                required: "Please enter firstname",
                pattern: "Please enter a valid firstname (alphanumeric and spaces allowed)"
            },
            last_name: {
                required: "Please enter lastname",
                pattern: "Please enter a valid lastname (alphanumeric and spaces allowed)"
            },
            name: {
                required: "Please enter name",
                pattern: "Name can only contain letters and spaces"
            },
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address"
            },
            contact_number: {
                digits: "Please enter only digits for contact number",
                minlength: "Contact number must be at least 10 digits",
                maxlength: "Contact number must not exceed 15 digits"
            },
            postcode: {
                digits: "Please enter only digits for postcode",
                minlength: "Postcode must be at least 5 digits",
                maxlength: "Postcode must not exceed 10 digits"
            },
            hobbies: {
                required: "Please enter your hobbies",
                minlength: "Hobbies should contain at least 2 characters",
                maxlength: "Hobbies should not exceed 100 characters"
            },
            gender: {
                required: "Please select a gender"
            },
            state_id: {
                required: "Please select a state"
            },
            city_id: {
                required: "Please select a city"
            },
            'roles[]': {
                required: "Please select at least one role",
                minlength: "Please select at least one role"
            },
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters"
            },
            password_confirmation: {
                required: "Please enter a confirm password",
                equalTo: "Password and confirmation do not match"
            },
            image: {
                accept: "Only image files (jpg, jpeg, png) are allowed"
            },
            'files[]': {
                required: "Please upload at least one document",
                extension: "Only jpg, jpeg, png, and pdf files are allowed"
            }
        },
        errorClass: 'error',
        validClass: '',
        highlight: function (element) {
            $(element).addClass('');
        },
        // unhighlight: function(element) {
        //     $(element).removeClass('is-invalid').addClass(
        //     'is-valid'); // Remove error class and optionally add a valid class
        // },
        errorPlacement: function (error, element) {
            if (element.attr("name") === "roles[]") {
                error.insertAfter(element.closest('.form-group div').find('label')
                    .first());
            } else {
                error.insertAfter(element);
            }
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            let formbtn = $('#module_form_btn');
            let formloader = $('#module_form_loader');
            $.ajax({
                url: form.action,
                type: "POST",
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': csrf_token
                },
                beforeSend: function () {
                    formloader.show();
                    formbtn.prop('disabled', true);
                },
                success: function (result) {
                    formloader.hide();
                    formbtn.prop('disabled', false);
                    if (result.status) {
                        $('#users_form')[0].reset();
                        $("#file_preview").html('');
                        $('#image_preview').attr('src', default_image);
                        $(".select_2_dropdown").val("").trigger("change");
                        $('#hobbies_tagsinput .tag').remove();
                        $("#roles").val("").trigger("change");
                        toastr.success(result.message);
                        setTimeout(() => {
                            let moduke_index_url = $("#moduke_index_url").val();
                            window.location.replace(moduke_index_url);
                        }, 1000);
                    } else {
                        // Clear previous error messages
                        $('.error').text(''); // Clear any existing error text

                        // Iterate through the errors returned from the server
                        if (result.errors) {
                            let role_error = [];
                            let file_error = [];
                            toastr.error(result.message);
                            // toastr.error(
                            //     "There are some errors in the form. Please check and try again."
                            // );
                            $.each(result.errors, function (field, messages) {

                                if (field == 'roles[]') {
                                    // role_error[] = result.message
                                } else if (field == 'files[]') {
                                    // file_error[] = result.message
                                } else {
                                    // Find the label element associated with the input
                                    const errorLabel = $(
                                        `#${field}-error`);

                                    if (errorLabel.length) {
                                        // Update the error label text
                                        errorLabel.text(messages.join(
                                            ' ')).show();
                                    }
                                }
                            });
                        }
                    }
                },
                error: function () {
                    toastr.error('Please Reload Page.');
                    formloader.hide();
                    formbtn.prop('disabled', false);
                }
            });
            return false;
        }
    });
});