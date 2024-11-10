$(document).ready(function () {
    $("#profile_frm").validate({
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
            gender: {
                required: true
            },
            image: {
                accept: "image/jpg,image/jpeg,image/png"
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
            gender: {
                required: "Please select a gender"
            },
            image: {
                accept: 'Only allow image!'
            },
        },
        errorClass: 'error',
        validClass: '',
        highlight: function (element) {
            $(element).addClass('');
        },
        submitHandler: function (form, e) {
            e.preventDefault();
            const formbtn = $('#profile_frm_btn');
            const formloader = $('#profile_frm_loader');
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
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
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

    $("#password_frm").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 6,
            },
            password: {
                required: true,
                minlength: 6,
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            },
        },
        messages: {

            old_password: {
                required: "Please enter old password",
                minlength: "Please enter old password atleast 6 character!"
            },
            password: {
                required: "Please enter password",
                minlength: "Please enter password atleast 6 character!"
            },
            password_confirmation: {
                required: "Please enter confirm password"
            },

        },
        submitHandler: function (form, e) {
            e.preventDefault();
            const formbtn = $('#password_frm_btn');
            const formloader = $('#password_frm_loader');
            $.ajax({
                url: form.action,
                type: "POST",
                data: new FormData(form),
                dataType: 'json',
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrf_token },
                beforeSend: function () {
                    $(formloader).show();
                    $(formbtn).prop('disabled', true);
                },
                success: function (result) {
                    $(formloader).hide();
                    $(formbtn).prop('disabled', false);
                    if (result.status) {
                        $("#password,#password_confirmation,#old_password").val('');
                        toastr.success(result.message);
                    } else {
                        toastr.error(result.message);
                    }
                },
                error: function () {
                    toastr.error('Please Reload Page.');
                    $(formloader).hide();
                    $(formbtn).prop('disabled', false);
                }
            });
            return false;
        }
    });


});