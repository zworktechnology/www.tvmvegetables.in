@extends('layout.backend.auth')

@section('content')

   <div class="content">
      <div class="page-header">
         <div class="page-title">
            <h4>Update Sales Payment</h4>
         </div>
      </div>



        <div class="card">
            <div class="card-body">
                <form autocomplete="off" method="POST" action="{{ route('salespayment.update', ['unique_key' => $SalespaymentData->unique_key]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Date<span
                                        style="color: red;">*</span></label>
                                <input type="date" name="date" placeholder="" value="{{ $SalespaymentData->date }}" required>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Time<span
                                        style="color: red;">*</span></label>
                                <input type="time" name="time" placeholder="" value="{{ $SalespaymentData->time }}" required>
                            </div>
                        </div>



                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Customer<span
                                        style="color: red;">*</span></label>
                                        <select class="form-control js-example-basic-single select spayment_customer_id" name="customer_id" id="customer_id" disabled>
                                    <option value="" disabled selected hiddden>Select Customer</option>
                                    @foreach ($customer as $customers)
                                        <option value="{{ $customers->id }}"@if ($customers->id === $SalespaymentData->customer_id) selected='selected' @endif>{{ $customers->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="col-lg-3 col-sm-3 col-3" hidden>
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                        style="color: red;">*</span></label>
                                  <select class="form-control js-example-basic-single select spayment_branch_id" name="branch_id" id="branch_id" disabled>
                                    @foreach ($allbranch as $branches)
                                        <option value="{{ $branches->id }}"@if ($branches->id === $SalespaymentData->branch_id) selected='selected' @endif>{{ $branches->shop_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Old Balance</label>
                                <input type="text" name="sales_oldblance" id="sales_oldblance" readonly class="sales_oldblance" value="{{ $SalespaymentData->oldblance }}" style="color:red">
                                    <input type="hidden" name="payment_purchase_id" id="payment_purchase_id" value=""/>
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Discount<span
                                        style="color: red;">*</span></label>
                                        <input type="text" name="salespayment_discount" id="salespayment_discount" required style="color:black" value="{{ $SalespaymentData->salespayment_discount }}" class="salespayment_discount" placeholder="Enter Discount Amount">
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Total Amount</label>
                                    <input type="text" name="salespayment_totalamount" id="salespayment_totalamount" value="{{ $SalespaymentData->salespayment_totalamount }}" style="color:black" readonly class="salespayment_totalamount">
                            </div>
                        </div>






                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Payable Amount <span style="color: red;">*</span></label>
                                <input type="text" name="spayment_payableamount" id="spayment_payableamount" required value="{{ $SalespaymentData->amount }}" style="color:black"  class="spayment_payableamount" placeholder="Enter Amount">
                            </div>
                        </div>

                        <div class="col-lg-3 col-sm-3 col-3">
                            <div class="form-group">
                                <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Pending</label>
                                <input type="text" name="spayment_pending" id="spayment_pending" value="{{ $SalespaymentData->payment_pending }}" readonly style="color:#d91617;font-weight: 700;font-size: 17px;background-color:#ffeb00;" class="spayment_pending" >
                            </div>
                        </div>

                    </div>

                    <br />



                    <br /><br />

                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" />
                        <a href="{{ route('salespayment.index') }}" class="btn btn-danger" value="">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
