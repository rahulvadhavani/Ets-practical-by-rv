<!-- View user details -->
<div class="modal fade" id="view_user_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ $module }} Detail</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body align-items-center justify-content-center loader" id="modal_loader1"
                    style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>
                <div class="card-body" id="modal_body_part">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body box-profile">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>Name</b></label><br>
                                            <p id="info_name"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>Created At</b></label><br>
                                            <p id="info_created_at"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Description</b></label><br>
                                            <p id="info_description"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Permissions</b></label><br>
                                            <div id="permissions_list">
                                                <div class="table-responsive m-t-15">
                                                    <table class="table table-striped custom-table"
                                                        id="info_role_permission_table">
                                                        <thead>
                                                            <tr>
                                                                <th>Module</th>
                                                                <th class="text-center">Create</th>
                                                                <th class="text-center">Update</th>
                                                                <th class="text-center">Show</th>
                                                                <th class="text-center">Delete</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            @foreach ($permissions as $module => $modulePermissions)
                                                                <tr>
                                                                    <td>
                                                                        {{ $module }}
                                                                    </td>

                                                                    @foreach ($modulePermissions as $permission)
                                                                        <td class="text-center">
                                                                            <div class="custom-control custom-checkbox">
                                                                                <input readonly type="checkbox"
                                                                                    class="custom-control-input permission-checkbox module-{{ $loop->parent->index }}"
                                                                                    id="info_checkbox-{{ $permission->id }}"
                                                                                    value="{{ $permission->id }}"
                                                                                    {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                                <label class="custom-control-label"
                                                                                    for="checkbox-{{ $permission->id }}"></label>
                                                                            </div>
                                                                        </td>
                                                                    @endforeach

                                                                    {{-- Fill remaining cells if less than 5 operations per module --}}
                                                                    @for ($i = count($modulePermissions); $i < 4; $i++)
                                                                        <td class="text-center"></td>
                                                                    @endfor
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" aria-label="Close"
                    class="btn btn-secondary float-right">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- add update modal -->
<div class="modal fade" id="modal-add-update" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal-add-update-title">{{ $module }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form-horizontal" id="module_form" action="{{ route('admin.roles.store') }}" name="module_form"
                novalidate="novalidate">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter name"
                                        id="name" name="name" value="">
                                    <label id="name-error" class="error" style="display: none" for="name"></label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="description" class="form-control"></textarea>
                                    <label id="description-error" class="error" style="display: none"
                                        for="description"></label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Permission</label>
                                    <div>
                                        <div class="table-responsive m-t-15">
                                            <table class="table table-striped custom-table"
                                                id="role_permission_table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <div class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="checkAllPermissions">
                                                                <label class="custom-control-label"
                                                                    for="checkAllPermissions">All</label>
                                                            </div>
                                                        </th>
                                                        <th class="text-center">Create</th>
                                                        <th class="text-center">Update</th>
                                                        <th class="text-center">Show</th>
                                                        <th class="text-center">Delete</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($permissions as $module => $modulePermissions)
                                                        <tr>
                                                            <td>
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input module-checkbox"
                                                                        id="module-{{ $loop->index }}">
                                                                    <label class="custom-control-label"
                                                                        for="module-{{ $loop->index }}">{{ $module }}</label>
                                                                </div>
                                                            </td>

                                                            @foreach ($modulePermissions as $permission)
                                                                <td class="text-center">
                                                                    <div class="custom-control custom-checkbox">
                                                                        <input type="checkbox"
                                                                            class="custom-control-input permission-checkbox module-{{ $loop->parent->index }}"
                                                                            id="checkbox-{{ $permission->id }}"
                                                                            name="permissions[]"
                                                                            value="{{ $permission->id }}"
                                                                            {{ in_array($permission->id, old('permissions', [])) ? 'checked' : '' }}>
                                                                        <label class="custom-control-label"
                                                                            for="checkbox-{{ $permission->id }}"></label>
                                                                    </div>
                                                                </td>
                                                            @endforeach

                                                            {{-- Fill remaining cells if less than 5 operations per module --}}
                                                            @for ($i = count($modulePermissions); $i < 4; $i++)
                                                                <td class="text-center"></td>
                                                            @endfor
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span
                            style="display: none" id="module_form_loader"><i
                                class="fa fa-spinner fa-spin"></i></span></button>
                </div>
            </form>
        </div>
    </div>
</div>
