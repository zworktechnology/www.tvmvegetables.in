<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Productlist;
use App\Models\Sales;
use App\Models\SalesProduct;
use App\Models\Purchase;
use App\Models\PurchaseProduct;
use App\Models\BranchwiseBalance;
use App\Models\Salespayment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PDF;

class SalesController extends Controller
{
    public function index()
    {

        $today = Carbon::now()->format('Y-m-d');
        $data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $customer_name = Customer::findOrFail($datas->customer_id);

            $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('sales_order', '=', NULL)->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }



            $Sales_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'old_balance' => $datas->old_balance,
                'grand_total' => $datas->grand_total,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'sales_terms' => $sales_terms,
                'status' => $datas->status,
            );
        }

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


                $PSTodayStockArr = [];

                $sales_branchwise_data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
                $Sales_Branch = [];
                foreach ($sales_branchwise_data as $key => $sales_Data) {
                    $Sales_Branch[] = $sales_Data->branch_id;
                }


                foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

                    $merge_salesProduct = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->get();
                    $sales_Array = [];
                    if($merge_salesProduct != ""){
                        foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                            $sales_Array[] = $merge_salesProducts->productlist_id;
                        }
                    }else {
                        $sales_Array[] = '';
                    }



                    foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                        $getSalebagcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                        $getSalekgcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


                        if($getSalebagcount != 0){
                            $bag_count = $getSalebagcount;
                        }else {
                            $bag_count = '';
                        }

                        if($getSalekgcount != 0){
                            $kg_count = $getSalekgcount;
                        }else {
                            $kg_count = '';
                        }


                            $productlist_ID = Productlist::findOrFail($sales_productlist);

                            $PSTodayStockArr[] = array(
                                'branch_id' => $Merge_Branchs,
                                'product_name' => $productlist_ID->name,
                                'getSalebagcount' => $bag_count,
                                'getSalekgcount' => $kg_count,
                                'today' => $today,

                            );


                    }

                }





                $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.sales.index', compact('Sales_data','allbranch', 'today', 'PSTodayStockArr', 'today_date'));
    }


    public function salesbranch($branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $branchwise_data = Sales::where('date', '=', $today)->where('branch_id', '=', $branch_id)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


            $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->where('sales_order', '=', NULL)->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }

            $Sales_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_name' => $branch_name->name,
                'customer_name' => $customer_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'old_balance' => $branchwise_datas->old_balance,
                'grand_total' => $branchwise_datas->grand_total,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'sales_terms' => $sales_terms,
                'status' => $branchwise_datas->status,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


        $PSTodayStockArr = [];

        $sales_branchwise_data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('date', '=', $today)->where('sales_order', '=', NULL)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('date', '=', $today)->where('sales_order', '=', NULL)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


                if($getSalebagcount != 0){
                    $bag_count = $getSalebagcount;
                }else {
                    $bag_count = '';
                }

                if($getSalekgcount != 0){
                    $kg_count = $getSalekgcount;
                }else {
                    $kg_count = '';
                }


                    $productlist_ID = Productlist::findOrFail($sales_productlist);

                    $PSTodayStockArr[] = array(
                        'branch_id' => $Merge_Branchs,
                        'product_name' => $productlist_ID->name,
                        'getSalebagcount' => $bag_count,
                        'getSalekgcount' => $kg_count,
                        'today' => $today,

                    );


            }

        }

        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.sales.index', compact('Sales_data', 'allbranch', 'branch_id', 'today', 'PSTodayStockArr', 'today_date'));
    }


    public function sales_branchdata($today, $branch_id)
    {

        $branchwise_data = Sales::where('date', '=', $today)->where('branch_id', '=', $branch_id)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


            $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->where('sales_order', '=', NULL)->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }

            $Sales_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_name' => $branch_name->name,
                'customer_name' => $customer_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'old_balance' => $branchwise_datas->old_balance,
                'grand_total' => $branchwise_datas->grand_total,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'sales_terms' => $sales_terms,
                'status' => $branchwise_datas->status,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


        $PSTodayStockArr = [];

        $sales_branchwise_data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('date', '=', $today)->where('sales_order', '=', NULL)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('date', '=', $today)->where('sales_order', '=', NULL)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


                if($getSalebagcount != 0){
                    $bag_count = $getSalebagcount;
                }else {
                    $bag_count = '';
                }

                if($getSalekgcount != 0){
                    $kg_count = $getSalekgcount;
                }else {
                    $kg_count = '';
                }


                    $productlist_ID = Productlist::findOrFail($sales_productlist);

                    $PSTodayStockArr[] = array(
                        'branch_id' => $Merge_Branchs,
                        'product_name' => $productlist_ID->name,
                        'getSalebagcount' => $bag_count,
                        'getSalekgcount' => $kg_count,
                        'today' => $today,

                    );


            }

        }

        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.sales.index', compact('Sales_data', 'allbranch', 'branch_id', 'today', 'PSTodayStockArr', 'today_date'));
    }

    public function datefilter(Request $request)
    {

        $today = $request->get('from_date');


        $data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $customer_name = Customer::findOrFail($datas->customer_id);

            $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('sales_order', '=', NULL)->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }



            $Sales_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_name' => $branch_name->name,
                'customer_name' => $customer_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'old_balance' => $datas->old_balance,
                'grand_total' => $datas->grand_total,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'sales_terms' => $sales_terms,
                'status' => $datas->status,
            );
        }

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


        $PSTodayStockArr = [];

        $sales_branchwise_data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = SalesProduct::where('branch_id', '=', $Merge_Branchs)->where('sales_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


                if($getSalebagcount != 0){
                    $bag_count = $getSalebagcount;
                }else {
                    $bag_count = '';
                }

                if($getSalekgcount != 0){
                    $kg_count = $getSalekgcount;
                }else {
                    $kg_count = '';
                }


                    $productlist_ID = Productlist::findOrFail($sales_productlist);

                    $PSTodayStockArr[] = array(
                        'branch_id' => $Merge_Branchs,
                        'product_name' => $productlist_ID->name,
                        'getSalebagcount' => $bag_count,
                        'getSalekgcount' => $kg_count,
                        'today' => $today,

                    );


            }

        }


        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.sales.index', compact('Sales_data','allbranch', 'today', 'PSTodayStockArr', 'today_date'));

    }

    public function create()
    {
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');
        return view('page.backend.sales.create', compact('productlist', 'branch', 'customer', 'today', 'timenow', 'bank'));
    }

    public function store(Request $request)
    {


            $sales_customerid = $request->get('sales_customerid');
            $sales_branch_id = $request->get('sales_branch_id');
            $sales_date = $request->get('sales_date');
            $s_bill_no = 1;

            // Branch
            $GetBranch = Branch::findOrFail($sales_branch_id);
            $Branch_Name = $GetBranch->shop_name;
            $first_three_letter = substr($Branch_Name, 0, 3);
            $branch_upper = strtoupper($first_three_letter);

            //Date
            $billreport_date = date('dmY', strtotime($sales_date));


            $lastreport_OFBranch = Sales::where('branch_id', '=', $sales_branch_id)->where('sales_order', '=', NULL)->where('date', '=', $sales_date)->latest('id')->first();
            if($lastreport_OFBranch != '')
            {
                $added_billno = substr ($lastreport_OFBranch->bill_no, -2);
                $invoiceno = $branch_upper . $billreport_date . 'S0' . ($added_billno) + 1;
            } else {
                $invoiceno = $branch_upper . $billreport_date . 'S0' . $s_bill_no;
            }


            $randomkey = Str::random(5);

            $data = new Sales();

            $data->unique_key = $randomkey;
            $data->customer_id = $request->get('sales_customerid');
            $data->branch_id = $request->get('sales_branch_id');
            $data->date = $request->get('sales_date');
            $data->time = $request->get('sales_time');

            $data->bill_no = $request->get('sales_billno');
            $data->bank_id = $request->get('sales_bank_id');
            $data->total_amount = $request->get('sales_total_amount');
            $data->note = $request->get('sales_extracost_note');
            $data->extra_cost = $request->get('sales_extracost');
            $data->gross_amount = $request->get('sales_gross_amount');
            $data->old_balance = $request->get('sales_old_balance');
            $data->grand_total = $request->get('sales_grand_total');
            $data->paid_amount = $request->get('salespayable_amount');
            $data->balance_amount = $request->get('sales_pending_amount');
            $data->bill_no = $invoiceno;
            $data->status = 1;
            $data->save();

            $insertedId = $data->id;

            // Purchase Products Table
            foreach ($request->get('sales_product_id') as $key => $sales_product_id) {

                $salesprandomkey = Str::random(5);

                $SalesProduct = new SalesProduct;
                $SalesProduct->unique_key = $salesprandomkey;
                $SalesProduct->sales_id = $insertedId;
                $SalesProduct->date = $data->date;
                $SalesProduct->branch_id = $data->branch_id;
                $SalesProduct->productlist_id = $sales_product_id;
                $SalesProduct->bagorkg = $request->sales_bagorkg[$key];
                $SalesProduct->count = $request->sales_count[$key];
                $SalesProduct->price_per_kg = $request->sales_priceperkg[$key];
                $SalesProduct->total_price = $request->sales_total_price[$key];
                $SalesProduct->save();

                $product_ids = $request->sales_product_id[$key];


                $sales_branch_id = $request->get('sales_branch_id');
                $product_Data = Product::where('productlist_id', '=', $product_ids)->where('branchtable_id', '=', $sales_branch_id)->first();

                if($product_Data != ""){
                    if($sales_branch_id == $product_Data->branchtable_id){

                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;


                        if($request->sales_bagorkg[$key] == 'bag'){
                            $totalbag_count = $bag_count - $request->sales_count[$key];
                            $totalkg_count = $kg_count - 0;
                        }else if($request->sales_bagorkg[$key] == 'kg'){
                            $totalkg_count = $kg_count - $request->sales_count[$key];
                            $totalbag_count = $bag_count - 0;
                        }



                        DB::table('products')->where('productlist_id', $product_ids)->where('branchtable_id', $sales_branch_id)->update([
                            'available_stockin_bag' => $totalbag_count,  'available_stockin_kilograms' => $totalkg_count
                        ]);
                    }
                }


            }



            $SalesbranchwiseData = BranchwiseBalance::where('customer_id', '=', $sales_customerid)->where('branch_id', '=', $sales_branch_id)->first();
            if($SalesbranchwiseData != ""){

                $old_grossamount = $SalesbranchwiseData->sales_amount;
                $old_paid = $SalesbranchwiseData->sales_paid;

                $gross_amount = $request->get('sales_gross_amount');
                $payable_amount = $request->get('salespayable_amount');

                $new_grossamount = $old_grossamount + $gross_amount;
                $new_paid = $old_paid + $payable_amount;
                $new_balance = $new_grossamount - $new_paid;

                DB::table('branchwise_balances')->where('customer_id', $sales_customerid)->where('branch_id', $sales_branch_id)->update([
                    'sales_amount' => $new_grossamount,  'sales_paid' => $new_paid, 'sales_balance' => $new_balance
                ]);

            }else {
                $gross_amount = $request->get('sales_gross_amount');
                $payable_amount = $request->get('salespayable_amount');
                $balance_amount = $gross_amount - $payable_amount;

                $data = new BranchwiseBalance();

                $data->customer_id = $sales_customerid;
                $data->branch_id = $sales_branch_id;
                $data->sales_amount = $gross_amount;
                $data->sales_paid = $payable_amount;
                $data->sales_balance = $balance_amount;
                $data->save();
            }



            return redirect()->route('sales.index')->with('add', 'Sales Data added successfully!');






    }


    public function print_view($unique_key)
    {

        $SalesData = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', NULL)->first();

        $customer_idname = Customer::where('id', '=', $SalesData->customer_id)->first();
            $branchname = Branch::where('id', '=', $SalesData->branch_id)->first();
            $bankname = Bank::where('id', '=', $SalesData->bank_id)->first();
            $customer_upper = strtoupper($customer_idname->name);
            $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $SalesProduct_darta = SalesProduct::where('sales_id', '=', $SalesData->id)->where('sales_order', '=', NULL)->get();


        return view('page.backend.sales.print_view', compact('customer_upper', 'SalesData', 'customer_idname', 'branchname', 'bankname', 'SalesProduct_darta', 'productlist'));
    }



    public function edit($unique_key)
    {
        $SalesData = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', NULL)->first();
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $SalesProducts = SalesProduct::where('sales_id', '=', $SalesData->id)->where('sales_order', '=', NULL)->get();

        return view('page.backend.sales.edit', compact('productlist', 'branch', 'customer', 'bank', 'SalesData', 'SalesProducts'));
    }




    public function update(Request $request, $unique_key)
    {




        $Sales_Data = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', NULL)->first();

        $branch_id = $Sales_Data->branch_id;
        $sales_customer_id = $Sales_Data->customer_id;


        $SalesbranchwiseData = BranchwiseBalance::where('customer_id', '=', $sales_customer_id)->where('branch_id', '=', $branch_id)->first();
        if($SalesbranchwiseData != ""){

            $old_grossamount = $SalesbranchwiseData->sales_amount;
            $old_paid = $SalesbranchwiseData->sales_paid;

                $oldentry_grossamount = $Sales_Data->gross_amount;
                $oldentry_paid = $Sales_Data->paid_amount;

                $gross_amount = $request->get('sales_gross_amount');
                $payable_amount = $request->get('salespayable_amount');


                $edited_gross = $old_grossamount - $oldentry_grossamount;
                $new_gross = $edited_gross + $gross_amount;
                $edited_paid = $old_paid - $oldentry_paid;
                $new_paid = $edited_paid + $payable_amount;
                $new_balance = $new_gross - $new_paid;




                DB::table('branchwise_balances')->where('customer_id', $sales_customer_id)->where('branch_id', $branch_id)->update([
                    'sales_amount' => $new_gross,  'sales_paid' => $new_paid, 'sales_balance' => $new_balance
                ]);

            }






        $Sales_Data->total_amount = $request->get('sales_total_amount');
        $Sales_Data->gross_amount = $request->get('sales_gross_amount');
        $Sales_Data->old_balance = $request->get('sales_old_balance');
        $Sales_Data->grand_total = $request->get('sales_grand_total');
        $Sales_Data->paid_amount = $request->get('salespayable_amount');
        $Sales_Data->balance_amount = $request->get('sales_pending_amount');
        $Sales_Data->update();

        $SalesId = $Sales_Data->id;

        // Purchase Products Table



        $getinsertedP_Products = SalesProduct::where('sales_id', '=', $SalesId)->get();
        $Purchaseproducts = array();
        foreach ($getinsertedP_Products as $key => $getinserted_P_Products) {
            $Purchaseproducts[] = $getinserted_P_Products->id;
        }

        $updatedpurchaseproduct_id = $request->sales_detail_id;
        $updated_PurchaseProduct_id = array_filter($updatedpurchaseproduct_id);
        $different_ids = array_merge(array_diff($Purchaseproducts, $updated_PurchaseProduct_id), array_diff($updated_PurchaseProduct_id, $Purchaseproducts));

        if (!empty($different_ids)) {
            foreach ($different_ids as $key => $different_id) {
                SalesProduct::where('id', $different_id)->delete();
            }
        }



        foreach ($request->get('sales_detail_id') as $key => $sales_detail_id) {
            if ($sales_detail_id > 0) {

                $updatesales_product_id = $request->sales_product_id[$key];



                $product_Data = Product::where('soft_delete', '!=', 1)->where('productlist_id', '=', $updatesales_product_id)->where('branchtable_id', '=', $branch_id)->first();
                if($product_Data != ""){
                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;

                    if($request->sales_bagorkg[$key] == 'bag'){

                        $getP_Productbag = SalesProduct::where('id', '=', $sales_detail_id)->where('bagorkg', '=', 'bag')->first();

                        $old_count = $getP_Productbag->count;
                        $new_count = $request->sales_count[$key];

                        if($old_count > $new_count){

                            $total_count = $old_count - $new_count;
                            $stockbag_count = $total_count + $bag_count;

                            DB::table('products')->where('productlist_id', $updatesales_product_id)->where('branchtable_id', $branch_id)->update([
                                'available_stockin_bag' => $stockbag_count
                            ]);

                        }else if($old_count < $new_count){

                            $total_count = $new_count - $old_count;
                            $stockbag_count = $bag_count - $total_count;

                            DB::table('products')->where('productlist_id', $updatesales_product_id)->where('branchtable_id', $branch_id)->update([
                                'available_stockin_bag' => $stockbag_count
                            ]);

                        }
                    }else if($request->sales_bagorkg[$key] == 'kg'){

                        $getP_Productkg = SalesProduct::where('id', '=', $sales_detail_id)->where('bagorkg', '=', 'kg')->first();

                        $oldkg_count = $getP_Productkg->count;
                        $newkg_count = $request->sales_count[$key];

                        if($oldkg_count > $newkg_count){

                            $total_count = $oldkg_count - $newkg_count;
                            $stockkg_count = $total_count + $kg_count;

                            DB::table('products')->where('productlist_id', $updatesales_product_id)->where('branchtable_id', $branch_id)->update([
                                'available_stockin_kilograms' => $stockkg_count
                            ]);

                        }else if($oldkg_count < $newkg_count){

                            $total_count = $newkg_count - $oldkg_count;
                            $stockkg_count = $kg_count - $total_count;

                            DB::table('products')->where('productlist_id', $updatesales_product_id)->where('branchtable_id', $branch_id)->update([
                                'available_stockin_kilograms' => $stockkg_count
                            ]);

                        }
                    }
                }


                $ids = $sales_detail_id;
                $Sales_Id = $SalesId;
                $productlist_id = $request->sales_product_id[$key];
                $bagorkg = $request->sales_bagorkg[$key];
                $count = $request->sales_count[$key];
                $price_per_kg = $request->sales_priceperkg[$key];
                $total_price = $request->sales_total_price[$key];

                DB::table('sales_products')->where('id', $ids)->update([
                    'sales_id' => $Sales_Id,  'productlist_id' => $updatesales_product_id,  'bagorkg' => $bagorkg,  'count' => $count, 'price_per_kg' => $price_per_kg, 'total_price' => $total_price
                ]);

            }else if ($sales_detail_id == '') {


                $salesuprandomkey = Str::random(5);

                $UpdateSalesProduct = new SalesProduct;
                $UpdateSalesProduct->unique_key = $salesuprandomkey;
                $UpdateSalesProduct->sales_id = $SalesId;
                $UpdateSalesProduct->date = $request->get('sales_date');
                $UpdateSalesProduct->branch_id = $branch_id;
                $UpdateSalesProduct->productlist_id = $request->sales_product_id[$key];
                $UpdateSalesProduct->bagorkg = $request->sales_bagorkg[$key];
                $UpdateSalesProduct->count = $request->sales_count[$key];
                $UpdateSalesProduct->price_per_kg = $request->sales_priceperkg[$key];
                $UpdateSalesProduct->total_price = $request->sales_total_price[$key];
                $UpdateSalesProduct->save();

                $product_ids = $request->sales_product_id[$key];


                $product_Data = Product::where('productlist_id', '=', $product_ids)->where('branchtable_id', '=', $branch_id)->first();

                if($product_Data != ""){
                    if($branch_id == $product_Data->branchtable_id){

                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;


                        if($request->sales_bagorkg[$key] == 'bag'){
                            $totalbag_count = $bag_count - $request->sales_count[$key];
                            $totalkg_count = $kg_count - 0;
                        }else if($request->sales_bagorkg[$key] == 'kg'){
                            $totalkg_count = $kg_count - $request->sales_count[$key];
                            $totalbag_count = $bag_count - 0;
                        }



                        DB::table('products')->where('productlist_id', $product_ids)->where('branchtable_id', $branch_id)->update([
                            'available_stockin_bag' => $totalbag_count,  'available_stockin_kilograms' => $totalkg_count
                        ]);
                    }
                }


            }
        }



        return redirect()->route('sales.index')->with('update', 'Updated Sales information has been added to your list.');

    }


    public function invoice($unique_key)
    {
        $SalesData = Sales::where('unique_key', '=', $unique_key)->first();
        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $SalesProducts = SalesProduct::where('sales_id', '=', $SalesData->id)->get();

        return view('page.backend.sales.invoice', compact('productlist', 'branch', 'customer', 'bank', 'SalesData', 'SalesProducts'));
    }


    public function invoice_update(Request $request, $unique_key)
    {

        $branch_id = $request->get('sales_branch_id');


        $Sales_Data = Sales::where('unique_key', '=', $unique_key)->first();

        $Sales_Data->bank_id = $request->get('sales_bank_id');
        $Sales_Data->total_amount = $request->get('sales_total_amount');
        $Sales_Data->note = $request->get('sales_extracost_note');
        $Sales_Data->extra_cost = $request->get('sales_extracost');
        $Sales_Data->gross_amount = $request->get('sales_gross_amount');
        $Sales_Data->old_balance = $request->get('sales_old_balance');
        $Sales_Data->grand_total = $request->get('sales_grand_total');
        $Sales_Data->paid_amount = $request->get('salespayable_amount');
        $Sales_Data->balance_amount = $request->get('sales_pending_amount');
        $Sales_Data->status = 1;
        $Sales_Data->update();

        $SalesId = $Sales_Data->id;

        // Purchase Products Table



        foreach ($request->get('sales_detail_id') as $key => $sales_detail_id) {
            if ($sales_detail_id > 0) {

                $updatesales_product_id = $request->sales_product_id[$key];

                $ids = $sales_detail_id;
                $Sales_Id = $SalesId;
                $price_per_kg = $request->sales_priceperkg[$key];
                $total_price = $request->sales_total_price[$key];

                DB::table('sales_products')->where('id', $ids)->update([
                    'sales_id' => $Sales_Id,  'productlist_id' => $updatesales_product_id, 'price_per_kg' => $price_per_kg, 'total_price' => $total_price
                ]);

            }
        }

        return redirect()->route('sales.index')->with('update', 'Updated Sales information has been added to your list.');

    }



    public function report() {
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $Customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


       // $sales = [];
        $data = Sales::where('soft_delete', '!=', 1)->get();
        foreach ($data as $key => $data_arr) {
            $sales[] = $data_arr;
        }
        $salepayment_s = [];
        $Salespaymentdata = Salespayment::where('soft_delete', '!=', 1)->get();
        foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
            $salepayment_s[] = $Salespaymentdatas;
        }


        $Sales_data = [];
        $sales_terms = [];

        // $merge = array_merge($sales, $salepayment_s);
        // foreach ($merge as $key => $datas) {
        //     $branch_name = Branch::findOrFail($datas->branch_id);
        //     $customer_name = Customer::findOrFail($datas->customer_id);

        //     $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->get();
        //     foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

        //         $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
        //         $sales_terms[] = array(
        //             'bag' => $SalesProducts_arrdata->bagorkg,
        //             'kgs' => $SalesProducts_arrdata->count,
        //             'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
        //             'total_price' => $SalesProducts_arrdata->total_price,
        //             'product_name' => $productlist_ID->name,
        //             'sales_id' => $SalesProducts_arrdata->sales_id,

        //         );
        //     }

        //     if($datas->status != ""){
        //         $paid = $datas->paid_amount;
        //         $balance = $datas->balance_amount;
        //         $type='SALES';
        //     }else {
        //         $paid = $datas->amount + $datas->salespayment_discount;
        //         $balance = $datas->payment_pending;
        //         $type='PAYMENT';
        //     }



        //     $Sales_data[] = array(
        //         'sales_order' => $datas->sales_order,
        //         'unique_key' => $datas->unique_key,
        //         'branch_name' => $branch_name->shop_name,
        //         'customer_name' => $customer_name->name,
        //         'date' => $datas->date,
        //         'time' => $datas->time,
        //         'gross_amount' => $datas->gross_amount,
        //         'grand_total' => $datas->grand_total,
        //         'paid_amount' => $paid,
        //         'balance_amount' => $balance,
        //         'type' => $type,
        //         'bill_no' => $datas->bill_no,
        //         'id' => $datas->id,
        //         'sales_terms' => $sales_terms,
        //         'status' => $datas->status,
        //         'branchheading' => $branch_name->shop_name,
        //         'customerheading' => $customer_name->name,
        //         'fromdateheading' => date('d-M-Y', strtotime($datas->date)),
        //         'todateheading' => date('d-M-Y', strtotime($datas->date)),
        //     );
        // }


        $fromdate = '';
        $todate = '';
        $customer_id = '';
        $branch_id = '';



        return view('page.backend.sales.report', compact('branch', 'Customer', 'Sales_data', 'fromdate', 'todate', 'customer_id', 'branch_id'));
    }



    public function report_view(Request $request)
    {
        $salesreport_fromdate = $request->get('salesreport_fromdate');
        $salesreport_todate = $request->get('salesreport_todate');
        $salesreport_branch = $request->get('salesreport_branch');
        $salesreport_customer = $request->get('salesreport_customer');

        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $Customer = Customer::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        if($salesreport_branch != ""){
            $GetBranch = Branch::findOrFail($salesreport_branch);

            $branchwise_report = Sales::where('branch_id', '=', $salesreport_branch)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ""){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $salesreport_branch)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);


                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }

                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }
                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );

                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => '',
                );
            }


            $fromdate = '';
            $todate = '';
            $customer_id = '';
            $branch_id = $salesreport_branch;
        }





        if($salesreport_customer != ""){
            $GetCustomer = Customer::findOrFail($salesreport_customer);

            $branchwise_report = Sales::where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);


                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }

                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'discount' => $discount,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );



                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => '',

                );
            }

            $fromdate = '';
            $todate = '';
            $customer_id = $salesreport_customer;
            $branch_id = '';


        }





        if($salesreport_fromdate != ""){

            $branchwise_report = Sales::where('date', '=', $salesreport_fromdate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $salesreport_fromdate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);




                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => '',
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => '',
                    'datetime' => '',
                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = '';
            $customer_id = '';
            $branch_id = '';

        }




        if($salesreport_todate != ""){

            $branchwise_report = Sales::where('date', '=', $salesreport_todate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $salesreport_todate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',
                );
            }

            $fromdate = '';
            $todate = $salesreport_todate;
            $customer_id = '';
            $branch_id = '';



        }


        if($salesreport_fromdate && $salesreport_customer){
            $GetCustomer = Customer::findOrFail($salesreport_customer);

            $branchwise_report = Sales::where('date', '=', $salesreport_fromdate)->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $salesreport_fromdate)->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'discount' => $discount,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => '',
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => '',
                    'datetime' => '',
                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = '';
            $customer_id = $salesreport_customer;
            $branch_id = '';



        }





        if($salesreport_fromdate && $salesreport_todate){


            $branchwise_report = Sales::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }



                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }




                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',

                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = $salesreport_todate;
            $customer_id = '';
            $branch_id = '';



        }



        if($salesreport_todate && $salesreport_customer){
            $GetCustomer = Customer::findOrFail($salesreport_customer);

            $branchwise_report = Sales::where('date', '=', $salesreport_todate)->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $salesreport_todate)->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }




                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',
                );
            }
            $fromdate = '';
            $todate = $salesreport_todate;
            $customer_id = $salesreport_customer;
            $branch_id = '';



        }





        if($salesreport_branch && $salesreport_customer){

            $GetBranch = Branch::findOrFail($salesreport_branch);
            $GetCustomer = Customer::findOrFail($salesreport_customer);

            $branchwise_report = Sales::where('branch_id', '=', $salesreport_branch)->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $salesreport_branch)->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => '',
                    'todateheading' => '',
                    'datetime' => '',
                );
            }

            $fromdate = '';
            $todate = '';
            $customer_id = $salesreport_customer;
            $branch_id = $salesreport_branch;

        }



        if($salesreport_branch && $salesreport_fromdate){
            $GetBranch = Branch::findOrFail($salesreport_branch);

            $branchwise_report = Sales::where('branch_id', '=', $salesreport_branch)->where('date', '=', $salesreport_fromdate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $salesreport_branch)->where('date', '=', $salesreport_fromdate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }



                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => '',
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => '',
                    'datetime' => '',
                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = '';
            $customer_id = '';
            $branch_id = $salesreport_branch;

        }




        if($salesreport_branch && $salesreport_todate){
            $GetBranch = Branch::findOrFail($salesreport_branch);

            $branchwise_report = Sales::where('branch_id', '=', $salesreport_branch)->where('date', '=', $salesreport_todate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $salesreport_branch)->where('date', '=', $salesreport_todate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',
                );
            }

            $fromdate = '';
            $todate = $salesreport_todate;
            $customer_id = '';
            $branch_id = $salesreport_branch;

        }




        if($salesreport_fromdate && $salesreport_todate && $salesreport_branch){
            $GetBrach = Branch::findOrFail($salesreport_branch);

            $branchwise_report = Sales::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('branch_id', '=', $salesreport_branch)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('branch_id', '=', $salesreport_branch)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'discount' => $discount,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',
                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = $salesreport_todate;
            $customer_id = '';
            $branch_id = $salesreport_branch;

        }



        if($salesreport_fromdate && $salesreport_todate && $salesreport_customer){

            $GetCustomer = Customer::findOrFail($salesreport_customer);

            $branchwise_report = Sales::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('customer_id', '=', $salesreport_customer)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'discount' => $discount,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',
                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = $salesreport_todate;
            $customer_id = $salesreport_customer;
            $branch_id = '';


        }




        if($salesreport_fromdate && $salesreport_todate && $salesreport_customer && $salesreport_branch){

            $GetCustomer = Customer::findOrFail($salesreport_customer);
            $GetBrach = Branch::findOrFail($salesreport_branch);

            $branchwise_report = Sales::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('customer_id', '=', $salesreport_customer)->where('branch_id', '=', $salesreport_branch)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$salesreport_fromdate, $salesreport_todate])->where('customer_id', '=', $salesreport_customer)->where('branch_id', '=', $salesreport_branch)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }



                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                        $discount = '';
                    }else {
                        $paid = $branchwise_datas->amount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $branchwise_datas->salespayment_discount;
                    }

                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'discount' => $discount,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBrach->shop_name,
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                        'datetime' => $branchwise_datas->date . $branchwise_datas->time,

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBrach->shop_name,
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => date('d-M-Y', strtotime($salesreport_fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($salesreport_todate)),
                    'datetime' => '',
                );
            }

            $fromdate = $salesreport_fromdate;
            $todate = $salesreport_todate;
            $customer_id = $salesreport_customer;
            $branch_id = $salesreport_branch;



        }



        usort($Sales_data, function($a1, $a2) {
            $value1 = strtotime($a1['datetime']);
            $value2 = strtotime($a2['datetime']);
            return ($value1 < $value2) ? 1 : -1;
         });



        return view('page.backend.sales.report', compact('Sales_data', 'branch', 'Customer', 'fromdate', 'todate', 'customer_id', 'branch_id'));


    }




    public function generate_print($unique_key)
    {
        $SalesData = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', NULL)->first();

        if($SalesData->status == 1){
            $SalesData->status = 2;
            $SalesData->update();
        }else if($SalesData->status == 2){
            $SalesData->status = 3;
            $SalesData->update();
        }



        $customer_idname = Customer::where('id', '=', $SalesData->customer_id)->first();
            $branchname = Branch::where('id', '=', $SalesData->branch_id)->first();
            $bankname = Bank::where('id', '=', $SalesData->bank_id)->first();
            $customer_upper = strtoupper($customer_idname->name);
            $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $SalesProduct_darta = SalesProduct::where('sales_id', '=', $SalesData->id)->where('sales_order', '=', NULL)->get();


        return view('page.backend.sales.print_view', compact('customer_upper', 'SalesData', 'customer_idname', 'branchname', 'bankname', 'SalesProduct_darta', 'productlist'));
    }



    public function getoldbalanceforSales()
    {
        $sales_customerid = request()->get('sales_customerid');
        $sales_branch_id = request()->get('sales_branch_id');

        $last_idrow = BranchwiseBalance::where('customer_id', '=', $sales_customerid)->where('branch_id', '=', $sales_branch_id)->first();

        if($last_idrow != ""){
            $output = [];
            if($last_idrow->sales_balance != NULL){
                $output[] = array(
                    'payment_pending' => $last_idrow->sales_balance,
                );
            }


        }else {
            $output[] = array(
                'payment_pending' => 0,
            );
        }



        echo json_encode($output);
    }


    public function getSalesview()
    {
        $sales_id = request()->get('sales_id');
        $get_Sales = Sales::where('soft_delete', '!=', 1)
                                    ->where('id', '=', $sales_id)
                                    ->get();
        $output = [];
        foreach ($get_Sales as $key => $get_Sales_data) {

            $customer_namearr = Customer::where('id', '=', $get_Sales_data->customer_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $branch_namearr = Branch::where('id', '=', $get_Sales_data->branch_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $bank_namearr = Bank::where('id', '=', $get_Sales_data->bank_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            if($bank_namearr != ""){
                $bank_name = $bank_namearr->name;
            }else {
                $bank_name = '';
            }

            $output[] = array(
                'sales_customername' => $customer_namearr->name,
                'sales_customercontact_number' => $customer_namearr->contact_number,
                'sales_customershop_name' => $customer_namearr->shop_name,
                'sales_customershop_address' => $customer_namearr->shop_address,
                'sales_branchname' => $branch_namearr->name,
                'salesbranch_contact_number' => $branch_namearr->contact_number,
                'salesbranch_shop_name' => $branch_namearr->shop_name,
                'salesbranch_address' => $branch_namearr->address,

                'sales_date' => date('d m Y', strtotime($get_Sales_data->date)),
                'sales_time' => date('h:i A', strtotime($get_Sales_data->time)),

                'sales_bank_namedata' => $bank_name,
                'sales_total_amount' => $get_Sales_data->total_amount,
                'sales_extra_cost' => $get_Sales_data->extra_cost,
                'sales_old_balance' => $get_Sales_data->old_balance,
                'sales_grand_total' => $get_Sales_data->grand_total,
                'sales_paid_amount' => $get_Sales_data->paid_amount,
                'sales_balance_amount' => $get_Sales_data->balance_amount,
                'sales_bill_no' => $get_Sales_data->bill_no,
            );
        }

        if (isset($output) & !empty($output)) {
            echo json_encode($output);
        }else{
            echo json_encode(
                array('status' => 'false')
            );
        }

    }




    public function getbranchwiseProducts()
    {

        $sales_branch_id = request()->get('sales_branch_id');

        $GetProduct = Product::where('soft_delete', '!=', 1)->where('branchtable_id', '=', $sales_branch_id)->get();
        $output = [];
        foreach ($GetProduct as $key => $GetProducts) {
            $ProductList = Productlist::findOrFail($GetProducts->productlist_id);


            $output[] = array(
                'productlistid' => $ProductList->id,
                'productlist_name' => $ProductList->name,
                'available_stockin_bag' => $GetProducts->available_stockin_bag,
                'available_stockin_kilograms' => $GetProducts->available_stockin_kilograms,
            );

        }
        echo json_encode($output);
    }

    public function getProductsdetail()
    {
        $sales_product_id = request()->get('sales_product_id');
        $sales_branch_id = request()->get('sales_branch_id');

        $GetProduct[] = Product::where('soft_delete', '!=', 1)->where('productlist_id', '=', $sales_product_id)->where('branchtable_id', '=', $sales_branch_id)->get();
        echo json_encode($GetProduct);
    }



    public function oldbalanceforsalespayment()
    {
        $customer_id = request()->get('spayment_customer_id');
        $branch_id = request()->get('spayment_branch_id');



        $last_idrow = BranchwiseBalance::where('customer_id', '=', $customer_id)->where('branch_id', '=', $branch_id)->first();
        if($last_idrow != ""){

            if($last_idrow->sales_balance != NULL){

                $output[] = array(
                    'payment_pending' => $last_idrow->sales_balance,
                );
            }else {
                $output[] = array(
                    'payment_pending' => 0,
                );


            }
        }else {
            $output[] = array(
                'payment_pending' => 0,
            );
        }




        echo json_encode($output);
    }






    // SALES ORDER


    public function salesorder_index()
    {

        $today = Carbon::now()->format('Y-m-d');
        $data = Sales::where('date', '=', $today)->where('sales_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $customer_name = Customer::findOrFail($datas->customer_id);

            $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('sales_order', '=', '1')->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'note' => $SalesProducts_arrdata->note,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }



            $Sales_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'sales_terms' => $sales_terms,
                'status' => $datas->status,
            );
        }

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.salesorder.salesorder_index', compact('Sales_data','allbranch', 'today', 'today_date'));
    }


    public function salesorder_datefilter(Request $request)
    {

        $today = $request->get('from_date');


        $data = Sales::where('date', '=', $today)->where('sales_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $customer_name = Customer::findOrFail($datas->customer_id);

            $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('sales_order', '=', '1')->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'note' => $SalesProducts_arrdata->note,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }



            $Sales_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'sales_terms' => $sales_terms,
                'status' => $datas->status,
            );
        }

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.salesorder.salesorder_index', compact('Sales_data','allbranch', 'today',  'today_date'));

    }


    public function salesorderbranch($branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $branchwise_data = Sales::where('date', '=', $today)->where('branch_id', '=', $branch_id)->where('sales_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


            $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->where('sales_order', '=', '1')->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'note' => $SalesProducts_arrdata->note,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }

            $Sales_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'sales_terms' => $sales_terms,
                'status' => $branchwise_datas->status,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.salesorder.salesorder_index', compact('Sales_data', 'allbranch', 'branch_id', 'today', 'today_date'));
    }


    public function salesorder_branchdata($today, $branch_id)
    {

        $branchwise_data = Sales::where('date', '=', $today)->where('branch_id', '=', $branch_id)->where('sales_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


            $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->where('sales_order', '=', '1')->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'note' => $SalesProducts_arrdata->note,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }

            $Sales_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'sales_terms' => $sales_terms,
                'status' => $branchwise_datas->status,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $today_date = Carbon::now()->format('Y-m-d');
        return view('page.backend.salesorder.salesorder_index', compact('Sales_data', 'allbranch', 'branch_id', 'today', 'today_date'));
    }


    public function salesorder_create()
    {
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');
        return view('page.backend.salesorder.salesorder_create', compact('productlist', 'branch', 'customer', 'today', 'timenow', 'bank'));
    }


    public function salesorder_store(Request $request)
    {


            $sales_customerid = $request->get('sales_customerid');
            $sales_branch_id = $request->get('sales_branch_id');
            $sales_date = $request->get('sales_date');
            $s_bill_no = 1;

            if($request->get('salespayable_amount') != ""){
                $payment = $request->get('salespayable_amount');
            }else {
                $payment = 0;
            }

            // Branch
            $GetBranch = Branch::findOrFail($sales_branch_id);
            $Branch_Name = $GetBranch->shop_name;
            $first_three_letter = substr($Branch_Name, 0, 3);
            $branch_upper = strtoupper($first_three_letter);

            //Date
            $billreport_date = date('dmY', strtotime($sales_date));


            $lastreport_OFBranch = Sales::where('branch_id', '=', $sales_branch_id)->where('sales_order', '=', '1')->latest('id')->first();
            if($lastreport_OFBranch != '')
            {
                $added_billno = substr ($lastreport_OFBranch->bill_no, -2);
                $invoiceno = '0' . ($added_billno) + 1;
            } else {
                $invoiceno = '0' . $s_bill_no;
            }


            $randomkey = Str::random(5);

            $data = new Sales();

            $data->unique_key = $randomkey;
            $data->customer_id = $request->get('sales_customerid');
            $data->branch_id = $request->get('sales_branch_id');
            $data->date = $request->get('sales_date');
            $data->time = $request->get('sales_time');

            $data->bill_no = $request->get('sales_billno');
            $data->bank_id = $request->get('sales_bank_id');
            $data->total_amount = $request->get('sales_total_amount');
            $data->note = $request->get('sales_extracost_note');
            $data->extra_cost = $request->get('sales_extracost');
            $data->gross_amount = $request->get('sales_gross_amount');
            $data->old_balance = $request->get('sales_old_balance');
            $data->grand_total = $request->get('sales_grand_total');

            

            $data->paid_amount = $payment;
            $data->balance_amount = $request->get('sales_pending_amount');
            $data->bill_no = $invoiceno;
            $data->sales_order = 1;
            $data->status = 1;
            $data->save();

            $insertedId = $data->id;

            // Purchase Products Table
            foreach ($request->get('sales_product_id') as $key => $sales_product_id) {

                $salesprandomkey = Str::random(5);

                $SalesProduct = new SalesProduct;
                $SalesProduct->unique_key = $salesprandomkey;
                $SalesProduct->sales_id = $insertedId;
                $SalesProduct->date = $data->date;
                $SalesProduct->branch_id = $data->branch_id;
                $SalesProduct->productlist_id = $sales_product_id;
                $SalesProduct->bagorkg = $request->sales_bagorkg[$key];
                $SalesProduct->count = $request->sales_count[$key];
                $SalesProduct->note = $request->sales_note[$key];
                $SalesProduct->price_per_kg = $request->sales_priceperkg[$key];
                $SalesProduct->total_price = $request->sales_total_price[$key];
                $SalesProduct->sales_order = 1;
                $SalesProduct->save();

            }



            $SalesbranchwiseData = BranchwiseBalance::where('customer_id', '=', $sales_customerid)->where('branch_id', '=', $sales_branch_id)->first();
            if($SalesbranchwiseData != ""){

                $old_grossamount = $SalesbranchwiseData->sales_amount;
                $old_paid = $SalesbranchwiseData->sales_paid;

                $gross_amount = $request->get('sales_gross_amount');
                $payable_amount = $request->get('salespayable_amount');

                $new_grossamount = $old_grossamount + $gross_amount;
                $new_paid = $old_paid + $payable_amount;
                $new_balance = $new_grossamount - $new_paid;

                DB::table('branchwise_balances')->where('customer_id', $sales_customerid)->where('branch_id', $sales_branch_id)->update([
                    'sales_amount' => $new_grossamount,  'sales_paid' => $new_paid, 'sales_balance' => $new_balance
                ]);

            }else {
                $gross_amount = $request->get('sales_gross_amount');
                $payable_amount = $request->get('salespayable_amount');
                $balance_amount = $gross_amount - $payment;

                $data = new BranchwiseBalance();

                $data->customer_id = $sales_customerid;
                $data->branch_id = $sales_branch_id;
                $data->sales_amount = $gross_amount;
                $data->sales_paid = $payment;
                $data->sales_balance = $balance_amount;
                $data->save();
            }



            return redirect()->route('salesorder.salesorder_index')->with('add', 'Sales Data added successfully!');
    }



    public function salesorder_edit($unique_key)
    {
        $SalesData = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', '1')->first();
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $customer = Customer::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $SalesProducts = SalesProduct::where('sales_id', '=', $SalesData->id)->where('sales_order', '=', '1')->get();

        return view('page.backend.salesorder.salesorder_edit', compact('productlist', 'branch', 'customer', 'bank', 'SalesData', 'SalesProducts'));
    }


    public function salesorder_update(Request $request, $unique_key)
    {




        $Sales_Data = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', '1')->first();

        $branch_id = $Sales_Data->branch_id;
        $sales_customer_id = $Sales_Data->customer_id;


        $SalesbranchwiseData = BranchwiseBalance::where('customer_id', '=', $sales_customer_id)->where('branch_id', '=', $branch_id)->first();
        if($SalesbranchwiseData != ""){

            $old_grossamount = $SalesbranchwiseData->sales_amount;
            $old_paid = $SalesbranchwiseData->sales_paid;

                $oldentry_grossamount = $Sales_Data->gross_amount;
                $oldentry_paid = $Sales_Data->paid_amount;

                $gross_amount = $request->get('sales_gross_amount');
                $payable_amount = $request->get('salespayable_amount');


               $editedgross = $old_grossamount - $oldentry_grossamount;
               $edited_paid = $old_paid - $oldentry_paid;

               $new_gross = $editedgross + $gross_amount;
               $new_paid = $edited_paid + $payable_amount;
               $newbalance = $new_gross - $new_paid;




                DB::table('branchwise_balances')->where('customer_id', $sales_customer_id)->where('branch_id', $branch_id)->update([
                    'sales_amount' => $new_gross,  'sales_paid' => $new_paid, 'sales_balance' => $newbalance
                ]);

        }
        $Sales_Data->date = $request->get('sales_date');
        $Sales_Data->time = $request->get('sales_time');
        $Sales_Data->bank_id = $request->get('sales_bank_id');
        $Sales_Data->total_amount = $request->get('sales_total_amount');
        $Sales_Data->note = $request->get('sales_extracost_note');
        $Sales_Data->extra_cost = $request->get('sales_extracost');
        $Sales_Data->gross_amount = $request->get('sales_gross_amount');
        $Sales_Data->old_balance = $request->get('sales_old_balance');
        $Sales_Data->grand_total = $request->get('sales_grand_total');
        $Sales_Data->paid_amount = $request->get('salespayable_amount');
        $Sales_Data->balance_amount = $request->get('sales_pending_amount');
        $Sales_Data->update();

        $SalesId = $Sales_Data->id;

        // Purchase Products Table

        $getinsertedP_Products = SalesProduct::where('sales_id', '=', $SalesId)->get();
        $Purchaseproducts = array();
        foreach ($getinsertedP_Products as $key => $getinserted_P_Products) {
            $Purchaseproducts[] = $getinserted_P_Products->id;
        }

        $updatedpurchaseproduct_id = $request->sales_detail_id;
        $updated_PurchaseProduct_id = array_filter($updatedpurchaseproduct_id);
        $different_ids = array_merge(array_diff($Purchaseproducts, $updated_PurchaseProduct_id), array_diff($updated_PurchaseProduct_id, $Purchaseproducts));

        if (!empty($different_ids)) {
            foreach ($different_ids as $key => $different_id) {
                SalesProduct::where('id', $different_id)->delete();
            }
        }


        foreach ($request->get('sales_detail_id') as $key => $sales_detail_id) {
            if ($sales_detail_id > 0) {

                $updatesales_product_id = $request->sales_product_id[$key];

                $ids = $sales_detail_id;
                $productlist_id = $request->sales_product_id[$key];
                $bagorkg = $request->sales_bagorkg[$key];
                $count = $request->sales_count[$key];
                $sales_note = $request->sales_note[$key];
                $price_per_kg = $request->sales_priceperkg[$key];
                $total_price = $request->sales_total_price[$key];

                DB::table('sales_products')->where('id', $ids)->update([
                    'sales_id' => $SalesId,  
                    'date' => $request->get('sales_date'),  
                    'productlist_id' => $updatesales_product_id,  
                    'bagorkg' => $bagorkg,  
                    'count' => $count,   
                    'note' => $sales_note, 
                    'price_per_kg' => $price_per_kg, 
                    'total_price' => $total_price
                ]);

            } else if ($sales_detail_id == '') {
                $salesprandomkey = Str::random(5);

                $SalesProduct = new SalesProduct;
                $SalesProduct->unique_key = $salesprandomkey;
                $SalesProduct->sales_id = $SalesId;
                $SalesProduct->date = $request->get('sales_date');
                $SalesProduct->branch_id = $request->get('sales_branch_id');
                $SalesProduct->productlist_id = $request->sales_product_id[$key];
                $SalesProduct->bagorkg = $request->sales_bagorkg[$key];
                $SalesProduct->count = $request->sales_count[$key];
                $SalesProduct->note = $request->sales_note[$key];
                $SalesProduct->price_per_kg = $request->sales_priceperkg[$key];
                $SalesProduct->total_price = $request->sales_total_price[$key];
                $SalesProduct->sales_order = 1;
                $SalesProduct->save();
            }
        }



        return redirect()->route('salesorder.salesorder_index')->with('update', 'Updated Sales information has been added to your list.');

    }



    public function salesorderview()
    {
        $sales_id = request()->get('sales_id');
        $get_Sales = Sales::where('soft_delete', '!=', 1)->where('sales_order', '=', '1')
                                    ->where('id', '=', $sales_id)
                                    ->get();
        $output = [];
        foreach ($get_Sales as $key => $get_Sales_data) {

            $customer_namearr = Customer::where('id', '=', $get_Sales_data->customer_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $branch_namearr = Branch::where('id', '=', $get_Sales_data->branch_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $bank_namearr = Bank::where('id', '=', $get_Sales_data->bank_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            if($bank_namearr != ""){
                $bank_name = $bank_namearr->name;
            }else {
                $bank_name = '';
            }

            $output[] = array(
                'sales_customername' => $customer_namearr->name,
                'sales_customercontact_number' => $customer_namearr->contact_number,
                'sales_customershop_name' => $customer_namearr->shop_name,
                'sales_customershop_address' => $customer_namearr->shop_address,
                'sales_branchname' => $branch_namearr->name,
                'salesbranch_contact_number' => $branch_namearr->contact_number,
                'salesbranch_shop_name' => $branch_namearr->shop_name,
                'salesbranch_address' => $branch_namearr->address,

                'sales_date' => date('d m Y', strtotime($get_Sales_data->date)),
                'sales_time' => date('h:i A', strtotime($get_Sales_data->time)),

                'sales_bank_namedata' => $bank_name,
                'sales_total_amount' => $get_Sales_data->total_amount,
                'sales_extra_cost' => $get_Sales_data->extra_cost,
                'sales_gross_amount' => $get_Sales_data->gross_amount,
                'sales_old_balance' => $get_Sales_data->old_balance,
                'sales_grand_total' => $get_Sales_data->grand_total,
                'sales_paid_amount' => $get_Sales_data->paid_amount,
                'sales_balance_amount' => $get_Sales_data->balance_amount,
                'sales_bill_no' => $get_Sales_data->bill_no,
            );
        }

        if (isset($output) & !empty($output)) {
            echo json_encode($output);
        }else{
            echo json_encode(
                array('status' => 'false')
            );
        }

    }



    public function salesorder_printview($unique_key)
    {

        $SalesData = Sales::where('unique_key', '=', $unique_key)->where('sales_order', '=', '1')->first();

        $customer_idname = Customer::where('id', '=', $SalesData->customer_id)->first();
            $branchname = Branch::where('id', '=', $SalesData->branch_id)->first();
            $bankname = Bank::where('id', '=', $SalesData->bank_id)->first();
            $customer_upper = strtoupper($customer_idname->name);
            $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            $SalesProduct_darta = SalesProduct::where('sales_id', '=', $SalesData->id)->where('sales_order', '=', '1')->get();

        return view('page.backend.salesorder.salesorder_printview', compact('customer_upper', 'SalesData', 'customer_idname', 'branchname', 'bankname', 'SalesProduct_darta', 'productlist'));
    }









    public function f_sales_pdfexport($fromdate)
    {
        if($fromdate != ""){

            $branchwise_report = Sales::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }



                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => '',

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => '',
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


            $pdf = Pdf::loadView('page.backend.sales.f_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => $fromdate,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }


    public function t_sales_pdfexport($todate)
    {
        if($todate != ""){

            $branchwise_report = Sales::where('date', '=', $todate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $todate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);




                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($todate)),
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });

            $pdf = Pdf::loadView('page.backend.sales.t_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'todate' => $todate,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);

        }
    }


    public function b_sales_pdfexport($branch_id)
    {
        if($branch_id != ""){
            $GetBranch = Branch::findOrFail($branch_id);

            $branchwise_report = Sales::where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ""){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }

                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => '',

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => '',
                );
            }


            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


            $pdf = Pdf::loadView('page.backend.sales.b_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'branch' => $GetBranch->shop_name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }


    public function c_sales_pdfexport($customer_id)
    {
        if($customer_id != ""){
            $GetCustomer = Customer::findOrFail($customer_id);

            $branchwise_report = Sales::where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }



                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'old_balance' => $branchwise_datas->old_balance,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => '',
                        'todateheading' => '',

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => '',
                    'todateheading' => '',

                );
            }


            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });

            $pdf = Pdf::loadView('page.backend.sales.c_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'customer' => $GetCustomer->name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }



    public function ft_sales_pdfexport($fromdate, $todate)
    {
        if($fromdate && $todate){


            $branchwise_report = Sales::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$fromdate, $todate])->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);




                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }



                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($todate)),

                );
            }


            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });

            $pdf = Pdf::loadView('page.backend.sales.ft_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => date('d M Y', strtotime($fromdate)),
                'todate' => date('d M Y', strtotime($todate)),
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);

        }
    }


    public function fb_sales_pdfexport($fromdate, $branch_id)
    {
        if($branch_id && $fromdate){
            $GetBranch = Branch::findOrFail($branch_id);

            $branchwise_report = Sales::where('branch_id', '=', $branch_id)->where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $branch_id)->where('date', '=', $fromdate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }




                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => '',

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => '',
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });

            $pdf = Pdf::loadView('page.backend.sales.fb_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => date('d M Y', strtotime($fromdate)),
                'branch' => $GetBranch->shop_name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }


    public function fc_sales_pdfexport($fromdate, $customer_id)
    {
        if($fromdate && $customer_id){
            $GetCustomer = Customer::findOrFail($customer_id);

            $branchwise_report = Sales::where('date', '=', $fromdate)->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $fromdate)->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);


                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => '',

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => '',
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


            $pdf = Pdf::loadView('page.backend.sales.fc_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => date('d M Y', strtotime($fromdate)),
                'customer' => $GetCustomer->name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);


        }
    }


    public function tb_sales_pdfexport($todate, $branch_id)
    {
        if($branch_id && $todate){
            $GetBranch = Branch::findOrFail($branch_id);

            $branchwise_report = Sales::where('branch_id', '=', $branch_id)->where('date', '=', $todate)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $branch_id)->where('date', '=', $todate)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($todate)),
                );
            }


            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });

            $pdf = Pdf::loadView('page.backend.sales.tb_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'todate' => date('d M Y', strtotime($todate)),
                'branch' => $GetBranch->shop_name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }


    public function tc_sales_pdfexport($todate, $customer_id)
    {
        if($todate && $customer_id){
            $GetCustomer = Customer::findOrFail($customer_id);

            $branchwise_report = Sales::where('date', '=', $todate)->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('date', '=', $todate)->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => '',
                    'todateheading' => date('d-M-Y', strtotime($todate)),
                );
            }



            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });



            $pdf = Pdf::loadView('page.backend.sales.tc_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'todate' => date('d M Y', strtotime($todate)),
                'customer' => $GetCustomer->name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);

        }

    }


    public function bc_sales_pdfexport($branch_id, $customer_id)
    {
        if($branch_id && $customer_id){

            $GetBranch = Branch::findOrFail($branch_id);
            $GetCustomer = Customer::findOrFail($customer_id);

            $branchwise_report = Sales::where('branch_id', '=', $branch_id)->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::where('branch_id', '=', $branch_id)->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBranch->shop_name,
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => '',
                        'todateheading' => '',

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBranch->shop_name,
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => '',
                    'todateheading' => '',
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


            $pdf = Pdf::loadView('page.backend.sales.bc_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'customer' => $GetCustomer->name,
                'branch' => $GetBranch->shop_name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }


    public function ftc_sales_pdfexport($fromdate, $todate, $customer_id)
    {
        if($fromdate && $todate && $customer_id){

            $GetCustomer = Customer::findOrFail($customer_id);

            $branchwise_report = Sales::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $customer_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }

                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }


                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $branch_name->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => '',
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => '',
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($todate)),
                );
            }



            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });

            $pdf = Pdf::loadView('page.backend.sales.ftc_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => date('d M Y', strtotime($fromdate)),
                'todate' => date('d M Y', strtotime($todate)),
                'customer' => $GetCustomer->name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);

        }
    }


    public function ftb_sales_pdfexport($fromdate, $todate, $branch_id)
    {
        if($fromdate && $todate && $branch_id){
            $GetBrach = Branch::findOrFail($branch_id);

            $branchwise_report = Sales::whereBetween('date', [$fromdate, $todate])->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){

                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$fromdate, $todate])->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }


                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $GetBrach->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBrach->shop_name,
                        'customerheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBrach->shop_name,
                    'customerheading' => '',
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($todate)),
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


            $pdf = Pdf::loadView('page.backend.sales.ftb_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => date('d M Y', strtotime($fromdate)),
                'todate' => date('d M Y', strtotime($todate)),
                'branch' => $GetBrach->shop_name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);
        }
    }


    public function ftbc_sales_pdfexport($fromdate, $todate, $branch_id, $customer_id)
    {
        if($fromdate && $todate && $customer_id && $branch_id){

            $GetCustomer = Customer::findOrFail($customer_id);
            $GetBrach = Branch::findOrFail($branch_id);

            $branchwise_report = Sales::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $customer_id)->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
            $Sales_data = [];
            if($branchwise_report != ''){


                $sales = [];
                foreach ($branchwise_report as $key => $data_arr) {
                    $sales[] = $data_arr;
                }
                $salepayment_s = [];
                $Salespaymentdata = Salespayment::whereBetween('date', [$fromdate, $todate])->where('customer_id', '=', $customer_id)->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
                foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                    $salepayment_s[] = $Salespaymentdatas;
                }


                $Sales_data = [];
                $sales_terms = [];

                $merge = array_merge($sales, $salepayment_s);



                $sales_terms = [];
                foreach ($merge as $key => $branchwise_datas) {
                    $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
                    $customer_name = Customer::findOrFail($branchwise_datas->customer_id);


                    $SalesProducts = SalesProduct::where('sales_id', '=', $branchwise_datas->id)->get();
                    foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                        $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                        $sales_terms[] = array(
                            'bag' => $SalesProducts_arrdata->bagorkg,
                            'kgs' => $SalesProducts_arrdata->count,
                            'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                            'total_price' => $SalesProducts_arrdata->total_price,
                            'product_name' => $productlist_ID->name,
                            'sales_id' => $SalesProducts_arrdata->sales_id,

                        );
                    }




                    if($branchwise_datas->status != ""){
                        $paid = $branchwise_datas->paid_amount;
                        $balance = $branchwise_datas->balance_amount;
                        $type='SALES';
                    }else {
                        $paid = $branchwise_datas->amount + $branchwise_datas->salespayment_discount;
                        $balance = $branchwise_datas->payment_pending;
                        $type='PAYMENT';
                    }



                    $Sales_data[] = array(
                        'sales_order' => $branchwise_datas->sales_order,
                        'unique_key' => $branchwise_datas->unique_key,
                        'branch_name' => $GetBrach->shop_name,
                        'customer_name' => $customer_name->name,
                        'date' => $branchwise_datas->date,
                        'time' => $branchwise_datas->time,
                        'gross_amount' => $branchwise_datas->gross_amount,
                        'grand_total' => $branchwise_datas->grand_total,
                        'paid_amount' => $paid,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'bill_no' => $branchwise_datas->bill_no,
                        'id' => $branchwise_datas->id,
                        'sales_terms' => $sales_terms,
                        'status' => $branchwise_datas->status,
                        'branchheading' => $GetBrach->shop_name,
                        'customerheading' => $GetCustomer->name,
                        'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($todate)),

                    );
                }
            }else{

                $Sales_data[] = array(
                    'unique_key' => '',
                    'branch_name' => '',
                    'customer_name' => '',
                    'date' => '',
                    'time' => '',
                    'gross_amount' => '',
                    'grand_total' => '',
                    'paid_amount' => '',
                    'balance_amount' => '',
                    'bill_no' => '',
                    'id' => '',
                    'sales_terms' => '',
                    'status' => '',
                    'branchheading' => $GetBrach->shop_name,
                    'customerheading' => $GetCustomer->name,
                    'fromdateheading' => date('d-M-Y', strtotime($fromdate)),
                    'todateheading' => date('d-M-Y', strtotime($todate)),
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


            $pdf = Pdf::loadView('page.backend.sales.ftbc_pdfexport_view', [
                'Sales_data' => $Sales_data,
                'fromdate' => date('d M Y', strtotime($fromdate)),
                'todate' => date('d M Y', strtotime($todate)),
                'branch' => $GetBrach->shop_name,
                'customer' => $GetCustomer->name,
            ]);


            $name = 'SalesReport.' . 'pdf';

                return $pdf->stream($name);

        }
    }




    public function sales_pdfexport()
    {


            $data = Sales::where('soft_delete', '!=', 1)->get();

            foreach ($data as $key => $data_arr) {
                $sales[] = $data_arr;
            }
            $salepayment_s = [];
            $Salespaymentdata = Salespayment::where('soft_delete', '!=', 1)->get();
            foreach ($Salespaymentdata as $key => $Salespaymentdatas) {
                $salepayment_s[] = $Salespaymentdatas;
            }


            $Sales_data = [];
            $sales_terms = [];

            $merge = array_merge($sales, $salepayment_s);


            foreach ($merge as $key => $datas) {
                $branch_name = Branch::findOrFail($datas->branch_id);
                $customer_name = Customer::findOrFail($datas->customer_id);

                $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->get();
                foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                    $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                    $sales_terms[] = array(
                        'bag' => $SalesProducts_arrdata->bagorkg,
                        'kgs' => $SalesProducts_arrdata->count,
                        'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                        'total_price' => $SalesProducts_arrdata->total_price,
                        'product_name' => $productlist_ID->name,
                        'sales_id' => $SalesProducts_arrdata->sales_id,

                    );
                }


                if($datas->status != ""){
                    $paid = $datas->paid_amount;
                    $balance = $datas->balance_amount;
                    $type='SALES';
                }else {
                    $paid = $datas->amount + $datas->salespayment_discount;
                    $balance = $datas->payment_pending;
                    $type='PAYMENT';
                }




                $Sales_data[] = array(
                    'sales_order' => $datas->sales_order,
                    'unique_key' => $datas->unique_key,
                    'branch_name' => $branch_name->shop_name,
                    'customer_name' => $customer_name->name,
                    'date' => $datas->date,
                    'time' => $datas->time,
                    'gross_amount' => $datas->gross_amount,
                    'grand_total' => $datas->grand_total,
                    'paid_amount' => $paid,
                    'balance_amount' => $balance,
                    'type' => $type,
                    'bill_no' => $datas->bill_no,
                    'id' => $datas->id,
                    'sales_terms' => $sales_terms,
                    'status' => $datas->status,
                    'branchheading' => $branch_name->shop_name,
                    'customerheading' => $customer_name->name,
                    'fromdateheading' => date('d-M-Y', strtotime($datas->date)),
                    'todateheading' => date('d-M-Y', strtotime($datas->date)),
                );
            }

            usort($Sales_data, function($a1, $a2) {
                $value1 = strtotime($a1['date']);
                $value2 = strtotime($a2['date']);
                return $value1 - $value2;
             });


        $pdf = Pdf::loadView('page.backend.sales.pdfexport_view', [
            'Sales_data' => $Sales_data,
        ]);


        $name = 'SalesReport.' . 'pdf';

            return $pdf->stream($name);

    }





    public function salesindex_pdfexport($today)
    {
        $data = Sales::where('date', '=', $today)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $customer_name = Customer::findOrFail($datas->customer_id);

            $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('sales_order', '=', NULL)->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }



            $Sales_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'old_balance' => $datas->old_balance,
                'grand_total' => $datas->grand_total,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'sales_terms' => $sales_terms,
                'status' => $datas->status,
            );
        }


        $pdf = Pdf::loadView('page.backend.sales.pdf.salesindex_pdfexport', [
            'Sales_data' => $Sales_data,
            'today' => date('d-m-Y', strtotime($today)),
        ]);

        $name = 'sales.' . 'pdf';

        return $pdf->stream($name);
    }



    public function salesindex_pdfexport_branchwise($last_word, $today)
    {
        $data = Sales::where('date', '=', $today)->where('branch_id', '=', $last_word)->where('sales_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_data = [];
        $sales_terms = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $customer_name = Customer::findOrFail($datas->customer_id);

            $SalesProducts = SalesProduct::where('sales_id', '=', $datas->id)->where('sales_order', '=', NULL)->get();
            foreach ($SalesProducts as $key => $SalesProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($SalesProducts_arrdata->productlist_id);
                $sales_terms[] = array(
                    'bag' => $SalesProducts_arrdata->bagorkg,
                    'kgs' => $SalesProducts_arrdata->count,
                    'price_per_kg' => $SalesProducts_arrdata->price_per_kg,
                    'total_price' => $SalesProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'sales_id' => $SalesProducts_arrdata->sales_id,

                );
            }



            $Sales_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_name' => $branch_name->shop_name,
                'customer_name' => $customer_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'old_balance' => $datas->old_balance,
                'grand_total' => $datas->grand_total,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'sales_terms' => $sales_terms,
                'status' => $datas->status,
            );
        }


        $pdf = Pdf::loadView('page.backend.sales.pdf.salesindex_pdfexport', [
            'Sales_data' => $Sales_data,
            'today' => date('d-m-Y', strtotime($today)),
        ]);

        $name = 'sales.' . 'pdf';

        return $pdf->stream($name);
    }

}



