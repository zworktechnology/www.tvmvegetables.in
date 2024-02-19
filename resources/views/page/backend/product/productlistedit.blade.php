<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="productlistediteditLargeModalLabel{{ $productlist_array->unique_key }}">Update
                Product Name</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST"
                action="{{ route('productlist.edit', ['unique_key' => $productlist_array->unique_key]) }}"
                enctype="multipart/form-data">

                @csrf
                <div class="row">
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Enter Product Name"
                                value="{{ $productlist_array->name }}">
                        </div>
                    </div>

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
