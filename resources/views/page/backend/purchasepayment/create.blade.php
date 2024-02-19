@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Add Purchase Payment</h4>
         </div>
      </div>



        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST" action="{{ route('purchasepayment.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $today }}" required>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;">*</span></label>
                                <input type="time" name="time" placeholder="" value="{{ $timenow }}" required>
                            </div>
                        </div>



                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Supplier<span
                                        style="color: red;">*</span></label>
                                    <select class="form-control js-example-basic-single select ppayment_supplier_id" name="supplier_id" id="supplier_id" required>
                                    <option value="" disabled selected hiddden>Select Supplier</option>
                                    @foreach ($supplier as $suppliers)
                                        <option value="{{ $suppliers->id }}">{{ $suppliers->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-3 col-3" hidden>
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;">*</span></label>
                                        <select class="form-control js-example-basic-single select ppayment_branch_id" name="branch_id" id="branch_id" required>
                                    <option value="" disabled selected hiddden>Select Branch</option>
                                    @foreach ($allbranch as $branches)
                                        <option value="{{ $branches->id }}" selected>{{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Old Balance</label>
                                    <input type="text" name="oldblance" id="oldblance" readonly class="oldblance" style="color:red" >
                                    <input type="hidden" name="payment_purchase_id" id="payment_purchase_id" value=""/>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Discount<span
                                        style="color: red;">*</span></label>
                                    <input type="text" name="purchasepayment_discount" required id="purchasepayment_discount" style="color:black" value="0" class="purchasepayment_discount" placeholder="Enter Discount Amount">
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Total Amount</label>
                                    <input type="text" name="purchasepayment_totalamount" id="purchasepayment_totalamount" style="color:black" readonly value=""class="purchasepayment_totalamount">
                            </div>
                        </div>






                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Payable Amount <span style="color: red;">*</span></label>
                                <input type="text" name="payment_payableamount" id="payment_payableamount"  required style="color:black"  class="payment_payableamount" placeholder="Enter Amount">
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Pending</label>
                                <input type="text" name="payment_pending" id="payment_pending" readonly style="color:#d91617;font-weight: 700;font-size: 17px;background-color:#ffeb00;" class="payment_pending" >
                            </div>
                        </div>

                    </div>

                    <br />



                    <br /><br />

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" />
                        <a href="{{ route('purchasepayment.index') }}" class="btn btn-danger" value="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
