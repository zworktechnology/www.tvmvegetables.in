<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="customereditLargeModalLabel{{ $customertdata['unique_key'] }}">Update Customer</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST"
                action="{{ route('customer.edit', ['unique_key' => $customertdata['unique_key']]) }}"
                enctype="multipart/form-data">

                @csrf
                <div class="row">
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Enter Customer name"
                                value="{{ $customertdata['name'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="number" name="contact_number" id="customer_contactno" class="customer_contactno form-control" value="{{ $customertdata['contact_number'] }}" onkeyup="customercheck(); return false;" placeholder="Enter Customer Number">
                            <span id="customer_message">Maximum 10 Numbers Allowed</span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" name="email" placeholder="Enter Customer email address"
                                value="{{ $customertdata['email_address'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Name</label>
                            <input type="text" name="shop_name" placeholder="Enter Shop Name"
                                value="{{ $customertdata['shop_name'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Address</label>
                            <input type="text" name="shop_address" placeholder="Enter Shop Address"
                                value="{{ $customertdata['shop_address'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Contact Number</label>
                            <input type="text" name="shop_contact_number" placeholder="Enter Shop Contact Number"
                                value="{{ $customertdata['shop_contact_number'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12" hidden>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="0"
                                        {{ $customertdata['status'] == 0 ? 'checked' : '' }}
                                        aria-label="Radio button for following text input" name="status">
                                </div>
                                <input type="text" class="form-control" value="Active">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12" hidden>
                        <div class="form-group">
                            <label style="color:white">Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="1"
                                        {{ $customertdata['status'] == 1 ? 'checked' : '' }}
                                        aria-label="Radio button for following text input" name="status">
                                </div>
                                <input type="text" class="form-control" value="De-Active">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 button-align">
                        <button type="submit" class="btn btn-submit me-2">Update</button>
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
