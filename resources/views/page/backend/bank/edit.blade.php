<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="bankeditLargeModalLabel{{ $bankdata->unique_key }}">Update Bank Details</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST"
                action="{{ route('bank.edit', ['unique_key' => $bankdata->unique_key]) }}" enctype="multipart/form-data">

                @csrf
                <div class="row">
                    <div class="col-lg-12 col-sm-12 col-12">
                        <div class="form-group">
                            <label>Name <span style="color: red;">*</span></label>
                            <input type="text" name="name" id="name" placeholder="Bank Name"
                                value="{{ $bankdata->name }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Details</label>

                            <textarea type="text" name="details" placeholder="Enter your Bank Details">{{ $bankdata->details }}</textarea>
                        </div>
                    </div>



                    <div class="col-lg-12 col-sm-6 col-12" hidden>
                        <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="0"
                                        {{ $bankdata->status == 0 ? 'checked' : '' }}
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
                                        {{ $bankdata->status == 1 ? 'checked' : '' }}
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


    </div><!-- /.modal-content -->
</div>
