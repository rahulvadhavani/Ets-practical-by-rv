@extends('admin.layouts.app')
@section('content')
    @include('admin.components.breadcrumb')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ $title }} list</h3>
                            <div class="text-right">
                                @can('roles.create')
                                    <button class="btn btn-sm btn-dark float-right  ml-2" id="add_data_modal"><i
                                            class="fa fa-plus" aria-hidden="true"></i> Add {{ $module }}</button>
                                @endcan
                            </div>
                        </div>
                        <form id="form_delete_selected" method="post">
                            @csrf
                            <div class="card-body">
                                <table id="data_table_main" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="module_name" value="{{ $module }}">
        <input type="hidden" id="module_index_url" value="{{ route('admin.roles.index') }}">
    </section>
    @include('admin.role.modal')
@endsection
@push('script')
    <script src="{{ asset('assets/js/custom/role.js') }}"></script>
@endpush
