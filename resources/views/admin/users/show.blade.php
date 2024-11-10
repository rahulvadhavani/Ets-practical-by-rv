@extends('admin.layouts.app')
@push('style')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush
@section('content')
    @include('admin.components.breadcrumb')
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-4 col-md-12">
                    <div class="card">
                        <div class="card-body box-profile">
                            <div class="text-center pb-3">
                                <img style="width: 150px; height:150px" class="profile-user-img img-fluid img-circle"
                                    src="{{ $data?->profile?->file_link ?? asset('dist/img/default-150x150.png') }}"
                                    alt="User profile picture">
                            </div>

                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item">
                                    <b>Email</b> <a class="float-right">{{ $data->email }}</a>
                                </li>
                                <li class="list-group-item">
                                    <b>Contact No.</b> <a class="float-right">{{ $data->contact_number }}</a>
                                </li>
                            </ul>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Roles</h3>
                        </div>
                        <div class="card-body">
                            <ul>
                                @foreach ($data->roles as $role)
                                    <li>
                                        {{ $role->name }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Hobbies</h3>
                        </div>
                        <div class="card-body">
                            @foreach ($data?->hobbies ?? [] as $hobby)
                                <div>
                                    <div class="badge badge-primary mt-2">
                                        {{ $hobby }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">General Detail</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>First Name:</strong></label>
                                            <span>{{ $data->first_name ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>Last Name:</strong></label>
                                            <span>{{ $data->last_name ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>Gender:</strong></label>
                                            <span>{{ $data->gender ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>Contact No.:</strong></label>
                                            <span>{{ $data?->contact_number ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>Email:</strong></label>
                                            <span>{{ $data?->email ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>City:</strong></label>
                                            <span>{{ $data?->city?->name ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>State:</strong></label>
                                            <span>{{ $data?->state?->name ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>Postcode:</strong></label>
                                            <span>{{ $data->postcode ?? 'NA' }}</span>
                                        </div>
                                        <div class="col-12 col-md-2 col-lg-4 mt-2">
                                            <label><strong>Created On:</strong></label>
                                            <span>{{ $data?->created_at?->format('d-m-Y H:i:s') ?? 'NA' }}</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Documents</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center d-flex justify-content-start mt-3">
                                        @forelse ($data->documents as $key => $file)
                                            @php
                                                $fileType = pathinfo($file->file_name, PATHINFO_EXTENSION);
                                                $isImage = in_array($fileType, ['jpg', 'jpeg', 'png', 'gif']);
                                                $thumbnailSrc = $isImage ? $file->file_link : asset('dist/img/pdf.png');
                                            @endphp
                                            <a href="{{ $file->file_link }}" target="__blank" class="col m-2">
                                                <div class="mr-2 file-preview-item position-relative rounded-square">
                                                    <img src="{{ $thumbnailSrc }}" class="file-thumbnail" width="200"
                                                        height="200" />
                                                    {{-- <small class="doc_img_title">{{$file->file_name}}</small> --}}

                                                </div>
                                            </a>

                                        @empty
                                            <h4 class="text-secondary text-center">No Data Found</h4>
                                        @endforelse
                                    </div>

                                </div>
                            </div>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
@endpush
@push('script')
@endpush
