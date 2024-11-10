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
                                            <label class="col-form-label"><b>First Name</b></label><br>
                                            <p id="info_first_name"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>Last Name</b></label><br>
                                            <p id="info_last_name"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>Email</b></label><br>
                                            <p id="info_email"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>Contact</b></label><br>
                                            <p id="info_contact_number"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Address</b></label><br>
                                            <p id="info_address"></p>
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
            <form class="form-horizontal" id="module_form" action="{{ route('admin.suppliers.store') }}"
                name="module_form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>First name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter first name"
                                        id="first_name" name="first_name" value="">
                                    <label id="first_name-error" class="error" style="display: none"
                                        for="first_name"></label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Last name</label>
                                    <input type="text" class="form-control" placeholder="Please enter last name"
                                        id="last_name" name="last_name" value="">
                                    <label id="last_name-error" class="error" style="display: none"
                                        for="last_name"></label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter email"
                                        id="email" name="email" value="">
                                    <label id="email-error" class="error" style="display: none"
                                        for="email"></label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <div class="form-group">
                                    <label>Contact No.</label>
                                    <input type="number" minlength="10" maxlength="15" class="form-control"
                                        placeholder="Please enter contact number" id="contact_number"
                                        name="contact_number" value="">
                                    <label id="contact_number-error" class="error" style="display: none"
                                        for="contact_number"></label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" id="address" class="form-control"></textarea>
                                    <label id="address-error" class="error" style="display: none"
                                        for="address"></label>
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
