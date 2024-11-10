$(document).ready(function () {
    // Get user data
    let module_index_url = $("#module_index_url").val();
    let module_name = $("#module_name").val();
    var table = $('#data_table_main').DataTable({
        processing: true,
        serverSide: true,
        dom: 'Bfrtip',  // Position of the buttons
        buttons: [
            'excel',  // Export to Excel
            'csv',    // Export to CSV
            'pdf'     // Export to PDF
        ],
        ajax: module_index_url,
        "order": [],
        columnDefs: [{
            'targets': [0],
            'searchable': false,
            'orderable': false,
            'className': 'dt-body-center',
            'render': function (data, type, full, meta) {
                return '<input type="checkbox" class="dt_checkbox" name="id[]" value="' +
                    $('<div/>').text(data).html() + '">';
            }
        }],
        select: {
            style: 'multi'
        },
        columns: [{
            data: 'id',
            name: 'id',
            searchable: false
        },
        {
            data: 'first_name',
            name: 'first_name'
        },
        {
            data: 'last_name',
            name: 'last_name'
        },
        {
            data: 'email',
            name: 'email'
        },
        {
            data: 'contact_number',
            name: 'contact_number',
        },
        {
            data: 'created_at_text',
            name: 'created_at'
        },
        {
            data: 'actions',
            name: 'actions',
            searchable: false,
            orderable: false,
        }
        ],
    });

    $("#add_data_modal").on('click', function () {
        var $alertas = $('#module_form');
        $alertas.validate().resetForm();
        $alertas.find('.error').removeClass('error');
        $('#module_form')[0].reset();
        $("#modal-add-update").modal('show');
        $("#id").val(0);
        $("#modal-add-update-title").text(`Add ${module_name}`);
        $("#preview_div").hide();
        $('#project_btn').html(
            'Save <span style="display: none" id="loader"><i class="fa fa-spinner fa-spin"></i></span>'
        );
    });
    // View user
    $(document).on('click', '.module_view_record', function () {
        const id = $(this).data("id");
        const url = $(this).data("url");
        $("#view_user_modal").modal('show');
        $("#view_user_modal .loader").addClass('d-flex');
        $.ajax({
            type: 'GET',
            data: {
                id: id,
                _method: 'SHOW'
            },
            url: `${url}/${id}`,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (response) {
                $("#view_user_modal .loader").removeClass('d-flex');
                if (response.status) {
                    $.each(response.data, function (key, value) {
                        $(`#info_${key}`).text(value);
                        if (key == 'image') {
                            $(`#info_${key}`).attr("src", value);
                        }
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                $("#view_user_modal .loader").removeClass('d-flex');
                toastr.error('Please Reload Page.');
            }
        });

    });

    // delete user
    $(document).on('click', '.module_delete_record', function () {
        const id = $(this).data("id");
        const url = $(this).data("url");
        deleteRecordModule(id, `${url}/${id}`);
    });

    // edit user
    $(document).on('click', '.module_edit_record', function () {
        const id = $(this).data("id");
        const url = $(this).data("url");
        $("#modal-add-update-title").text(`Edit ${module_name}`);
        $("#modal-add-update").modal('show');
        $('#image_preview').attr("");
        $.ajax({
            type: 'GET',
            data: {
                id: id,
                _method: 'SHOW'
            },
            url: `${url}/${id}`,
            headers: {
                'X-CSRF-TOKEN': csrf_token
            },
            success: function (response) {
                if (response.status) {
                    $.each(response.data, function (key, value) {
                        if (key == 'image') {
                            $('#image_preview').attr("src", value);
                        } else {
                            $(`#${key}`).val(value);
                        }
                    });
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error('Please Reload Page.');
            }
        });
        $('#module_form_btn').html(
            'Save <span style="display: none" id="module_form_loader"><i class="fa fa-spinner fa-spin"></i></span>'
        );
    });



    $("#module_form").validate({
        rules: {
            first_name: {
                required: true,
                pattern: /^[a-zA-Z0-9\s]+$/ // Allow alphanumeric and spaces
            },
            last_name: {
                required: false,
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
            address: {
                required: false,
                minlength: 3,
                maxlength: 500
            },

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
            email: {
                required: "Please enter an email",
                email: "Please enter a valid email address"
            },
            contact_number: {
                digits: "Please enter only digits for contact number",
                minlength: "Contact number must be at least 10 digits",
                maxlength: "Contact number must not exceed 15 digits"
            },
            addresss: {
                minlength: "addresss must be at least 3 character.",
                maxlength: "addresss must not exceed 500 character."
            },
        },
        errorClass: 'error',
        validClass: '',
        highlight: function (element) {
            $(element).addClass('');
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            const formbtn = $('#module_form_btn');
            const formloader = $('#module_form_loader');
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
                        $('#module_form')[0].reset();
                        $("#modal-add-update").modal('hide');
                        table.ajax.reload();
                        toastr.success(result.message);
                    } else {
                        $('.error').text(''); // Clear any existing error text
                        if (result.errors) {
                            let role_error = [];
                            let file_error = [];
                            // toastr.error(result.message);
                            toastr.error(
                                "There are some errors in the form. Please check and try again."
                            );
                            $.each(result.errors, function (field, messages) {
                                const errorLabel = $(
                                    `#${field}-error`);

                                if (errorLabel.length) {
                                    errorLabel.text(messages.join(
                                        ' ')).show();
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