<div class="modal-dialog modal-l">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="branch_viewLargeModalLabel{{ $allbranches->id }}">{{ $allbranches->name }} - Branch</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
               <a class="nav-link active" id="home-tab{{ $allbranches->id }}" data-bs-toggle="tab" href="#home{{ $allbranches->id }}" role="tab" aria-controls="home" aria-selected="true">Bag</a>
            </li>
            <li class="nav-item" role="presentation">
               <a class="nav-link" id="profile-tab{{ $allbranches->id }}" data-bs-toggle="tab" href="#profile{{ $allbranches->id }}" role="tab" aria-controls="profile" aria-selected="false">Kg</a>
            </li>
         </ul>
         <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="home{{ $allbranches->id }}" role="tabpanel" aria-labelledby="home-tab{{ $allbranches->id }}">



               <div class="card" style="overflow: auto;">
                  <div class="card-body">
                    <div class="row">

                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:black;font-weight: 600;line-height: 35px; text-align:center">S. No</div>
                        <div class="col-lg-6 col-sm-6 col-6 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:black;font-weight: 600;line-height: 35px; text-align:center">Product</div>
                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:black;font-weight: 600;line-height: 35px; text-align:center">Bag</div>
                    </div>
                    @php
                        $keydata = 0;
                    @endphp

                    @foreach ($bag_array as $keydata => $bag_array_data)
                     @if ($bag_array_data['branch_id'] == $allbranches->id)


                     <div class="row">
                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px;text-align:center ">{{ ++$keydata }}</div>
                        <div class="col-lg-6 col-sm-6 col-6 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $bag_array_data['product_name'] }}</div>
                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px;text-align:center ">{{ $bag_array_data['bag'] }} Bag</div>
                    </div>

                        @endif
                        @endforeach
                  </div>
               </div>




            </div>
            <div class="tab-pane fade" id="profile{{ $allbranches->id }}" role="tabpanel" aria-labelledby="profile-tab{{ $allbranches->id }}">

               <div class="card" style="overflow: auto;">
                  <div class="card-body">
                    <div class="row">

                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:black;font-weight: 600;line-height: 35px; text-align:center">S. No</div>
                        <div class="col-lg-6 col-sm-6 col-6 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:black;font-weight: 600;line-height: 35px; text-align:center">Product</div>
                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 16px;color:black;font-weight: 600;line-height: 35px; text-align:center">Kg</div>
                    </div>
                    @php
                        $index = 0;
                    @endphp

                    @foreach ($kg_array as $index => $kg_array_data)
                     @if ($kg_array_data['branch_id'] == $allbranches->id)


                     <div class="row">
                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px;text-align:center ">{{ ++$index }}</div>
                        <div class="col-lg-6 col-sm-6 col-6 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px; ">{{ $kg_array_data['product_name'] }}</div>
                        <div class="col-lg-3 col-sm-3 col-3 border" style="vertical-align: inherit;vertical-align: inherit;font-size: 14px;color:#000;font-weight: 400;line-height: 35px;text-align:center ">{{ $kg_array_data['kg'] }} Kg</div>
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
