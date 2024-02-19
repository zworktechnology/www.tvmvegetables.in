<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="uniteditLargeModalLabel{{ $unitdata->unique_key }}">Update Supplier</h5>
        </div>
        <div class="modal-body">
        <form autocomplete="off" method="POST"
                    action="{{ route('unit.edit', ['unique_key' => $unitdata->unique_key]) }}" enctype="multipart/form-data">
                   
                   @csrf
                <div class="row">
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" placeholder="Enter Unit name" value="{{ $unitdata->name }}">
                        </div>
                    </div>
                    
                    
                   
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="0"
                                        {{ $unitdata->status == 0 ? 'checked' : '' }}
                                        aria-label="Radio button for following text input" name="status">
                                </div>
                                <input type="text" class="form-control" value="Active">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-6 col-12">
                        <div class="form-group">
                            <label style="color:white">Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="1"
                                        {{ $unitdata->status == 1 ? 'checked' : '' }}
                                        aria-label="Radio button for following text input" name="status">
                                </div>
                                <input type="text" class="form-control" value="De-Active">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 button-align">
                        <button type="submit" class="btn btn-submit me-2">Update</button>
                        <a href="{{ route('unit.index') }}">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>

        
    </div><!-- /.modal-content -->
</div>

 