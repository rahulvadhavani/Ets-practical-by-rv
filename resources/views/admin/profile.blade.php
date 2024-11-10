@extends('admin.layouts.app')
@section('content')
    @include('admin.components.breadcrumb')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <label id="profile_img">
                                    <div id="preview_div">
                                        <img id="image_preview"
                                            class="image_preview profile-user-img img-fluid img-circle admin_profile"
                                            height=150 width=150
                                            src="{{ $user->profile_img ?? asset('dist/img/default-150x150.png') }}">
                                    </div>
                                </label>
                            </div>
                            <h5 class="text-center">{{ $user->email ?? '-' }}</h5>
                            <ul class="nav nav-pills d-block">
                                <li class="nav-item my-1"><a class="border rounded nav-link active" href="#profile"
                                        data-toggle="tab">Profile</a></li>
                                <li class="nav-item my-1"><a class="border rounded nav-link" href="#password_tab"
                                        data-toggle="tab">Password</a></li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Roles</h3>
                        </div>
                        <div class="card-body">
                            @if ($user->is_super_admin)
                                <ul>
                                    <li>
                                        Super Admin
                                    </li>
                                </ul>
                            @else
                                <ul>
                                    @foreach ($user->roles as $role)
                                        <li>
                                            {{ $role->name }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                        </div>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <form id="profile_frm" form_name="profile_frm" method="post"
                                        action="{{ route('admin.update_profile') }}">
                                        <div class="card-body">
                                            <input type="hidden" name="id" id="id" value="{{ $user->id }}">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>First name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Please enter firstname" id="first_name"
                                                            name="first_name" value="{{ $user->first_name }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Last name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Please enter lastname" id="last_name"
                                                            name="last_name" value="{{ $user->last_name }}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Email <span class="text-danger">*</span></label>
                                                        <input type="read" class="form-control"
                                                            placeholder="Please enter email" value="{{ $user->email }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Contact No.<span class="text-danger"></span></label>
                                                        <input type="number" minlength="10" maxlength="15"
                                                            class="form-control" placeholder="Please enter contact no."
                                                            id="contact_number" name="contact_number"
                                                            value="{{ $user->contact_number }}">
                                                        <label id="contact_number-error" class="error"
                                                            style="display: none"></label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Gender<span class="text-danger">*</span></label>
                                                        <div class="d-flex">
                                                            <div class="custom-control custom-radio mr-2">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="male_radio" name="gender" value="Male"
                                                                    {{ $user->gender == 'Male' ? 'checked' : '' }}>
                                                                <label for="male_radio"
                                                                    class="custom-control-label">Male</label>
                                                            </div>
                                                            <div class="custom-control custom-radio mr-2">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="female_radio" name="gender" value="Female"
                                                                    {{ $user->gender == 'Female' ? 'checked' : '' }}>
                                                                <label for="female_radio"
                                                                    class="custom-control-label">Female</label>
                                                            </div>
                                                            <div class="custom-control custom-radio mr-2">
                                                                <input class="custom-control-input" type="radio"
                                                                    id="other_radio" name="gender" value="Other"
                                                                    {{ $user->gender == 'Other' ? 'checked' : '' }}>
                                                                <label for="other_radio"
                                                                    class="custom-control-label">Other</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Image</label>
                                                        <input type="file" name="image" id="image"
                                                            class="form-control" style="padding: 0.200rem 0.75rem;"
                                                            placeholder="Enter Select Image"
                                                            onchange="load_preview_image(this);"
                                                            accept="image/x-png,image/jpg,image/jpeg">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-indigo btn-flat float-right"
                                                id="profile_frm_btn">Update Profile <span style="display: none"
                                                    id="profile_frm_loader"><i
                                                        class="fa fa-spinner fa-spin"></i></span></button>
                                        </div>
                                    </form>
                                </div>
                                <!-- /.tab-pane -->
                                <div class="tab-pane" id="password_tab">
                                    <form id="password_frm" form_name="password_frm" method="post"
                                        action="{{ route('admin.update_password') }}">
                                        <div class="card-body">
                                            <input type="hidden" name="id" id="id"
                                                value="{{ $user->id }}">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Old Password</label>
                                                        <input type="password" class="form-control"
                                                            placeholder="Please enter old password" id="old_password"
                                                            name="old_password">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" class="form-control"
                                                            placeholder="Please enter password" id="password"
                                                            name="password">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Confirm Password</label>
                                                        <input type="password" class="form-control"
                                                            placeholder="Please enter confirm password"
                                                            id="password_confirmation" name="password_confirmation">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-indigo btn-flat float-right"
                                                id="password_frm_btn">Update Password <span style="display: none"
                                                    id="password_frm_loader"><i
                                                        class="fa fa-spinner fa-spin"></i></span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- /.tab-content -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('script')
    <script src="{{ asset('assets/js/custom/profile.js') }}"></script>
@endpush
