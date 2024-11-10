@extends('admin.layouts.app')
@push('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush
@section('content')
    @include('admin.components.breadcrumb')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form class="form-horizontal" id="edit_users_form"
                            action="{{ route('admin.users.update', $data->id) }}" name="edit_users_form"
                            novalidate="novalidate">
                            @method('PUT')
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h3 class="card-title">{{ $title }}</h3>
                                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span
                                            style="display: none" id="module_form_loader"><i
                                                class="fa fa-spinner fa-spin"></i></span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <input type="hidden" name="id" id="id" value="{{ $data->id }}">
                                <div class="row">
                                    <div class="form-group col-md-12" id="password_note">
                                        <div class="callout callout-info p-2">
                                            <span>
                                                <h5 class="d-inline text-warning"> Note :</h5> Leave <b>Password</b> and
                                                <b>Confirm
                                                    Password</b> empty, if you are not going to
                                                change the password.
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>First name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Please enter firstname"
                                                id="first_name" name="first_name" value="{{ $data->first_name }}">
                                            <label id="first_name-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Last name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Please enter lastname"
                                                id="last_name" name="last_name" value="{{ $data->last_name }}">
                                            <label id="last_name-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" placeholder="Please enter email"
                                                id="email" name="email" value="{{ $data->email }}">
                                            <label id="email-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Contact No.<span class="text-danger"></span></label>
                                            <input type="number" minlength="10" maxlength="15" class="form-control"
                                                placeholder="Please enter contact no." id="contact_number"
                                                name="contact_number" value="{{ $data->contact_number }}">
                                            <label id="contact_number-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="state">State</label>
                                            <div>
                                                <select id="state_id" name="state_id"
                                                    class="form-control select_2_dropdown">
                                                    <option value="">Select State</option>
                                                    @foreach ($states as $state)
                                                        <option value="{{ $state->id }}"
                                                            {{ $data->state_id == $state->id ? 'selected' : '' }}>
                                                            {{ $state->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label id="state_id-error" class="error" style="display: none"
                                                    for="state_id"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <div>
                                                <select id="city_id" name="city_id"
                                                    class="form-control select_2_dropdown">
                                                    <option value="">Select City</option>
                                                    <!-- Dynamically populate cities here if necessary -->
                                                </select>
                                                <label id="city_id-error" class="error" style="display: none"
                                                    for="city_id"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Postcode</label>
                                            <input type="number" minlength="10" maxlength="15" class="form-control"
                                                placeholder="Please enter postcode" id="postcode" name="postcode"
                                                value="{{ $data->postcode }}">
                                            <label id="postcode-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="tags">Hobbies</label>
                                            <input type="text" name="hobbies" class="form-control" id="hobbies"
                                                value="{{ implode(',', $data?->hobbies ?? []) }}" required>
                                            <label id="hobbies-error" class="error" style="display: none">
                                                @error('hobbies')
                                                    {{ $message }}
                                                @enderror
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Gender<span class="text-danger">*</span></label>
                                            <div class="d-flex">
                                                <div class="custom-control custom-radio mr-2">
                                                    <input class="custom-control-input" type="radio" id="male_radio"
                                                        name="gender" value="Male"
                                                        {{ $data->gender == 'Male' ? 'checked' : '' }}>
                                                    <label for="male_radio" class="custom-control-label">Male</label>
                                                </div>
                                                <div class="custom-control custom-radio mr-2">
                                                    <input class="custom-control-input" type="radio" id="female_radio"
                                                        name="gender" value="Female"
                                                        {{ $data->gender == 'Female' ? 'checked' : '' }}>
                                                    <label for="female_radio" class="custom-control-label">Female</label>
                                                </div>
                                                <div class="custom-control custom-radio mr-2">
                                                    <input class="custom-control-input" type="radio" id="other_radio"
                                                        name="gender" value="Other"
                                                        {{ $data->gender == 'Other' ? 'checked' : '' }}>
                                                    <label for="other_radio" class="custom-control-label">Other</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label>Roles <span class="text-danger">*</span></label>
                                            <div class="w-100">
                                                <select id="roles" name="roles[]" class="form-control w-100"
                                                    multiple="multiple">
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->id }}"
                                                            {{ in_array($role->id, $data->roles->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                            {{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                                <label id="role-error" class="error" style="display: none"
                                                    for="roles[]"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input type="file" name="image" id="image" class="form-control"
                                                style="padding: 0.200rem 0.75rem;" placeholder="Enter Select Image"
                                                onchange="load_preview_image(this);"
                                                accept="image/x-png,image/jpg,image/jpeg">
                                            <label id="image-error" class="error" style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div id="preview_div">
                                            <img id="image_preview" width="70" height="70"
                                                class="profile-user-img img-fluid single_img_preview"
                                                src="{{ $data?->profile_img ?? asset('dist/img/default-150x150.png') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-12 row">
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control"
                                                    placeholder="Please enter password" id="password" name="password">
                                                <label id="password-error" class="error" style="display: none"></label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-4">
                                            <div class="form-group">
                                                <label>Confirm Password <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control"
                                                    placeholder="Please enter confirm password" id="password_confirmation"
                                                    name="password_confirmation">
                                                <label id="password_confirmation-error" class="error"
                                                    style="display: none"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Upload Document</label>
                                            <input type="file" class="form-control" id="file_input" name="files[]"
                                                style="padding: 0.200rem 0.75rem;" multiple
                                                accept="image/png, image/jpg, image/jpeg, application/pdf">
                                            <label id="file_input-error" class="error" style="display: none"></label>
                                            <div id="file_preview"
                                                class="align-items-center d-flex justify-content-start mt-3">
                                                @if ($data->document)
                                                    @foreach ($data->document as $file)
                                                        <div
                                                            class="mr-2 file-preview-item position-relative rounded-square">
                                                            @php
                                                                $fileType = pathinfo(
                                                                    $file->file_name,
                                                                    PATHINFO_EXTENSION,
                                                                );
                                                                $isImage = in_array($fileType, [
                                                                    'jpg',
                                                                    'jpeg',
                                                                    'png',
                                                                    'gif',
                                                                ]);
                                                                $thumbnailSrc = $isImage
                                                                    ? $file->file_link
                                                                    : asset('dist/img/pdf.png');
                                                            @endphp
                                                            <input type="hidden">
                                                            <img src="{{ $thumbnailSrc }}" class="file-thumbnail"
                                                                width="100" height="100" />

                                                            <i data-id={{ $file->id }} style="cursor:pointer"
                                                                class="position-absolute remove-file-btn fa-solid fa-circle-xmark text-danger"></i>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="my_city_id" value="{{ $data->city_id }}">
        <input type="hidden" id="moduke_index_url" value="{{ route('admin.users.index') }}">
        <input type="hidden" id="get-cities-url"
            value="{{ route('admin.get.cities', ['stateId' => '__STATE_ID__']) }}">

    </section>
@endsection
@push('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-selection__rendered {
            line-height: 31px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-container .select2-selection--multiple {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e4e4e4;
            border: 1px solid #aaa;
            border-radius: 4px;
            cursor: default;
            float: left;
            margin-right: 4px;
            margin-top: 2px;
            margin-left: 2px;
            padding: 0px 5px;
        }

        select2-container--default .select2-selection--multiple .select2-selection__clear {
            margin-top: 2px;
        }

        .select2-selection__arrow {
            height: 34px !important;
        }

        .remove-file-btn {
            border: none;
            background: transparent;
            top: -6px;
            right: -6px;
            font-size: 20px;
            color: red;
            text-shadow: 1px 1px 6px black;
        }

        .file-thumbnail {
            width: 100px;
            height: 100px;
            border-radius: 5px;
            padding: 5px;
            background: lightgray;
        }
    </style>
@endpush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-tagsinput/1.3.6/jquery.tagsinput.min.js"></script>
    <script src="{{ asset('assets/js/custom/userCreateUpdate.js') }}"></script>
@endpush
