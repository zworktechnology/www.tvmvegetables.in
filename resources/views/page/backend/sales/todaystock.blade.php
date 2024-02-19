<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="todaystockLargeModalLabel"><span style="color:green">{{ $allbranches->shop_name }} BRANCH</span></h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="card">
               <div class="card-body">
                  <div class="row">
                     <div class="col-lg-12 col-sm-12 col-12">
                        <div class="row" style="background-color: lightgray">
                              <div class="col-lg-2 col-sm-2 col-2 border">
                                 <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Date</span>
                              </div>
                              <div class="col-lg-8 col-sm-8 col-8 border">
                                 <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Product Name</span>
                              </div>
                              
                              <div class="col-lg-2 col-sm-2 col-2 border">
                                 <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Sales</span>
                              </div>
                        </div>
                        <div class="row" style="background-color: lightgray">
                            <div class="col-lg-2 col-sm-2 col-2 border">
                            </div>
                            <div class="col-lg-8 col-sm-8 col-8 border">
                            </div>
                            <div class="col-lg-1 col-sm-2 col-2 border">
                               <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Bag</span>
                            </div>
                            <div class="col-lg-1 col-sm-2 col-2 border">
                                <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">Kg</span>
                             </div>
                      </div>
                      @foreach ($PSTodayStockArr as $keydata => $p_datas)
                      @if ($allbranches->id == $p_datas['branch_id'])


                        <div class="row">
                        
                              <div class="col-lg-2 col-sm-2 col-2 border">
                                 <span class=""style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ date('d-m-Y', strtotime($p_datas['today'])) }}</span>
                              </div>

                             
                             

                            
                             <div class="col-lg-8 col-sm-8 col-8 border">
                                 <span class="" style="text-transform: uppercase; vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">{{$p_datas['product_name']}}</span>
                              </div>
                             
                              <div class="col-lg-1 col-sm-1 col-1 border">
                                 <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">{{$p_datas['getSalebagcount']}}</span>
                              </div>
                             <div class="col-lg-1 col-sm-1 col-1 border">
                                <span class="" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 700;line-height: 35px; ">{{$p_datas['getSalekgcount']}}</span>
                             </div>
                        </div>
                        @endif
                        @endforeach
                        
                     </div>
                  </div>

               </div>
            </div>
         </div>


        
   </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->




