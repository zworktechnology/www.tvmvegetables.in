<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="customerLargeModalLabel">Add new Customer</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST" action="{{ route('customer.store') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name <span style="color: red;">*</span></label>
                            <input type="text" name="name" placeholder="Enter Customer name">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Contact Number</label>
                            <input type="number" name="contact_number" id="customer_contactno" class="customer_contactno form-control" onkeyup="customercheck(); return false;" placeholder="Enter Customer Number">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="text" name="email" placeholder="Enter Customer email address">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Name</label>
                            <input type="text" name="shop_name" placeholder="Enter Shop Name">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Address</label>
                            <input type="text" name="shop_address" placeholder="Enter Shop Address">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Shop Contact Number</label>
                            <input type="text" name="shop_contact_number" placeholder="Enter Shop Contact Number">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Old Balance <span style="color: red;">*</span></label>
                            <input type="text" name="balance_amount" placeholder="Enter Old Balance">
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
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
