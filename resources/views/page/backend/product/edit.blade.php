<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="producteditLargeModalLabel{{ $producttdata['unique_key'] }}">Update Customer</h5>
        </div>
        <div class="modal-body">
            <form autocomplete="off" method="POST"
                action="{{ route('product.edit', ['unique_key' => $producttdata['unique_key']]) }}"
                enctype="multipart/form-data">

                @csrf
                <div class="row">
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name</label>
                            <select class="select form-control" name="productlist_id" id="productlist_id">
                                <option value="" disabled selected hiddden>Select Product</option>
                                @foreach ($productlistdata as $productlistdatas)
                                    <option value="{{ $productlistdatas->id }}"@if ($productlistdatas->id === $producttdata['productlist_id']) selected='selected' @endif>{{ $productlistdatas->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Name</label>
                            <select class="select form-control" name="branchid" id="branchid">
                                <option value="" disabled selected hiddden>Select Branch</option>
                                @foreach ($branch_data as $branch_datas)
                                    <option value="{{ $branch_datas->id }}"@if ($branch_datas->id === $producttdata['branchtable_id']) selected='selected' @endif>{{ $branch_datas->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea type="text" name="description" placeholder="Enter Description">{{ $producttdata['description'] }}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Available Stockin Bags</label>
                            <input type="text" name="available_stockin_bag" placeholder="Enter Available Stockin Bags"  value="{{ $producttdata['available_stockin_bag'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Available Stockin Kilograms</label>
                            <input type="text" name="available_stockin_kilograms" placeholder="Enter Available Stockin Kilograms"  value="{{ $producttdata['available_stockin_kilograms'] }}">
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label>Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="0"
                                        {{ $producttdata['status'] == 0 ? 'checked' : '' }}
                                        aria-label="Radio button for following text input" name="status">
                                </div>
                                <input type="text" class="form-control" value="Active">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-sm-6 col-12">
                        <div class="form-group">
                            <label style="color:white">Status</label>
                            <div class="input-group">
                                <div class="input-group-text">
                                    <input class="form-check-input" type="radio" value="1"
                                        {{ $producttdata['status'] == 1 ? 'checked' : '' }}
                                        aria-label="Radio button for following text input" name="status">
                                </div>
                                <input type="text" class="form-control" value="De-Active">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-lg-12 button-align">
                        <button type="submit" class="btn btn-submit me-2">Update</button>
                        <a href="{{ route('stockmanagement.index') }}">
                            <button type="button" class="btn btn-cancel" data-bs-dismiss="modal"
                                aria-label="Close">Cancel</button>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
