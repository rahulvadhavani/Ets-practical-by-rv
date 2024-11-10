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
                                @can('customers.create')
                                    <button class="btn btn-sm btn-dark float-right  ml-2" id="add_data_modal"><i
                                            class="fa fa-plus" aria-hidden="true"></i> Add {{ $module }}</button>
                                @endcan
                                @can('customers.delete')
                                    <button id="delete_selected" class="btn btn-sm btn-danger float-right" disabled>Delete
                                        Selected</button>
                                @endcan
                            </div>
                        </div>
                        <form id="form_delete_selected" method="post">
                            @csrf
                            <div class="card-body">
                                <table id="data_table_main" class="table table-bordered table-striped w-100">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><input type="checkbox" name="select_all" value="1"
                                                    id="delete-select-checkbox"></th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Email</th>
                                            <th>Contact</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </form>
                        <input type="hidden" id="delete_selected_url"
                            value="{{ route('admin.customers.delete-selected') }}">
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="delete_selected_url" value="{{ route('admin.customers.delete-selected') }}">
        <input type="hidden" id="module_name" value="{{ $module }}">
        <input type="hidden" id="module_index_url" value="{{ route('admin.customers.index') }}">
    </section>
    @include('admin.customer.modal')
@endsection
@push('script')
    <script src="{{ asset('assets/js/custom/customer.js') }}"></script>
    <script>
        
    </script>
@endpush
