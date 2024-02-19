<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="myExtraLargeModalLabel">Add new branch</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST" action="{{ route('branch.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name <span style="color: red;">*</span></label>
                            <input type="text" name="name" placeholder="Enter Branch name" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Name <span style="color: red;">*</span></label>
                            <input type="text" name="shop_name" placeholder="Enter shop name" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Phone Number <span style="color: red;">*</span></label>
                            <input type="text" name="contact_number" placeholder="Enter shop phone number" required>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Mail Address</label>
                            <input type="text" name="mail_address" placeholder="Enter shop mail address">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Web Address</label>
                            <input type="text" name="web_address" placeholder="Enter shop web address">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Gst Number</label>
                            <input type="text" name="gst_number" placeholder="Enter shop gst number">
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Address <span style="color: red;">*</span></label>
                            <textarea type="text" name="address" placeholder="Enter your shop address" required></textarea>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>logo <span style="color: red;" required>*</span></label>
                            <div class="image-upload">
                                <input type="file" name="logo">
                                <div class="image-uploads">
                                    <img src="{{ asset('assets/backend/img/icons/upload.svg') }}" alt="img">
                                    <h4>Drag and drop a logo to upload</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 button-align">
                        <button type="submit" class="btn btn-submit me-2">Submit</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
