@extends('admin.layouts.app')
@section('content')
    @include('admin.components.breadcrumb')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }} lists</h3>
                            <div class="text-right">
                                @can('users.create')
                                    <a class="btn btn-sm btn-dark float-right  ml-2" href="{{ route('admin.users.create') }}"><i
                                            class="fa fa-plus" aria-hidden="true"></i> Add {{ $module }}</a>
                                @endcan
                                @can('users.delete')
                                    <button id="delete_selected" class="btn btn-sm btn-danger float-right" disabled>Delete
                                        Selected</button>
                                @endcan
                            </div>
                        </div>
                        <form id="form_delete_selected" method="post">
                            @csrf
                            <div class="card-body table-responsive">
                                <table style="width: 100%" id="data_table_main"
                                    class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><input type="checkbox" name="select_all" value="1"
                                                    id="delete-select-checkbox"></th>
                                            <th>Image</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Gender</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                        <input type="hidden" id="delete_selected_url" value="{{ route('admin.users.delete-selected') }}">
                        <input type="hidden" id="moduke_index_url" value="{{ route('admin.users.index') }}">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
@endpush
@push('script')
    <script src="{{ asset('assets/js/custom/users.js') }}"></script>
@endpush
