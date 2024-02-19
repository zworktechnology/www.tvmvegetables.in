<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="expenseviewLargeModalLabel{{ $expenceData['unique_key'] }}">Expense
                Details</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="card">
                <div class="card-body">
                    <div class="invoice-box table-height"
                        style="max-width: 1600px;width:100%;padding: 0;font-size: 14px;line-height: 24px;color: #555;">
                           <div class="row">
                              <div class="col-lg-4 col-sm-4 col-12">
                                 <div class="form-group">
                                    <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px; font-weight:700;">Date</label>
                                    <input type="date" name="date" placeholder="" value="{{ $expenceData['date'] }}" readonly>
                                 </div>
                              </div>

                              <div class="col-lg-4 col-sm-4 col-12">
                                 <div class="form-group">
                                    <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px; font-weight:700;">Time</label>
                                    <input type="time" name="time" placeholder="" value="{{ $expenceData['time'] }}" readonly>
                                 </div>
                              </div>
                              <div class="col-lg-4 col-sm-4 col-12">
                                <div class="form-group">
                                   <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px; font-weight:700;">Total</label>
                                   <input type="text" name="time" placeholder="" value="₹. {{ $expenceData['amount'] }}" readonly>
                                </div>
                             </div>



                              {{-- <div class="col-lg-5 col-sm-5 col-12">
                                 <div class="form-group">
                                    <label style="font-size:15px;padding-top: 5px;padding-bottom: 2px;">Branch<span
                                             style="color: red;">*</span></label>
                                    <select class="form-control js-example-basic-single select" name="branch_id" id="branch_id" disabled>
                                          <option value="" disabled selected hiddden>Select Branch</option>
                                          @foreach ($branch as $branches)
                                             <option value="{{ $branches->id }}"@if ($branches->id ===  $expenceData['branch_id'] ) selected='selected' @endif>{{ $branches->shop_name }}</option>
                                          @endforeach
                                    </select>
                                 </div>
                              </div> --}}
                           </div>
                           <div class="row">
                              <div class="col-lg-12 col-12  col-sm-12">
                                <p style="color:black; font-weight:700;">DETAILS</p>
                                <div class="row">
                                    {{-- <div class="col-lg-1 col-sm-1 col-1"></div> --}}
                                    <div class="col-lg-1 col-sm-1 col-1 border">
                                       <span  style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">S.No</span>
                                    </div>
                                    <div class="col-lg-7 col-sm-7 col-7 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Note</span>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-4 border">
                                        <span class=""
                                            style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Amount
                                            </span>
                                    </div>
                                    {{-- <div class="col-lg-1 col-sm-1 col-1"></div> --}}
                                </div>
                                <div class="row">
                                    @foreach ($expenceData['terms'] as $index => $term_arr)
                                        @if ($term_arr['expense_id'] == $expenceData['id'])
                                             {{-- <div class="col-lg-1 col-sm-1 col-1"></div> --}}
                                             <div class="col-lg-1 col-sm-1 col-1 border">
                                                <span  style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">{{ ++$index }}</span>
                                             </div>
                                            <div class="col-lg-7 col-sm-7 col-7 border">
                                                <span
                                                   style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $term_arr['expense_note'] }}</span>
                                            </div>
                                            <div class="col-lg-4 col-sm-4 col-4 border">
                                                <span
                                                   style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">₹. {{ $term_arr['expense_amount'] }}</span>
                                            </div>
                                            {{-- <div class="col-lg-1 col-sm-1 col-1"></div> --}}
                                        @endif
                                    @endforeach
                                </div>
                              </div>
                           </div>

                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
