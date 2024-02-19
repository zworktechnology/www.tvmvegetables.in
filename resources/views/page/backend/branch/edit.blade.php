<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="customereditLargeModalLabel{{ $branchdata->unique_key }}">Update Branch</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST"
                action="{{ route('branch.edit', ['unique_key' => $branchdata->unique_key]) }}"
                enctype="multipart/form-branchdata">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name <span style="color: red;">*</span></label>
                            <input type="text" name="name" placeholder="Enter manager name"
                                value="{{ $branchdata->name }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Name <span style="color: red;">*</span></label>
                            <input type="text" name="shop_name" placeholder="Enter shop name"
                                value="{{ $branchdata->shop_name }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Phone Number <span style="color: red;">*</span></label>
                            <input type="text" name="contact_number" placeholder="Enter shop phone number"
                                value="{{ $branchdata->contact_number }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Mail Address</label>
                            <input type="text" name="mail_address" placeholder="Enter shop mail Address"
                                value="{{ $branchdata->mail_address }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Web Address</label>
                            <input type="text" name="web_address" placeholder="Enter shop web address"
                                value="{{ $branchdata->web_address }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Gst Number</label>
                            <input type="text" name="gst_number" placeholder="Enter shop gst number"
                                value="{{ $branchdata->gst_number }}">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Address <span style="color: red;">*</span></label>
                            <textarea type="text" name="address" placeholder="Enter your shop address">{!! $branchdata->address !!}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-1 col-sm-1 col-12">
                        <div class="form-group">
                            <label>Prev</label>
                            <img src="{{ asset('asset/branch/' . $branchdata->logo) }}" class="rounded avatar-lg"
                                alt="{{ $branchdata->name }}">
                        </div>
                    </div>
                    <div class="col-lg-5 col-sm-5 col-12">
                        <div class="form-group">
                            <label>logo <span style="color: red;">*</span></label>
                            <div class="image-upload">
                                <input type="file" name="logo" value="{{ $branchdata->logo }}">
                                <div class="image-uploads">
                                    <img src="{{ asset('assets/backend/img/icons/upload.svg') }}" alt="img">
                                    <h4>Drag and drop a logo to upload</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 button-align">
                        <button type="submit" class="btn btn-submit me-2">Upadte</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
