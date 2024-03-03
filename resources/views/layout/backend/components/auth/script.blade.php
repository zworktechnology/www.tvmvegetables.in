<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

<script src="{{ asset('assets/backend/js/jquery-3.6.0.min.js') }}"></script>

<script src="{{ asset('assets/backend/js/feather.min.js') }}"></script>

<script src="{{ asset('assets/backend/js/jquery.slimscroll.min.js') }}"></script>

<script src="{{ asset('assets/backend/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('assets/backend/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('assets/backend/plugins/apexchart/apexcharts.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/apexchart/chart-data.js') }}"></script>

<script src="{{ asset('assets/backend/plugins/sweetalert/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/sweetalert/sweetalerts.min.js') }}"></script>

<script src="{{ asset('assets/backend/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/backend/plugins/toastr/toastr.js') }}"></script>

<script src="{{ asset('assets/backend/js/script.js') }}"></script>

<script src="{{ asset('assets/backend/js/custom/purchase_create.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });
</script>

<script>

$(".purchaseclose").click(function() {
    window.location.reload();
});


// PURCHASE

   var j = 1;
   var i = 1;
   var m = 1;
   var n = 2;
   var o = 3;
   var p = 4;
    $(document).ready(function() {
        $(document).on('click', '.addproductfields', function() {
         ++i;
                $(".product_fields").append(
                    '<tr>' +
                    '<td class=""><input type="hidden"id="purchase_detail_id"name="purchase_detail_id[]" />' +
                    '<select class="form-control js-example-basic-single product_id select"name="product_id[]" id="product_id' + i + '"required>' +
                    '<option value="" selected hidden class="text-muted">Select Product</option></select>' +
                    '</td>' +
                    '<td><select class=" form-control bagorkg" name="bagorkg[]" id="bagorkg1"required>' +
                    '<option value="" selected hidden class="text-muted">Select</option>' +
                    '<option value="bag">Bag</option><option value="kg">Kg</option>' +
                    '</select></td>' +
                    '<td><input type="text" class="form-control count" id="count" name="count[]" placeholder="count" value="" required /></td>' +
                    '<td><button style="width: 35px;margin-right:5px;"class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary addproductfields"type="button" id="" value="Add">+</button><button style="width: 35px;" class="text-white py-1 font-medium rounded-lg text-sm  text-center btn btn-danger remove-tr" type="button" >-</button></td>' +
                    '</tr>'
                );

                $.ajax({
                    url: '/getProducts/',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        //console.log(response['data']);
                        var len = response['data'].length;

                        var selectedValues = new Array();

                        if (len > 0) {
                            for (var i = 0; i < len; i++) {

                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='" + id + "'>" + name +
                                        "</option>";
                                    selectedValues.push(option);
                            }
                        }
                        ++j;
                        $('#product_id' + j).append(selectedValues);
                        //add_count.push(Object.keys(selectedValues).length);
                    }
                });
        });

        $(document).on('click', '.addextranotefields', function() {
            $(".extracost_tr").append(
                    '<tr>' +
                    '<td style="font-size:15px;color: black;" class="text-end">Extra Cost<span style="color: red;">*</span></td>' +
                    '<td colspan="4"><select class=" form-control bagorkg" name="extracost_note[]" id="extracost_note" required><option value="" selected hidden class="text-muted">Select</option><option value="Rent">Rent</option><option value="WAGE">Wage</option><option value="Gate">Gate</option><option value="Advance">Advance</option></select></td>' +
                    '<td><input type="text" class="form-control extracost" id="extracost" placeholder="Extra Cost" name="extracost[]" value="" /></td>' +
                    '<td><button style="width: 35px;margin-right:5px;"class="py-1 addextranotefields text-white font-medium rounded-lg text-sm  text-center btn btn-primary"type="button" id="" value="Add">+</button>' +
                    '<button style="width: 35px;"class="py-1 text-white remove-extratr font-medium rounded-lg text-sm  text-center btn btn-danger" type="button" id="" value="Add">-</button></td>' +
                    '</tr>'
                );
        });

        $(document).on('click', '.remove-extratr', function() {
            $(this).parents('tr').remove();
        });




        $(document).on('click', '.addpurchaseorderfields', function() {
         ++i;
                $(".purchaseorder_fields").append(
                    '<tr>' +
                    '<td class=""><input type="hidden"id="purchase_detail_id"name="purchase_detail_id[]" />' +
                    '<select class="form-control js-example-basic-single product_id select" name="product_id[]" id="product_id' + i + '"required>' +
                    '<option value="" selected hidden class="text-muted">Select Product</option></select>' +
                    '</td>' +
                    '<td><select class=" form-control bagorkg" name="bagorkg[]" id="bagorkg1"required>' +
                    '<option value="" selected hidden class="text-muted">Select</option>' +
                    '<option value="bag">Bag</option><option value="kg">Kg</option>' +
                    '</select></td>' +
                    '<td><input type="text" class="form-control count" id="count" name="count[]" placeholder="count" value="" required /></td>' +
                    '<td><input type="text" class="form-control note" id="note" name="note[]" placeholder="note" value="" /></td>' +
                    '<td><input type="text" class="form-control price_per_kg" id="price_per_kg" name="price_per_kg[]" placeholder="Price Per count" required /></td>' +
                    '<td></td>' +
                    '<td class="text-end"><input type="text" class="form-control total_price" id="total_price" style="background-color: #e9ecef;" readonly name="total_price[]" placeholder=""required /></td>' +
                    '<td><button style="width: 35px;margin-right:5px;"class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary addpurchaseorderfields" type="button" id="" value="Add">+</button>' +
                    '<button style="width: 35px;" class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-tr" type="button" >-</button> </td>' +
                    '</tr>'
                );

                $.ajax({
                    url: '/getProducts/',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        //console.log(response['data']);
                        var len = response['data'].length;

                        var selectedValues = new Array();

                        if (len > 0) {
                            for (var i = 0; i < len; i++) {

                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='" + id + "'>" + id + ' - ' + name +
                                        "</option>";
                                    selectedValues.push(option);
                            }
                        }
                        ++j;
                        $('#product_id' + j).append(selectedValues);
                        //add_count.push(Object.keys(selectedValues).length);
                    }
                });
        });




        $('.commission_ornet').on('change', function() {
            var commission_ornet = this.value;
            if(commission_ornet == 'commission'){
                $("#commission_percent").show();
            }else if(commission_ornet == 'netprice'){
                $("#commission_percent").hide();
                $(".commission_amount").val(0);
                $("#commission_percent").val(0);
            }
        });
        $(document).on("keyup", 'input.commission_percent', function() {
                var commission_percent = $(this).val();
                var total_amount = $(".total_amount").val();
                var commision_amount = (commission_percent / 100) * total_amount;
                $('.commission_amount').val(commision_amount.toFixed(2));

                var totalExtraAmount = 0;
            $("input[name='extracost[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalExtraAmount = Number(totalExtraAmount) +
                                            Number($(this).val());
                                        $('.total_extracost').val(
                                            totalExtraAmount);
                                    });
            var tot_comm_extracost = Number(totalExtraAmount) + Number(commision_amount);
            $(".tot_comm_extracost").val(tot_comm_extracost);


                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) - Number(tot_comm_extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
        });



        $('.ppayment_branch_id').on('change', function() {
            var branch_id = this.value;
            var supplier_id = $(".ppayment_supplier_id").val();
            //alert(branch_id);
            $('.oldblance').val('');
                $.ajax({
                    url: '/getoldbalanceforPayment/',
                    type: 'get',
                    data: {
                            _token: "{{ csrf_token() }}",
                            supplier_id: supplier_id,
                            branch_id: branch_id
                        },
                    dataType: 'json',
                    success: function(response) {
                        //
                        console.log(response);
                        var len = response.length;
                        for (var i = 0; i < len; i++) {
                            $(".oldblance").val(response[i].payment_pending);
                            $('.purchasepayment_totalamount').val(response[i].payment_pending);
                        }
                    }
                });
        });
        $('.ppayment_supplier_id').on('change', function() {
            var supplier_id = this.value;
            var branch_id = $(".ppayment_branch_id").val();
            //alert(branch_id);
            $('.oldblance').val('');
                $.ajax({
                    url: '/getoldbalanceforPayment/',
                    type: 'get',
                    data: {
                            _token: "{{ csrf_token() }}",
                            supplier_id: supplier_id,
                            branch_id: branch_id
                        },
                    dataType: 'json',
                    success: function(response) {
                        //
                        console.log(response);
                        var len = response.length;
                        for (var i = 0; i < len; i++) {
                            $(".oldblance").val(response[i].payment_pending);
                            $('.purchasepayment_totalamount').val(response[i].payment_pending);
                        }
                    }
                });
        });

            $(document).on("keyup", 'input.purchasepayment_discount', function() {
                var purchasepayment_discount = $(this).val();
                var oldblance = $(".oldblance").val();
                var Total_purchasepayment = Number(oldblance) - Number(purchasepayment_discount);
                $('.purchasepayment_totalamount').val(Total_purchasepayment);

                var payment_payableamount = $(".payment_payableamount").val();
                var payment_pending_amount = Number(Total_purchasepayment) - Number(payment_payableamount);
                $('.payment_pending').val(payment_pending_amount.toFixed(2));

            });

            $(document).on("keyup", 'input.payment_payableamount', function() {
                var payment_payableamount = $(this).val();
                var purchasepayment_totalamount = $(".purchasepayment_totalamount").val();
                var payment_pending_amount = Number(purchasepayment_totalamount) - Number(payment_payableamount);
                $('.payment_pending').val(payment_pending_amount.toFixed(2));
            });





        $('.spayment_branch_id').on('change', function() {
            var spayment_branch_id = this.value;
            var spayment_customer_id = $(".spayment_customer_id").val();
            //alert(branch_id);
            $('.sales_oldblance').val('');
                $.ajax({
                    url: '/oldbalanceforsalespayment/',
                    type: 'get',
                    data: {
                            _token: "{{ csrf_token() }}",
                            spayment_customer_id: spayment_customer_id,
                            spayment_branch_id: spayment_branch_id
                        },
                    dataType: 'json',
                    success: function(response) {
                        //
                        console.log(response);
                        var len = response.length;
                        for (var i = 0; i < len; i++) {
                            $(".sales_oldblance").val(response[i].payment_pending);
                            $('.salespayment_totalamount').val(response[i].payment_pending);
                        }
                    }
                });
        });
        $('.spayment_customer_id').on('change', function() {
            var spayment_customer_id = this.value;
            var spayment_branch_id = $(".spayment_branch_id").val();
            //alert(branch_id);
            $('.sales_oldblance').val('');
                $.ajax({
                    url: '/oldbalanceforsalespayment/',
                    type: 'get',
                    data: {
                            _token: "{{ csrf_token() }}",
                            spayment_customer_id: spayment_customer_id,
                            spayment_branch_id: spayment_branch_id
                        },
                    dataType: 'json',
                    success: function(response) {
                        //
                        console.log(response);
                        var len = response.length;
                        for (var i = 0; i < len; i++) {
                            $(".sales_oldblance").val(response[i].payment_pending);
                            $('.salespayment_totalamount').val(response[i].payment_pending);
                        }
                    }
                });
        });

            $(document).on("keyup", 'input.salespayment_discount', function() {
                var salespayment_discount = $(this).val();
                var sales_oldblance = $(".sales_oldblance").val();
                var Total_salespayment = Number(sales_oldblance) - Number(salespayment_discount);
                $('.salespayment_totalamount').val(Total_salespayment);

                var spayment_payableamount = $(".spayment_payableamount").val();
                var spayment_pending_amount = Number(Total_salespayment) - Number(spayment_payableamount);
                $('.spayment_pending').val(spayment_pending_amount.toFixed(2));

            });

            $(document).on("keyup", 'input.spayment_payableamount', function() {
                var spayment_payableamount = $(this).val();
                var salespayment_totalamount = $(".salespayment_totalamount").val();
                var spayment_pending_amount = Number(salespayment_totalamount) - Number(spayment_payableamount);
                $('.spayment_pending').val(spayment_pending_amount.toFixed(2));
            });



        $('.purchase_pattiyal').each(function() {
            $(this).on('click', function(e) {
                purchase_uniquekey = $(this).attr('data-id');
                //alert(purchase_uniquekey);


            });
        });

        $('.purchaseview').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var $this = $(this),
                purchase_id = $this.attr('data-id');
                //alert(purchase_id);

                $.ajax({
                    url: '/getPurchaseview/',
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        purchase_id: purchase_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var Totalextraamount = Number(response[i].tot_comm_extracost) - Number(response[i].commission_amount);
                                $('.purchase_bill_no').html(response[i].purchase_bill_no);
                                $('.purchase_total_amount').html(response[i].purchase_total_amount);
                                $('.purchase_commision').html(response[i].commission_amount);
                                $('.purchase_commisionpercentage').html(response[i].purchase_commisionpercentage);
                                $('.tot_comm_extracost').html(response[i].tot_comm_extracost);
                                $('.purchase_extra_cost').html(Totalextraamount);

                                $('.purchase_grossamont').html(response[i].purchase_gross_amount);
                                $('.purchase_old_balance').html(response[i].purchase_old_balance);
                                $('.purchase_grand_total').html(response[i].purchase_grand_total);
                                $('.purchase_paid_amount').html(response[i].purchase_paid_amount);
                                $('.purchase_balance_amount').html(response[i].purchase_balance_amount);

                                $('.suppliername').html(response[i].suppliername);
                                $('.supplier_contact_number').html(response[i].supplier_contact_number);
                                $('.supplier_shop_name').html(response[i].supplier_shop_name);
                                $('.supplier_shop_address').html(response[i].supplier_shop_address);

                                $('.branchname').html(response[i].branchname);
                                $('.branch_contact_number').html(response[i].branch_contact_number);
                                $('.branch_shop_name').html(response[i].branch_shop_name);
                                $('.branch_address').html(response[i].branch_address);

                                $('.date').html(response[i].date);
                                $('.time').html(response[i].time);
                                $('.bank_namedata').html(response[i].bank_namedata);
                            }
                        }
                    }
                });


            });
        });



        $('.purchaseorderview').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var $this = $(this),
                purchase_id = $this.attr('data-id');
                //alert(purchase_id);

                $.ajax({
                    url: '/getpurchaseorderview/',
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        purchase_id: purchase_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                var Totalextraamount = Number(response[i].tot_comm_extracost) - Number(response[i].commission_amount);
                                $('.purchaseorder_bill_no').html(response[i].purchase_bill_no);
                                $('.purchaseorder_total_amount').html(response[i].purchase_total_amount);
                                $('.purchaseorder_commission_ornet').html(response[i].commission_ornet);
                                $('.purchaseorder_commisionpercentage').html(response[i].commission_percent);
                                $('.purchaseorder_commision').html(response[i].commission_amount);
                                $('.purchaseorder_tot_comm_extracost').html(response[i].tot_comm_extracost);
                                $('.purchaseorder_extra_cost').html(Totalextraamount);
                                $('.purchaseorder_grossamont').html(response[i].purchase_gross_amount);
                                $('.purchaseorder_old_balance').html(response[i].purchase_old_balance);
                                $('.purchaseorder_grand_total').html(response[i].purchase_grand_total);
                                $('.purchaseorder_paid_amount').html(response[i].purchase_paid_amount);
                                $('.purchaseorder_balance_amount').html(response[i].purchase_balance_amount);

                                $('.purchaseorder_suppliername').html(response[i].suppliername);
                                $('.purchaseordersupplier_contact_number').html(response[i].supplier_contact_number);
                                $('.purchaseordersupplier_shop_name').html(response[i].supplier_shop_name);
                                $('.purchaseordersupplier_shop_address').html(response[i].supplier_shop_address);

                                $('.purchaseorderbranchname').html(response[i].branchname);
                                $('.purchaseorderbranch_contact_number').html(response[i].branch_contact_number);
                                $('.purchaseorderbranch_shop_name').html(response[i].branch_shop_name);
                                $('.purchaseorderbranch_address').html(response[i].branch_address);

                                $('.purchaseorder_date').html(response[i].date);
                                $('.purchaseorder_time').html(response[i].time);
                                $('.purchaseorderbank_namedata').html(response[i].bank_namedata);
                            }
                        }
                    }
                });


            });
        });



        $('.salesview').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var $this = $(this),
                sales_id = $this.attr('data-id');
                //alert(sales_id);

                $.ajax({
                    url: '/getSalesview/',
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        sales_id: sales_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                $('.sales_bill_no').html(response[i].sales_bill_no);
                                $('.sales_total_amount').html(response[i].sales_total_amount);
                                $('.sales_extra_cost').html(response[i].sales_extra_cost);
                                $('.sales_old_balance').html(response[i].sales_old_balance);
                                $('.sales_grand_total').html(response[i].sales_grand_total);
                                $('.sales_paid_amount').html(response[i].sales_paid_amount);
                                $('.sales_balance_amount').html(response[i].sales_balance_amount);

                                $('.sales_customername').html(response[i].sales_customername);
                                $('.sales_customercontact_number').html(response[i].sales_customercontact_number);
                                $('.sales_customershop_name').html(response[i].sales_customershop_name);
                                $('.sales_customershop_address').html(response[i].sales_customershop_address);

                                $('.sales_branchname').html(response[i].sales_branchname);
                                $('.salesbranch_contact_number').html(response[i].salesbranch_contact_number);
                                $('.salesbranch_shop_name').html(response[i].salesbranch_shop_name);
                                $('.salesbranch_address').html(response[i].salesbranch_address);

                                $('.sales_date').html(response[i].sales_date);
                                $('.sales_time').html(response[i].sales_time);
                                $('.sales_bank_namedata').html(response[i].sales_bank_namedata);
                            }
                        }
                    }
                });


            });
        });



        $('.salesorderview').each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                var $this = $(this),
                sales_id = $this.attr('data-id');
                //alert(sales_id);

                $.ajax({
                    url: '/salesorderview/',
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        sales_id: sales_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        if (len > 0) {
                            for (var i = 0; i < len; i++) {
                                $('.sales_bill_no').html(response[i].sales_bill_no);
                                $('.sales_total_amount').html(response[i].sales_total_amount);
                                $('.sales_grossamount').html(response[i].sales_gross_amount);
                                $('.sales_extra_cost').html(response[i].sales_extra_cost);
                                $('.sales_old_balance').html(response[i].sales_old_balance);
                                $('.sales_grand_total').html(response[i].sales_grand_total);
                                $('.sales_paid_amount').html(response[i].sales_paid_amount);
                                $('.sales_balance_amount').html(response[i].sales_balance_amount);

                                $('.sales_customername').html(response[i].sales_customername);
                                $('.sales_customercontact_number').html(response[i].sales_customercontact_number);
                                $('.sales_customershop_name').html(response[i].sales_customershop_name);
                                $('.sales_customershop_address').html(response[i].sales_customershop_address);

                                $('.sales_branchname').html(response[i].sales_branchname);
                                $('.salesbranch_contact_number').html(response[i].salesbranch_contact_number);
                                $('.salesbranch_shop_name').html(response[i].salesbranch_shop_name);
                                $('.salesbranch_address').html(response[i].salesbranch_address);

                                $('.sales_date').html(response[i].sales_date);
                                $('.sales_time').html(response[i].sales_time);
                                $('.sales_bank_namedata').html(response[i].sales_bank_namedata);
                            }
                        }
                    }
                });


            });
        });


            //$('.checkbalance').each(function() {
               //         $(this).on('click', function(e) {
                  //         e.preventDefault();
                  //         var $this = $(this),
                  //         supplierid = $this.attr('data-id');
                            //alert(supplierid);



                    //        $.ajax({
                      //          url: '/getsupplierbalance/',
                    //            type: 'get',
                    //            data: {
                    //                        _token: "{{ csrf_token() }}",
                     //                       supplierid: supplierid
                      //                  },
                      //          dataType: 'json',
                      //              success: function(response) {
                      //                  console.log(response);
                      //                  var len = response.length;
                       //                 var supplirtotbal = 0;
                        //                if (len > 0) {
                       //                     for (var i = 0; i < len; i++) {
                        //                        supplirtotbal += response[i].balance_amount << 0;
                        //                        var balance_amount = response[0].balance_amount;
                        //                        console.log(balance_amount);

                         //                       $('.supplier_balance' + m).html(balance_amount);
                         //                       $('.suplier_totbalnce').html(supplirtotbal);
                         //                   }
                         //                  for (var i = 0; i < len; i++) {
                          //                      var balance_amount1 = response[1].balance_amount;
                          //                      console.log(balance_amount1);

                          //                      $('.supplier_balance' + n).html(balance_amount1);
                           //                 }
                           //             }
                            //        }
                            //    });


                        //});
                     //});




            $(".supplier_contactno").keyup(function() {
                var query = $(this).val();


                if (query != '') {

                    $.ajax({
                        url: "{{ route('supplier.checkduplicate') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            query: query
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response['data']);
                            if(response['data'] != null){
                                alert('Already Existed');
                                $('.supplier_contactno').val('');
                            }
                        }
                    });
                }


            });

            $(".customer_contactno").keyup(function() {
                var query = $(this).val();


                if (query != '') {

                    $.ajax({
                        url: "{{ route('customer.checkduplicate') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            query: query
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response['data']);
                            if(response['data'] != null){
                                alert('Already Existed');
                                $('.customer_contactno').val('');
                            }
                        }
                    });
                }


            });


            $(document).on("keyup", 'input.expense_amount', function() {
                    var tot_expense_amount = 0;
                    $("input[name='expense_amount[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        tot_expense_amount = Number(tot_expense_amount) +
                                            Number($(this).val());
                                        $('#tot_expense_amount').val(
                                            tot_expense_amount);
                                    });
                });
            $(document).on('click', '.addexpensefilds', function() {
                $(".expensefilds").append(
                    '<tr>' +
                    '<td><input type="hidden"id="expense_detialid"name="expense_detialid[]" /><input type="text" class="form-control expense_note" id="expense_note" name="expense_note[]" placeholder="Note" value="" required /></td>' +
                    '<td><input type="text" class="form-control expense_amount" id="expense_amount" name="expense_amount[]" placeholder="Amount" value="" required /></td>' +
                    '<td><button style="width: 35px;margin-right:5px;"class="addexpensefilds py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"type="button" id="" value="Add">+</button>' +
                    '<button style="width: 35px;"class="py-1 text-white remove-expensetr font-medium rounded-lg text-sm  text-center btn btn-danger" type="button" id="" value="">-</button></td>' +
                    '</tr>'
                );

                $(document).on("keyup", 'input.expense_amount', function() {
                    var tot_expense_amount = 0;
                    $("input[name='expense_amount[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        tot_expense_amount = Number(tot_expense_amount) +
                                            Number($(this).val());
                                        $('#tot_expense_amount').val(
                                            tot_expense_amount);
                                    });
                });

            });


            $(document).on('click', '.remove-expensetr', function() {
                $(this).parents('tr').remove();

                var tot_expense_amount = 0;
                 $("input[name='expense_amount[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        tot_expense_amount = Number(tot_expense_amount) +
                                            Number($(this).val());
                                        $('#tot_expense_amount').val(
                                            tot_expense_amount);
                                    });
            });


    });


    function check()
    {
        var mobile = $('.supplier_contactno').val();

        if(mobile.length>10){
            $('.supplier_contactno').val('');

        }
    }

    function customercheck()
    {
        var mobile = $('.customer_contactno').val();

        if(mobile.length>10){
            $('.customer_contactno').val('');

        }
    }





    $(document).on('click', '.remove-tr', function() {
            $(this).parents('tr').remove();

            var totalAmount = 0;
            $("input[name='total_price[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalAmount = Number(totalAmount) +
                                            Number($(this).val());
                                        $('.total_amount').val(
                                            totalAmount);
                                    });
                var extracost = $(".extracost").val();
                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) + Number(extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var grand_total = $(".grand_total").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));

                $(document).on("keyup", 'input.payable_amount', function() {
                var payable_amount = $(this).val();
                var grand_total = $(".grand_total").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
            });
    });


        $(document).on("keyup", "input[name*=count]", function() {
         var count = $(this).val();
         var price_per_kg = $(this).parents('tr').find('.price_per_kg').val();
         var total = count * price_per_kg;
         $(this).parents('tr').find('.total_price').val(total);

         var totalAmount = 0;
            $("input[name='total_price[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalAmount = Number(totalAmount) +
                                            Number($(this).val());
                                        $('.total_amount').val(
                                            totalAmount);
                                    });
                var extracost = $(".extracost").val();
                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) + Number(extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var grand_total = $(".grand_total").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));


            $(document).on("keyup", 'input.extracost', function() {
                var extracost = $(this).val();
                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) + Number(extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
            });

            $(document).on("keyup", 'input.payable_amount', function() {
                var payable_amount = $(this).val();
                var grand_total = $(".grand_total").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
            });
        });


        $(document).on("keyup", "input[name*=price_per_kg]", function() {
         var price_per_kg = $(this).val();
         var count = $(this).parents('tr').find('.count').val();
         var total = count * price_per_kg;
         $(this).parents('tr').find('.total_price').val(total);

         var totalAmount = 0;
            $("input[name='total_price[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalAmount = Number(totalAmount) +
                                            Number($(this).val());
                                        $('.total_amount').val(
                                            totalAmount);
                                    });


            var totalExtraAmount = 0;
            $("input[name='extracost[]']").each(
                                    function() {
                                        totalExtraAmount = Number(totalExtraAmount) +
                                            Number($(this).val());
                                        $('.total_extracost').val(
                                            totalExtraAmount);
                                    });
            var commission_amount = $(".commission_amount").val();
            var tot_comm_extracost = Number(totalExtraAmount) + Number(commission_amount);
            $(".tot_comm_extracost").val(tot_comm_extracost);


                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) - Number(tot_comm_extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var grand_total = $(".grand_total").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));


            $(document).on("blur", "input[name*=extracost]", function() {
                var extracost = $(this).val();
                var totalExtraAmount = 0;
                $("input[name='extracost[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalExtraAmount = Number(totalExtraAmount) +
                                            Number($(this).val());
                                        $('.total_extracost').val(
                                            totalExtraAmount);
                                    });
                var commission_amount = $(".commission_amount").val();
                var tot_comm_extracost = Number(totalExtraAmount) + Number(commission_amount);
                $(".tot_comm_extracost").val(tot_comm_extracost);
                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) - Number(tot_comm_extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
            });

            $(document).on("keyup", 'input.payable_amount', function() {
                var payable_amount = $(this).val();
                var grand_total = $(".grand_total").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
            });




            var invoice_supplier = $(".invoice_supplier").val();
            var invoice_branchid = $(".invoice_branchid").val();

            if(invoice_branchid){
                    console.log(invoice_branchid);
                    console.log(invoice_supplier);
                    $('.old_balance').val('');
                    $.ajax({
                    url: '/getoldbalance/',
                    type: 'get',
                    data: {_token: "{{ csrf_token() }}",
                                invoice_supplier: invoice_supplier,
                                invoice_branchid: invoice_branchid,
                            },
                    dataType: 'json',
                        success: function(response) {
                            console.log(response);
                                    $(".old_balance").val(response['data']);
                                    var gross_amount = $(".gross_amount").val();
                                    var grand_total = Number(response['data']) + Number(gross_amount);
                                    $('.grand_total').val(grand_total.toFixed(2));

                                    var payable_amount = $(".payable_amount").val();
                                    var pending_amount = Number(grand_total) - Number(payable_amount);
                                    $('.pending_amount').val(pending_amount.toFixed(2));
                        }
                    });
            }


        });

        $('.invoice_supplier').on('change', function() {
            var invoice_supplier = $(this).val();
            var invoice_branchid = $(".invoice_branchid").val();
            if(invoice_branchid){
                    console.log(invoice_branchid);
                    console.log(invoice_supplier);
                    $('.old_balance').val('');
                    $.ajax({
                    url: '/getoldbalance/',
                    type: 'get',
                    data: {_token: "{{ csrf_token() }}",
                                invoice_supplier: invoice_supplier,
                                invoice_branchid: invoice_branchid,
                            },
                    dataType: 'json',
                        success: function(response) {
                            console.log(response);
                                    $(".old_balance").val(response['data']);
                                    var gross_amount = $(".gross_amount").val();
                                    var grand_total = Number(response['data']) + Number(gross_amount);
                                    $('.grand_total').val(grand_total.toFixed(2));

                                    var payable_amount = $(".payable_amount").val();
                                    var pending_amount = Number(grand_total) - Number(payable_amount);
                                    $('.pending_amount').val(pending_amount.toFixed(2));
                        }
                    });
            }
        });


        $(document).on("blur", "input[name*=extracost]", function() {
        var extracost = $(this).val();
        var totalExtraAmount = 0;
            $("input[name='extracost[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalExtraAmount = Number(totalExtraAmount) +
                                            Number($(this).val());
                                        $('.total_extracost').val(
                                            totalExtraAmount);
                                    });
        var commission_amount = $(".commission_amount").val();
        var tot_comm_extracost = Number(totalExtraAmount) + Number(commission_amount);
        $(".tot_comm_extracost").val(tot_comm_extracost);

                var total_amount = $(".total_amount").val();
                var gross_amount = Number(total_amount) - Number(tot_comm_extracost);
                $('.gross_amount').val(gross_amount.toFixed(2));
                var old_balance = $(".old_balance").val();
                var grand_total = Number(old_balance) + Number(gross_amount);
                $('.grand_total').val(grand_total.toFixed(2));

                var payable_amount = $(".payable_amount").val();
                var pending_amount = Number(grand_total) - Number(payable_amount);
                $('.pending_amount').val(pending_amount.toFixed(2));
        });





// SALES

    $('.sales_branch_id').on('change', function() {
            var sales_branch_id = this.value;
            //alert(sales_branch_id);
                $.ajax({
                    url: '/getbranchwiseProducts/',
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        sales_branch_id: sales_branch_id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;

                        var selectedValues = new Array();

                        if (len > 0) {
                            for (var i = 0; i < len; i++) {

                                    var id = response[i].productlistid;
                                    var name = response[i].productlist_name;
                                    var option = "<option value='" + id + "'>" + name +
                                        "</option>";
                                    selectedValues.push(option);
                            }
                        }
                        $('#sales_product_id1').append(selectedValues);
                    }
                });
        });





       // $(document).ready(function() {

            /* NEW QUOTE BUTTON */

          //  $("#branch_widget").click(function() {
           //     location.reload();
         //       document.getElementById("branch_widget").style.backgroundColor = "blue";
          //  });

       // });

var j = 1;
var i = 1;
var k = 1;

$(document).ready(function() {

    $(document).on('click', '.addsalesproductfields', function() {
        ++i;
                $(".sales_productfields").append(
                    '<tr>' +
                    '<td class=""><input type="hidden"id="sales_detail_id"name="sales_detail_id[]" />' +
                    '<select class="form-control js-example-basic-single select sales_product_id"name="sales_product_id[]" id="sales_product_id' + i + '"required>' +
                    '<option value="" selected hidden class="text-muted">Select Product</option></select>' +
                    '</td>' +
                    '<td><select class=" form-control sales_bagorkg" name="sales_bagorkg[]" id="sales_bagorkg' + i + '"required>' +
                    '<option value="" selected hidden class="text-muted">Select</option>' +
                    '<option value="bag">Bag</option><option value="kg">Kg</option>' +
                    '</select></td>' +
                    '<td><input type="text" class="form-control sales_count" id="sales_count' + i + '" name="sales_count[]" placeholder="count" value="" required /></td>' +
                    '<td><input type="text" class="form-control sales_priceperkg" id="sales_priceperkg" name="sales_priceperkg[]" placeholder="Price Per Count" value="" required /></td>' +
                    '<td class="text-end"><input type="text" class="form-control sales_total_price" id="sales_total_price" readonly style="background-color: #e9ecef;" name="sales_total_price[]" placeholder="" value="" required /></td>' +
                    '<td><button style=" width: 35px;margin-right:5px;"class="addsalesproductfields py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary"type="button" id="" value="Add">+</button>' +
                    '<button style="width: 35px;" class="text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-salestr" type="button" >-</button></td>' +
                    '</tr>'
                );

                var sales_branch_id = $(".sales_branch_id").val();
                $.ajax({
                    url: '/getbranchwiseProducts/',
                    type: 'get',
                    data: {
                        _token: "{{ csrf_token() }}",
                        sales_branch_id: sales_branch_id,
                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;

                        var selectedValues = new Array();

                        if (len > 0) {
                            for (var i = 0; i < len; i++) {

                                    var id = response[i].productlistid;
                                    var name = response[i].productlist_name;
                                    var option = "<option value='" + id + "'>" + name +
                                        "</option>";
                                    selectedValues.push(option);
                            }
                        }++j;
                        $('#sales_product_id' + j).append(selectedValues);
                    }
                });

    });




    $(document).on('click', '.addsalesorderfields', function() {
        ++i;
                $("#sales_orderfields").append(
                    '<tr>' +
                    '<td class=""><input type="hidden"id="sales_detail_id"name="sales_detail_id[]" />' +
                    '<select class="form-control js-example-basic-single select "name="sales_product_id[]" id="product_id' + i + '"required>' +
                    '<option value="" selected hidden class="text-muted">Select Product</option></select>' +
                    '</td>' +
                    '<td><select class=" form-control sales_bagorkg" name="sales_bagorkg[]" id="sales_bagorkg' + i + '"required>' +
                    '<option value="" selected hidden class="text-muted">Select</option>' +
                    '<option value="bag">Bag</option><option value="kg">Kg</option>' +
                    '</select></td>' +
                    '<td><input type="text" class="form-control sales_count" id="sales_count' + i + '" name="sales_count[]" placeholder="count" value="" required /></td>' +
                    '<td><input type="text" class="form-control sales_note" id="sales_note" name="sales_note[]" placeholder="note" value="" required /></td>' +
                    '<td><input type="text" class="form-control sales_priceperkg" id="sales_priceperkg" name="sales_priceperkg[]" placeholder="Price Per Count" value="" required /></td>' +
                    '<td class="text-end"><input type="text" class="form-control sales_total_price" id="sales_total_price" readonly style="background-color: #e9ecef;" name="sales_total_price[]" placeholder="" value="" required /></td>' +
                    '<td><button style="width: 35px;margin-right:5px;"class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-primary addsalesorderfields" type="button" id="" value="Add">+</button>' +
                    '<button style="width: 35px;" class="py-1 text-white font-medium rounded-lg text-sm  text-center btn btn-danger remove-salestr" type="button" >-</button></td>' +
                    '</tr>'
                );


                $.ajax({
                    url: '/getProducts/',
                    type: 'get',
                    dataType: 'json',
                    success: function(response) {
                        //console.log(response['data']);
                        var len = response['data'].length;

                        var selectedValues = new Array();

                        if (len > 0) {
                            for (var i = 0; i < len; i++) {

                                    var id = response['data'][i].id;
                                    var name = response['data'][i].name;
                                    var option = "<option value='" + id + "'>" + id + ' - ' + name +
                                        "</option>";
                                    selectedValues.push(option);
                            }
                        }
                        ++j;
                        $('#product_id' + j).append(selectedValues);
                        //add_count.push(Object.keys(selectedValues).length);
                    }
                });

    });








});



$(document).on('click', '.remove-salestr', function() {
            $(this).parents('tr').remove();

            var totalAmount = 0;
            $("input[name='sales_total_price[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalAmount = Number(totalAmount) +
                                            Number($(this).val());
                                        $('.sales_total_amount').val(
                                            totalAmount);
                                    });
                var sales_extracost = $(".sales_extracost").val();
                var sales_total_amount = $(".sales_total_amount").val();
                var sales_gross_amount = Number(sales_total_amount) + Number(sales_extracost);
                $('.sales_gross_amount').val(sales_gross_amount.toFixed(2));
                var sales_old_balance = $(".sales_old_balance").val();
                var sales_grand_total = Number(sales_old_balance) + Number(sales_gross_amount);
                $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                var salespayable_amount = $(".salespayable_amount").val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));

                $(document).on("keyup", 'input.salespayable_amount', function() {
                var salespayable_amount = $(this).val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
            });
    });



    $(document).on("blur", "input[name*=sales_count]", function() {
         var sales_count = $(this).val();
         var sales_priceperkg = $(this).parents('tr').find('.sales_priceperkg').val();
         var sales_total = sales_count * sales_priceperkg;
         $(this).parents('tr').find('.sales_total_price').val(sales_total);

         var totalAmount = 0;
            $("input[name='sales_total_price[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalAmount = Number(totalAmount) +
                                            Number($(this).val());
                                        $('.sales_total_amount').val(
                                            totalAmount);
                                    });
                var sales_extracost = $(".sales_extracost").val();
                var sales_total_amount = $(".sales_total_amount").val();
                var sales_gross_amount = Number(sales_total_amount) + Number(sales_extracost);
                $('.sales_gross_amount').val(sales_gross_amount.toFixed(2));
                var sales_old_balance = $(".sales_old_balance").val();
                var sales_grand_total = Number(sales_old_balance) + Number(sales_gross_amount);
                $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                var salespayable_amount = $(".salespayable_amount").val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));


            $(document).on("keyup", 'input.sales_extracost', function() {
                var sales_extracost = $(this).val();
                var sales_total_amount = $(".sales_total_amount").val();
                var sales_gross_amount = Number(sales_total_amount) + Number(sales_extracost);
                $('.sales_gross_amount').val(sales_gross_amount.toFixed(2));
                var sales_old_balance = $(".sales_old_balance").val();
                var sales_grand_total = Number(sales_old_balance) + Number(sales_gross_amount);
                $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                var salespayable_amount = $(".salespayable_amount").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
            });

            $(document).on("keyup", 'input.salespayable_amount', function() {
                var salespayable_amount = $(this).val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
            });
      });



      $(document).on("blur", "input[name*=sales_priceperkg]", function() {





         var sales_priceperkg = $(this).val();
         var sales_count = $(this).parents('tr').find('.sales_count').val();
         var sales_total = sales_count * sales_priceperkg;
         $(this).parents('tr').find('.sales_total_price').val(sales_total);

         var totalAmount = 0;
         $("input[name='sales_total_price[]']").each(
                                    function() {
                                        //alert($(this).val());
                                        totalAmount = Number(totalAmount) +
                                            Number($(this).val());
                                        $('.sales_total_amount').val(
                                            totalAmount);
                                    });
                var sales_extracost = $(".sales_extracost").val();
                var sales_total_amount = $(".sales_total_amount").val();
                var sales_gross_amount = Number(sales_total_amount) + Number(sales_extracost);
                $('.sales_gross_amount').val(sales_gross_amount.toFixed(2));
                var sales_old_balance = $(".sales_old_balance").val();
                var sales_grand_total = Number(sales_old_balance) + Number(sales_gross_amount);
                $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                var salespayable_amount = $(".salespayable_amount").val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));


            $(document).on("keyup", 'input.sales_extracost', function() {
                var sales_extracost = $(this).val();
                var sales_total_amount = $(".sales_total_amount").val();
                var sales_gross_amount = Number(sales_total_amount) + Number(sales_extracost);
                $('.sales_gross_amount').val(sales_gross_amount.toFixed(2));
                var sales_old_balance = $(".sales_old_balance").val();
                var sales_grand_total = Number(sales_old_balance) + Number(sales_gross_amount);
                $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                var salespayable_amount = $(".salespayable_amount").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
            });

            $(document).on("keyup", 'input.salespayable_amount', function() {
                var salespayable_amount = $(this).val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
            });




            var sales_branch_id = $(".sales_branch_id").val();
            var sales_customerid = $(".sales_customerid").val();

            if(sales_customerid){
                $('.sales_old_balance').html('');
                $.ajax({
                url: '/getoldbalanceforSales/',
                type: 'get',
                data: {
                            _token: "{{ csrf_token() }}",
                            sales_customerid: sales_customerid,
                            sales_branch_id: sales_branch_id
                        },
                dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        for (var i = 0; i < len; i++) {
                            $(".sales_old_balance").val(response[i].payment_pending);

                            var sales_gross_amount = $(".sales_gross_amount").val();
                            var sales_grand_total = Number(response[i].payment_pending) + Number(sales_gross_amount);
                            $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                            var salespayable_amount = $(".salespayable_amount").val();
                            var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                            $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
                        }

                    }
                });
            }

      });


        $('.sales_customerid').on('change', function() {
            var sales_customerid = $(this).val();
            var sales_branch_id = $(".sales_branch_id").val();

            if(sales_customerid){
                $('.sales_old_balance').html('');
                $.ajax({
                url: '/getoldbalanceforSales/',
                type: 'get',
                data: {
                            _token: "{{ csrf_token() }}",
                            sales_customerid: sales_customerid,
                            sales_branch_id: sales_branch_id
                        },
                dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        var len = response.length;
                        for (var i = 0; i < len; i++) {
                            $(".sales_old_balance").val(response[i].payment_pending);

                            var sales_gross_amount = $(".sales_gross_amount").val();
                            var sales_grand_total = Number(response[i].payment_pending) + Number(sales_gross_amount);
                            $('.sales_grand_total').val(sales_grand_total.toFixed(2));

                            var salespayable_amount = $(".salespayable_amount").val();
                            var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                            $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
                        }

                    }
                });
            }
        });

        $(document).on("keyup", 'input.salespayable_amount', function() {
                var salespayable_amount = $(this).val();
                var sales_grand_total = $(".sales_grand_total").val();
                var sales_pending_amount = Number(sales_grand_total) - Number(salespayable_amount);
                $('.sales_pending_amount').val(sales_pending_amount.toFixed(2));
            });





        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }



        $(document).on("keyup", 'input.salespayable_amount', function() {
            var payable_amount = $(this).val();
            var grand_total = $(".sales_grand_total").val();

            if (Number(payable_amount) > Number(grand_total)) {
                alert('!Paid Amount is More than of Total!');
                $(".salespayable_amount").val('');
            }
        });


        $(document).on("keyup", 'input.payable_amount', function() {
            var payable_amount = $(this).val();
            var grand_total = $(".grand_total").val();

            if (Number(payable_amount) > Number(grand_total)) {
                alert('!Paid Amount is More than of Total!');
                $(".payable_amount").val('');
            }
        });


        $(document).on("keyup", 'input.payment_payableamount', function() {
            var payment_payableamount = $(this).val();
            var oldblance = $(".oldblance").val();

            if (Number(payment_payableamount) > Number(oldblance)) {
                alert('!Paid Amount is More than of Total!');
                $(".payment_payableamount").val('');
            }
        });


        $(document).on("keyup", 'input.spayment_payableamount', function() {
            var spayment_payableamount = $(this).val();
            var sales_oldblance = $(".sales_oldblance").val();

            if (Number(spayment_payableamount) > Number(sales_oldblance)) {
                alert('!Paid Amount is More than of Total!');
                $(".spayment_payableamount").val('');
            }
        });


    function purchasesubmitForm(btn) {
        // disable the button
        btn.disabled = true;
        // submit the form
        btn.form.submit();
    }

    function purchaseinvoiceubmitForm(btn) {
        // disable the button
        btn.disabled = true;
        // submit the form
        btn.form.submit();
    }


    function salessubmitForm(btn) {
        // disable the button
        btn.disabled = true;
        // submit the form
        btn.form.submit();
    }






    $(document).ready(function () {
        $("#viewtotal").click(function () {
            $("#totaldiv").toggle();
        });


        $("#viewsuppliertotal").click(function () {
            $("#suppliertotaldiv").toggle();
        });
    });




</script>
