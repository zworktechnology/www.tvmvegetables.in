<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Branch;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\SalesProduct;
use App\Models\PurchaseProduct;
use App\Models\PurchasePayment;
use App\Models\PurchaseExtracost;
use App\Models\BranchwiseBalance;
use App\Models\Bank;
use App\Models\Productlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index()
    {

        $today = Carbon::now()->format('Y-m-d');
        $data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];
        $lastpattiyalDate = [];
        $lastpattiyalid = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $supplier_name = Supplier::findOrFail($datas->supplier_id);

            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);
                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );

            }


            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

                );

            }



            $purchase_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_id' => $datas->branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_name' => $supplier_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'supplier_id' => $datas->supplier_id,
                'bank_id' => $datas->bank_id,
                'status' => $datas->status,
                'terms' => $terms,
                'Extracost_Arr' => $Extracost_Arr,
                'supplier_id' => $datas->supplier_id,
            );
        }



        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


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

        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');

        $today_date = Carbon::now()->format('Y-m-d');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        return view('page.backend.purchase.index', compact('purchase_data', 'today', 'productlist', 'allbranch', 'branch', 'supplier', 'timenow', 'bank', 'PSTodayStockArr', 'today_date'));
    }

    public function purchasebranch($branch_id)
    {

        $today = Carbon::now()->format('Y-m-d');
        $branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];
        $lastpattiyalDate = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $supplier_name = Supplier::findOrFail($branchwise_datas->supplier_id);


            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);

                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );
            }

            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

                );

            }



           // dd($null_status);



            $purchase_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_id' => $branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_name' => $supplier_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'terms' => $terms,
                'status' => $branchwise_datas->status,
                'Extracost_Arr' => $Extracost_Arr,
                'supplier_id' => $branchwise_datas->supplier_id,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


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



        return view('page.backend.purchase.index', compact('purchase_data', 'allbranch', 'branch_id', 'today', 'PSTodayStockArr', 'today_date'));
    }


    public function purchase_branchdata($today, $branch_id)
    {


        $branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];
        $lastpattiyalDate = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $supplier_name = Supplier::findOrFail($branchwise_datas->supplier_id);


            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);

                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );
            }

            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

                );

            }

           // dd($null_status);



            $purchase_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_id' => $branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_name' => $supplier_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'terms' => $terms,
                'status' => $branchwise_datas->status,
                'Extracost_Arr' => $Extracost_Arr,
                'supplier_id' => $branchwise_datas->supplier_id,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


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
        return view('page.backend.purchase.index', compact('purchase_data', 'allbranch', 'branch_id', 'today', 'PSTodayStockArr', 'today_date'));
    }


    public function datefilter(Request $request) {


        $today = $request->get('from_date');



        $data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];
        $lastpattiyalDate = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $supplier_name = Supplier::findOrFail($datas->supplier_id);

            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);
                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );
            }


            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', NULL)->get();
            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

                );

            }
            //dd($null_grossarr);


            $purchase_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_id' => $datas->branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_id' => $datas->supplier_id,
                'supplier_name' => $supplier_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'terms' => $terms,
                'Extracost_Arr' => $Extracost_Arr,
                'status' => $datas->status,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', NULL)->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', NULL)->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('date', '=', $today)->where('purchase_order', '=', NULL)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


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
        return view('page.backend.purchase.index', compact('purchase_data', 'allbranch', 'today', 'PSTodayStockArr', 'today_date'));

    }


    public function create()
    {
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');



        return view('page.backend.purchase.create', compact('productlist', 'branch', 'supplier', 'today', 'timenow', 'bank'));
    }



    public function store(Request $request)
    {
            $randomkey = Str::random(5);

            $supplier_id = $request->get('supplier_id');


            $bill_branchid = $request->get('branch_id');
            $bill_date = $request->get('date');
            $s_bill_no = 1;

            // Branch
            $GetBranch = Branch::findOrFail($bill_branchid);
            $Branch_Name = $GetBranch->shop_name;
            $first_three_letter = substr($Branch_Name, 0, 3);
            $branch_upper = strtoupper($first_three_letter);

            //Date
            $billreport_date = date('dmY', strtotime($bill_date));


            $lastreport_OFBranch = Purchase::where('branch_id', '=', $bill_branchid)->where('date', '=', $bill_date)->latest('id')->first();
            if($lastreport_OFBranch != '')
            {
                $added_billno = substr ($lastreport_OFBranch->bill_no, -2);
                $invoiceno = $branch_upper . $billreport_date . 'P0' . ($added_billno) + 1;
            } else {
                $invoiceno = $branch_upper . $billreport_date . 'P0' . $s_bill_no;
            }



            $data = new Purchase();

            $data->unique_key = $randomkey;
            $data->supplier_id = $request->get('supplier_id');
            $data->branch_id = $request->get('branch_id');
            $data->date = $request->get('date');
            $data->time = $request->get('time');
            $data->bill_no = $invoiceno;
            $data->save();

            $insertedId = $data->id;

            // Purchase Products Table
            foreach ($request->get('product_id') as $key => $product_id) {

                $pprandomkey = Str::random(5);

                $PurchaseProduct = new PurchaseProduct;
                $PurchaseProduct->unique_key = $pprandomkey;
                $PurchaseProduct->purchase_id = $insertedId;
                $PurchaseProduct->date = $data->date;
                $PurchaseProduct->branch_id = $data->branch_id;
                $PurchaseProduct->productlist_id = $product_id;
                $PurchaseProduct->bagorkg = $request->bagorkg[$key];
                $PurchaseProduct->count = $request->count[$key];
                $PurchaseProduct->save();

                $product_ids = $request->product_id[$key];


                $branch_id = $request->get('branch_id');
                $product_Data = Product::where('productlist_id', '=', $product_ids)->where('branchtable_id', '=', $branch_id)->first();

                if($product_Data != ""){
                    if($branch_id == $product_Data->branchtable_id){

                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;

                        if($request->bagorkg[$key] == 'bag'){
                            $totalbag_count = $bag_count + $request->count[$key];
                            $totalkg_count = $kg_count + 0;
                        }else if($request->bagorkg[$key] == 'kg'){
                            $totalkg_count = $kg_count + $request->count[$key];
                            $totalbag_count = $bag_count + 0;
                        }


                        DB::table('products')->where('productlist_id', $product_ids)->where('branchtable_id', $branch_id)->update([
                            'available_stockin_bag' => $totalbag_count,  'available_stockin_kilograms' => $totalkg_count
                        ]);
                    }
                }else {
                        $product_randomkey = Str::random(5);


                        if($request->bagorkg[$key] == 'bag'){
                            $New_bagcount = $request->count[$key];
                            $New_kgcount = 0;
                        }else if($request->bagorkg[$key] == 'kg'){
                            $New_kgcount = $request->count[$key];
                            $New_bagcount = 0;
                        }

                        $ProductlistData = new Product;
                        $ProductlistData->unique_key = $product_randomkey;
                        $ProductlistData->productlist_id = $product_ids;
                        $ProductlistData->branchtable_id = $branch_id;
                        $ProductlistData->available_stockin_bag = $New_bagcount;
                        $ProductlistData->available_stockin_kilograms = $New_kgcount;
                        $ProductlistData->save();


                }


            }







            return redirect()->route('purchase.index')->with('add', 'Purchase Data added successfully!');





    }

    public function edit($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->first();
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->get();

        return view('page.backend.purchase.edit', compact('productlist', 'branch', 'supplier', 'bank', 'PurchaseData', 'PurchaseProducts'));
    }




    public function update(Request $request, $unique_key)
    {

        $branch_id = $request->get('branch_id');


        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->first();

        $Purchase_Data->supplier_id = $request->get('supplier_id');
        $Purchase_Data->branch_id = $request->get('branch_id');
        $Purchase_Data->date = $request->get('date');
        $Purchase_Data->time = $request->get('time');
        $Purchase_Data->bank_id = $request->get('bank_id');
        $Purchase_Data->update();

        $PurchaseId = $Purchase_Data->id;

        // Purchase Products Table

        $getinsertedP_Products = PurchaseProduct::where('purchase_id', '=', $PurchaseId)->get();
        $Purchaseproducts = array();
        foreach ($getinsertedP_Products as $key => $getinserted_P_Products) {
            $Purchaseproducts[] = $getinserted_P_Products->id;
        }

        $updatedpurchaseproduct_id = $request->purchase_detail_id;
        $updated_PurchaseProduct_id = array_filter($updatedpurchaseproduct_id);
        $different_ids = array_merge(array_diff($Purchaseproducts, $updated_PurchaseProduct_id), array_diff($updated_PurchaseProduct_id, $Purchaseproducts));

        if (!empty($different_ids)) {
            foreach ($different_ids as $key => $differents_id) {

                $getPurchaseOld = PurchaseProduct::where('id', '=', $differents_id)->first();

                $product_Data = Product::where('soft_delete', '!=', 1)->where('productlist_id', '=', $getPurchaseOld->productlist_id)->where('branchtable_id', '=', $branch_id)->first();
                if($branch_id == $product_Data->branchtable_id){

                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;


                        if($getPurchaseOld->bagorkg == 'bag'){
                            $totalbag_count = $bag_count - $getPurchaseOld->count;
                            $totalkg_count = $kg_count - 0;
                        }else if($getPurchaseOld->bagorkg == 'kg'){
                            $totalkg_count = $kg_count - $getPurchaseOld->count;
                            $totalbag_count = $bag_count - 0;
                        }




                        DB::table('products')->where('productlist_id', $getPurchaseOld->productlist_id)->where('branchtable_id', $branch_id)->update([
                            'available_stockin_bag' => $totalbag_count,  'available_stockin_kilograms' => $totalkg_count
                        ]);
                    }
            }
        }

        if (!empty($different_ids)) {
            foreach ($different_ids as $key => $different_id) {
                PurchaseProduct::where('id', $different_id)->delete();
            }
        }

        foreach ($request->get('purchase_detail_id') as $key => $purchase_detail_id) {
            if ($purchase_detail_id > 0) {

                $updateproduct_id = $request->product_id[$key];

                $ids = $purchase_detail_id;
                $purchaseID = $PurchaseId;
                $productlist_id = $request->product_id[$key];
                $bagorkg = $request->bagorkg[$key];
                $count = $request->count[$key];
                $date = $request->get('date');
                $purchase_branch_id = $request->get('branch_id');

                DB::table('purchase_products')->where('id', $ids)->update([
                    'purchase_id' => $purchaseID,  'productlist_id' => $updateproduct_id,  'bagorkg' => $bagorkg,  'count' => $count,  'date' => $date,  'branch_id' => $purchase_branch_id
                ]);

            } else if ($purchase_detail_id == '') {
                if ($request->product_id[$key] > 0) {


                    $p_prandomkey = Str::random(5);

                    $PurchaseProduct = new PurchaseProduct;
                    $PurchaseProduct->unique_key = $p_prandomkey;
                    $PurchaseProduct->purchase_id = $PurchaseId;
                    $PurchaseProduct->date = $request->get('date');
                    $PurchaseProduct->branch_id = $request->get('branch_id');
                    $PurchaseProduct->productlist_id = $request->product_id[$key];
                    $PurchaseProduct->bagorkg = $request->bagorkg[$key];
                    $PurchaseProduct->count = $request->count[$key];
                    $PurchaseProduct->save();



                    $Product_id = $request->product_id[$key];
                    $product_Data = Product::where('productlist_id', '=', $Product_id)->where('branchtable_id', '=', $branch_id)->first();

                    if($product_Data != ""){

                        if($branch_id == $product_Data->branchtable_id){

                            $bag_count = $product_Data->available_stockin_bag;
                            $kg_count = $product_Data->available_stockin_kilograms;


                            if($request->bagorkg[$key] == 'bag'){
                                $totalbag_count = $bag_count + $request->count[$key];
                                $totalkg_count = $kg_count + 0;
                            }else if($request->bagorkg[$key] == 'kg'){
                                $totalkg_count = $kg_count + $request->count[$key];
                                $totalbag_count = $bag_count + 0;
                            }



                            DB::table('products')->where('productlist_id', $Product_id)->where('branchtable_id', $branch_id)->update([
                                'available_stockin_bag' => $totalbag_count,  'available_stockin_kilograms' => $totalkg_count
                            ]);
                        }
                    }else {
                        $updateproduct_randomkey = Str::random(5);



                        if($request->bagorkg[$key] == 'bag'){
                            $New_bagcount = $request->count[$key];
                            $New_kgcount = 0;
                        }else if($request->bagorkg[$key] == 'kg'){
                            $New_kgcount = $request->count[$key];
                            $New_bagcount = 0;
                        }

                        $ProductlistData = new Product;
                        $ProductlistData->unique_key = $updateproduct_randomkey;
                        $ProductlistData->productlist_id = $Product_id;
                        $ProductlistData->branchtable_id = $branch_id;
                        $ProductlistData->available_stockin_bag = $New_bagcount;
                        $ProductlistData->available_stockin_kilograms = $New_kgcount;
                        $ProductlistData->save();
                    }


                }
            }
        }

        return redirect()->route('purchase.index')->with('update', 'Updated Purchase information has been added to your list.');

    }




    public function invoice($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->first();
        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->get();



        return view('page.backend.purchase.invoice', compact('productlist', 'branch', 'supplier', 'bank', 'PurchaseData', 'PurchaseProducts'));
    }



    public function invoice_update(Request $request, $unique_key)
    {


        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->first();

        $Purchase_Data->bank_id = $request->get('bank_id');
        $Purchase_Data->total_amount = $request->get('total_amount');

        $Purchase_Data->commission_ornet = $request->get('commission_ornet');
        $Purchase_Data->commission_percent = $request->get('commission_percent');
        $Purchase_Data->commission_amount = $request->get('commission_amount');

        $Purchase_Data->tot_comm_extracost = $request->get('tot_comm_extracost');
        $Purchase_Data->gross_amount = $request->get('gross_amount');
        $Purchase_Data->old_balance = $request->get('old_balance');
        $Purchase_Data->grand_total = $request->get('grand_total');
        $Purchase_Data->paid_amount = $request->get('payable_amount');
        $Purchase_Data->balance_amount = $request->get('pending_amount');
        $Purchase_Data->status = 1;
        $Purchase_Data->update();






        $PurchaseId = $Purchase_Data->id;

        // Purchase Products Table



        foreach ($request->get('purchase_detail_id') as $key => $purchase_detail_id) {
            if ($purchase_detail_id > 0) {

                $updateproduct_id = $request->product_id[$key];

                $ids = $purchase_detail_id;
                $purchaseID = $PurchaseId;
                $price_per_kg = $request->price_per_kg[$key];
                $total_price = $request->total_price[$key];

                DB::table('purchase_products')->where('id', $ids)->update([
                    'purchase_id' => $purchaseID, 'price_per_kg' => $price_per_kg, 'total_price' => $total_price
                ]);

            }
        }


        foreach ($request->get('extracost_note') as $key => $extracost_note) {
            if ($extracost_note != "") {
                $pecrandomkey = Str::random(5);

                $PurchaseExtracost = new PurchaseExtracost;
                $PurchaseExtracost->unique_key = $pecrandomkey;
                $PurchaseExtracost->purchase_id = $PurchaseId;
                $PurchaseExtracost->extracost_note = $extracost_note;
                $PurchaseExtracost->extracost = $request->extracost[$key];
                $PurchaseExtracost->save();
            }
        }



        $PurchseData = BranchwiseBalance::where('supplier_id', '=', $Purchase_Data->supplier_id)->where('branch_id', '=', $Purchase_Data->branch_id)->first();
        if($PurchseData != ""){

            $old_grossamount = $PurchseData->purchase_amount;
            $old_paid = $PurchseData->purchase_paid;

            $gross_amount = $request->get('gross_amount');
            $payable_amount = $request->get('payable_amount');

            $new_grossamount = $old_grossamount + $gross_amount;
            $new_paid = $old_paid + $payable_amount;
            $new_balance = $new_grossamount - $new_paid;

            DB::table('branchwise_balances')->where('supplier_id', $Purchase_Data->supplier_id)->where('branch_id', $Purchase_Data->branch_id)->update([
                'purchase_amount' => $new_grossamount,  'purchase_paid' => $new_paid, 'purchase_balance' => $new_balance
            ]);

        }else {
            $gross_amount = $request->get('gross_amount');
            $payable_amount = $request->get('payable_amount');
            $balance_amount = $gross_amount - $payable_amount;

            $data = new BranchwiseBalance();

            $data->supplier_id = $Purchase_Data->supplier_id;
            $data->branch_id = $Purchase_Data->branch_id;
            $data->purchase_amount = $request->get('gross_amount');
            $data->purchase_paid = $request->get('payable_amount');
            $data->purchase_balance = $balance_amount;
            $data->save();
        }

        return redirect()->route('purchase.index')->with('update', 'Updated Purchase information has been added to your list.');

    }



    public function invoiceedit($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->first();
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->get();
        $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $PurchaseData->id)->get();

        return view('page.backend.purchase.invoiceedit', compact('productlist', 'branch', 'supplier', 'bank', 'PurchaseData', 'PurchaseProducts', 'PurchaseExtracosts'));
    }


    public function invoiceedit_update(Request $request, $unique_key)
    {

        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->first();

        $purchase_branch_id = $Purchase_Data->branch_id;
        $purchase_supplier_id = $Purchase_Data->supplier_id;


        $PurchasebranchwiseData = BranchwiseBalance::where('supplier_id', '=', $purchase_supplier_id)->where('branch_id', '=', $purchase_branch_id)->first();
        if($PurchasebranchwiseData != ""){


            $old_grossamount = $PurchasebranchwiseData->purchase_amount;
            $old_paid = $PurchasebranchwiseData->purchase_paid;

            $oldentry_grossamount = $Purchase_Data->gross_amount;
            $oldentry_paid = $Purchase_Data->paid_amount;

            $gross_amount = $request->get('gross_amount');
            $payable_amount = $request->get('payable_amount');



            if($oldentry_grossamount > $gross_amount){
                $newgross = $oldentry_grossamount - $gross_amount;
                $updated_gross = $old_grossamount - $newgross;
            }else if($oldentry_grossamount < $gross_amount){
                $newgross = $gross_amount - $oldentry_grossamount;
                $updated_gross = $old_grossamount + $newgross;
            }else if($oldentry_grossamount == $gross_amount){
                $updated_gross = $old_grossamount;
            }


            if($oldentry_paid > $payable_amount){
                $newPaidAmt = $oldentry_paid - $payable_amount;
                $updated_paid = $old_paid - $newPaidAmt;
            }else if($oldentry_paid < $payable_amount){
                $newPaidAmt = $payable_amount - $oldentry_paid;
                $updated_paid = $old_paid + $newPaidAmt;
            }else if($oldentry_paid == $payable_amount){
                $updated_paid = $old_paid;
            }

            $new_balance = $updated_gross - $updated_paid;

            DB::table('branchwise_balances')->where('supplier_id', $purchase_supplier_id)->where('branch_id', $purchase_branch_id)->update([
                'purchase_amount' => $updated_gross,  'purchase_paid' => $updated_paid, 'purchase_balance' => $new_balance
            ]);

        }

        $Purchase_Data->total_amount = $request->get('total_amount');
        $Purchase_Data->commission_ornet = $request->get('commission_ornet');
        $Purchase_Data->commission_percent = $request->get('commission_percent');
        $Purchase_Data->commission_amount = $request->get('commission_amount');

        $Purchase_Data->tot_comm_extracost = $request->get('tot_comm_extracost');
        $Purchase_Data->gross_amount = $request->get('gross_amount');
        $Purchase_Data->old_balance = $request->get('old_balance');
        $Purchase_Data->grand_total = $request->get('grand_total');
        $Purchase_Data->paid_amount = $request->get('payable_amount');
        $Purchase_Data->balance_amount = $request->get('pending_amount');
        $Purchase_Data->purchase_remark = $request->get('purchase_remark');
        $Purchase_Data->update();


        $PurchaseId = $Purchase_Data->id;




        foreach ($request->get('purchase_detail_id') as $key => $purchase_detail_id) {
            if ($purchase_detail_id > 0) {
                $updateproduct_id = $request->product_id[$key];

                $product_Data = Product::where('soft_delete', '!=', 1)->where('productlist_id', '=', $updateproduct_id)->where('branchtable_id', '=', $purchase_branch_id)->first();
                    if($product_Data != ""){
                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;


                        if($request->bagorkg[$key] == 'bag'){
                            $getP_Productbag = PurchaseProduct::where('id', '=', $purchase_detail_id)->where('bagorkg', '=', 'bag')->first();

                            $old_count = $getP_Productbag->count;
                            $new_count = $request->count[$key];

                            if($old_count > $new_count){

                                $total_count = $old_count - $new_count;
                                $stockbag_count = $bag_count - $total_count;

                                DB::table('products')->where('productlist_id', $updateproduct_id)->where('branchtable_id', $purchase_branch_id)->update([
                                    'available_stockin_bag' => $stockbag_count
                                ]);
                            }else if($old_count < $new_count){

                                $total_count = $new_count - $old_count;
                                $stockbag_count = $bag_count + $total_count;

                                DB::table('products')->where('productlist_id', $updateproduct_id)->where('branchtable_id', $purchase_branch_id)->update([
                                    'available_stockin_bag' => $stockbag_count
                                ]);

                            }
                        }else if($request->bagorkg[$key] == 'kg'){
                            $getP_Productkg = SalesProduct::where('id', '=', $sales_detail_id)->where('bagorkg', '=', 'kg')->first();

                            $oldkg_count = $getP_Productkg->count;
                            $newkg_count = $request->count[$key];


                            if($oldkg_count > $newkg_count){

                                $total_count = $oldkg_count - $newkg_count;
                                $stockkg_count = $kg_count - $total_count;

                                DB::table('products')->where('productlist_id', $updateproduct_id)->where('branchtable_id', $purchase_branch_id)->update([
                                    'available_stockin_kilograms' => $stockkg_count
                                ]);

                            }else if($oldkg_count < $newkg_count){

                                $total_count = $newkg_count - $oldkg_count;
                                $stockkg_count = $kg_count + $total_count;

                                DB::table('products')->where('productlist_id', $updateproduct_id)->where('branchtable_id', $purchase_branch_id)->update([
                                    'available_stockin_kilograms' => $stockkg_count
                                ]);

                            }

                        }
                    }



                    $ids = $purchase_detail_id;
                    $PurchaseId = $PurchaseId;
                    $productlist_id = $request->product_id[$key];
                    $bagorkg = $request->bagorkg[$key];
                    $count = $request->count[$key];
                    $price_per_kg = $request->price_per_kg[$key];
                    $total_price = $request->total_price[$key];

                    DB::table('purchase_products')->where('id', $ids)->update([
                        'purchase_id' => $PurchaseId,  'productlist_id' => $updateproduct_id,  'bagorkg' => $bagorkg,  'count' => $count, 'price_per_kg' => $price_per_kg, 'total_price' => $total_price
                    ]);


            }

        }

        return redirect()->route('purchase.index')->with('update', 'Updated Purchase information has been added to your list.');

    }



    public function print_view($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->where('purchase_order', '=', NULL)->first();

        $suppliername = Supplier::where('id', '=', $PurchaseData->supplier_id)->first();
        $supplier_upper = strtoupper($suppliername->name);
        $branchname = Branch::where('id', '=', $PurchaseData->branch_id)->first();
        $bankname = Bank::where('id', '=', $PurchaseData->bank_id)->first();

        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->where('purchase_order', '=', NULL)->get();
        $extracostamount = $PurchaseData->tot_comm_extracost - $PurchaseData->commission_amount;

        $Purchaseextracosts = PurchaseExtracost::where('purchase_id', '=', $PurchaseData->id)->get();

        return view('page.backend.purchase.print_view', compact('PurchaseData', 'suppliername', 'branchname', 'bankname', 'PurchaseProducts', 'productlist', 'supplier_upper', 'extracostamount', 'Purchaseextracosts'));
    }


    public function delete($unique_key)
    {
        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->first();


        $getinsertedP_Products = PurchaseProduct::where('purchase_id', '=', $Purchase_Data->id)->get();
        $Purchaseproducts = array();
        foreach ($getinsertedP_Products as $key => $getinserted_P_Products) {
            $Purchaseproducts[] = $getinserted_P_Products->id;
        }



        if (!empty($Purchaseproducts)) {
            foreach ($Purchaseproducts as $key => $differents_id) {

                $getPurchaseOld = PurchaseProduct::where('id', '=', $differents_id)->first();

                $product_Data = Product::where('soft_delete', '!=', 1)->where('productlist_id', '=', $getPurchaseOld->productlist_id)->where('branchtable_id', '=', $Purchase_Data->branch_id)->first();
                if($Purchase_Data->branch_id == $product_Data->branchtable_id){

                        $bag_count = $product_Data->available_stockin_bag;
                        $kg_count = $product_Data->available_stockin_kilograms;


                        if($getPurchaseOld->bagorkg == 'bag'){
                            $totalbag_count = $bag_count - $getPurchaseOld->count;
                            $totalkg_count = $kg_count - 0;
                        }else if($getPurchaseOld->bagorkg == 'kg'){
                            $totalkg_count = $kg_count - $getPurchaseOld->count;
                            $totalbag_count = $bag_count - 0;
                        }




                        DB::table('products')->where('productlist_id', $getPurchaseOld->productlist_id)->where('branchtable_id', $Purchase_Data->branch_id)->update([
                            'available_stockin_bag' => $totalbag_count,  'available_stockin_kilograms' => $totalkg_count
                        ]);
                    }
            }
        }

        if (!empty($Purchaseproducts)) {
            foreach ($Purchaseproducts as $key => $Purchaseproducts_arr) {
                PurchaseProduct::where('id', $Purchaseproducts_arr)->delete();
            }
        }



        $getinsertedExtracost = PurchaseExtracost::where('purchase_id', '=', $Purchase_Data->id)->get();
        if (!empty($getinsertedExtracost)) {
            foreach ($getinsertedExtracost as $key => $getinsertedExtracosts) {
                PurchaseExtracost::where('purchase_id', $getinsertedExtracosts->purchase_id)->delete();
            }
        }




        $purchase_branch_id = $Purchase_Data->branch_id;
        $purchase_supplier_id = $Purchase_Data->supplier_id;


        $PurchasebranchwiseData = BranchwiseBalance::where('supplier_id', '=', $purchase_supplier_id)->where('branch_id', '=', $purchase_branch_id)->first();
        if($PurchasebranchwiseData != ""){


            $old_grossamount = $PurchasebranchwiseData->purchase_amount;
            $old_paid = $PurchasebranchwiseData->purchase_paid;

            $oldentry_grossamount = $Purchase_Data->gross_amount;
            $oldentry_paid = $Purchase_Data->paid_amount;


                $updated_gross = $old_grossamount - $oldentry_grossamount;
                $updated_paid = $old_paid - $oldentry_paid;

                $new_balance = $updated_gross - $updated_paid;

            DB::table('branchwise_balances')->where('supplier_id', $purchase_supplier_id)->where('branch_id', $purchase_branch_id)->update([
                'purchase_amount' => $updated_gross,  'purchase_paid' => $updated_paid, 'purchase_balance' => $new_balance
            ]);

        }


        $Purchase_Data->delete();

        return redirect()->route('purchase.index')->with('update', 'Successfully erased the Purchase record !');

    }



    public function getProducts()
    {
        $GetProduct = productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->get();
        $userData['data'] = $GetProduct;
        echo json_encode($userData);
    }



    public function getoldbalance()
    {

        $invoice_supplier = request()->get('invoice_supplier');
        $invoice_branchid = request()->get('invoice_branchid');




        $last_idrow = BranchwiseBalance::where('supplier_id', '=', $invoice_supplier)->where('branch_id', '=', $invoice_branchid)->first();

        if($last_idrow != ""){

            if($last_idrow->purchase_balance != NULL){
                $userData['data'] = $last_idrow->purchase_balance;
            }


        }else {
            $userData['data'] = 0;
        }


        echo json_encode($userData);
    }



    public function getoldbalanceforPayment()
    {
        $supplier_id = request()->get('supplier_id');
        $branch_id = request()->get('branch_id');



        $last_idrow = BranchwiseBalance::where('supplier_id', '=', $supplier_id)->where('branch_id', '=', $branch_id)->first();
        if($last_idrow != ""){

            if($last_idrow->purchase_balance != NULL){

                $output[] = array(
                    'payment_pending' => $last_idrow->purchase_balance,
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




    public function getPurchaseview()
    {
        $purchase_id = request()->get('purchase_id');
        $get_Purchase = Purchase::where('soft_delete', '!=', 1)
                                    ->where('id', '=', $purchase_id)->where('purchase_order', '=', NULL)
                                    ->get();
        $output = [];
        foreach ($get_Purchase as $key => $get_Purchase_data) {

            $Supplier_namearr = Supplier::where('id', '=', $get_Purchase_data->supplier_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $branch_namearr = Branch::where('id', '=', $get_Purchase_data->branch_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $bank_namearr = Bank::where('id', '=', $get_Purchase_data->bank_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            if($bank_namearr != ""){
                $bank_name = $bank_namearr->name;
            }else {
                $bank_name = '';
            }
            $output[] = array(
                'suppliername' => $Supplier_namearr->name,
                'supplier_contact_number' => $Supplier_namearr->contact_number,
                'supplier_shop_name' => $Supplier_namearr->shop_name,
                'supplier_shop_address' => $Supplier_namearr->shop_address,
                'branchname' => $branch_namearr->name,
                'branch_contact_number' => $branch_namearr->contact_number,
                'branch_shop_name' => $branch_namearr->shop_name,
                'branch_address' => $branch_namearr->address,

                'date' => date('d m Y', strtotime($get_Purchase_data->date)),
                'time' => date('h:i A', strtotime($get_Purchase_data->time)),

                'bank_namedata' => $bank_name,
                'purchase_total_amount' => $get_Purchase_data->total_amount,
                'commission_amount' => $get_Purchase_data->commission_amount,
                'purchase_commisionpercentage' => $get_Purchase_data->commission_percent . '%',
                'tot_comm_extracost' => $get_Purchase_data->tot_comm_extracost,
                'purchase_gross_amount' => $get_Purchase_data->gross_amount,
                'purchase_old_balance' => $get_Purchase_data->old_balance,
                'purchase_grand_total' => $get_Purchase_data->grand_total,
                'purchase_paid_amount' => $get_Purchase_data->paid_amount,
                'purchase_balance_amount' => $get_Purchase_data->balance_amount,
                'purchase_bill_no' => $get_Purchase_data->bill_no,
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



    public function report() {
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplierarr = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


        $data = Purchase::where('soft_delete', '!=', 1)->get();
        $Purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        // foreach ($data as $key => $datas) {
        //     $branch_name = Branch::findOrFail($datas->branch_id);
        //     $supplier_name = Supplier::findOrFail($datas->supplier_id);

        //     $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
        //     foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

        //         $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);
        //         $terms[] = array(
        //             'bag' => $PurchaseProducts_arrdata->bagorkg,
        //             'kgs' => $PurchaseProducts_arrdata->count,
        //             'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
        //             'total_price' => $PurchaseProducts_arrdata->total_price,
        //             'product_name' => $productlist_ID->name,
        //             'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

        //         );

        //     }


        //     $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $datas->id)->get();
        //     foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

        //         $Extracost_Arr[] = array(
        //             'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
        //             'extracost' => $PurchaseExtracosts_arr->extracost,
        //             'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

        //         );

        //     }



        //     $purchase_data[] = array(
        //         'purchase_order' => $datas->purchase_order,
        //         'unique_key' => $datas->unique_key,
        //         'branch_id' => $datas->branch_id,
        //         'branch_name' => $branch_name->shop_name,
        //         'supplier_name' => $supplier_name->name,
        //         'date' => $datas->date,
        //         'time' => $datas->time,
        //         'gross_amount' => $datas->gross_amount,
        //         'bill_no' => $datas->bill_no,
        //         'id' => $datas->id,
        //         'supplier_id' => $datas->supplier_id,
        //         'bank_id' => $datas->bank_id,
        //         'status' => $datas->status,
        //         'terms' => $terms,
        //         'Extracost_Arr' => $Extracost_Arr,
        //         'branchheading' => $branch_name->shop_name,
        //         'supplierheading' => $supplier_name->name,
        //         'fromdateheading' => date('d-M-Y', strtotime($datas->date)),
        //         'todateheading' => date('d-M-Y', strtotime($datas->date)),
        //     );
        // }



        return view('page.backend.purchase.report', compact('branch', 'supplierarr', 'Purchase_data'));
    }


    public function report_view(Request $request)
    {
        $purchasereport_fromdate = $request->get('purchasereport_fromdate');
        $purchasereport_todate = $request->get('purchasereport_todate');
        $purchasereport_branch = $request->get('purchasereport_branch');
        $purchasereport_supplier = $request->get('purchasereport_supplier');

        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplierarr = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

       


        if($purchasereport_supplier != ""){
            $SupplierData = Supplier::findOrFail($purchasereport_supplier);

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $SupplierData->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => $SupplierData->name,
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => $SupplierData->name,
                        'fromdateheading' => '',
                        'todateheading' => '',
                        'datetime' => '',
    
                );
            }
        }



        if($purchasereport_fromdate != ""){

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::where('date', '=', $purchasereport_fromdate)->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::where('date', '=', $purchasereport_fromdate)->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }
                    $supplier = Supplier::findOrFail($datas->supplier_id);

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $supplier->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => '',
                        'datetime' => '',
    
                );
            }

        }


        if($purchasereport_todate != ""){

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::where('date', '=', $purchasereport_todate)->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::where('date', '=', $purchasereport_todate)->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }
                    $supplier = Supplier::findOrFail($datas->supplier_id);

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $supplier->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => $datas->date . $datas->time,

                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => '',
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => '',
    
                );
            }

        }


        if($purchasereport_fromdate && $purchasereport_todate){

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::whereBetween('date', [$purchasereport_fromdate, $purchasereport_todate])->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::whereBetween('date', [$purchasereport_fromdate, $purchasereport_todate])->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }
                    $supplier = Supplier::findOrFail($datas->supplier_id);

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $supplier->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => $datas->date . $datas->time,
                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => '',
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => '',
    
                );
            }

        

        }


        if($purchasereport_fromdate && $purchasereport_supplier){

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::where('date', '=', $purchasereport_fromdate)->where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::where('date', '=', $purchasereport_fromdate)->where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }
                    $supplier = Supplier::findOrFail($datas->supplier_id);

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $supplier->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => $supplier->name,
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => '',
                        'datetime' => $datas->date . $datas->time,

                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => $supplier->name,
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => '',
                        'datetime' => '',
    
                );
            }

        

        }
       

        if($purchasereport_todate && $purchasereport_supplier){

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::where('date', '=', $purchasereport_todate)->where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::where('date', '=', $purchasereport_todate)->where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }
                    $supplier = Supplier::findOrFail($datas->supplier_id);

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $supplier->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => $supplier->name,
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => $datas->date . $datas->time,

                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => $supplier->name,
                        'fromdateheading' => '',
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => '',
    
                );
            }

        

        }


        if($purchasereport_fromdate && $purchasereport_todate && $purchasereport_supplier){
            

            $Purchase_data = [];
            $terms = [];

            $branchwise_report = Purchase::whereBetween('date', [$purchasereport_fromdate, $purchasereport_todate])->where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            $purchases = [];
            foreach ($branchwise_report as $key => $branchwise_reports) {
                $purchases[] = $branchwise_reports;
            }


            $purhcasepayment_s = [];
            $Purchasepaymentdata = PurchasePayment::whereBetween('date', [$purchasereport_fromdate, $purchasereport_todate])->where('supplier_id', '=', $purchasereport_supplier)->where('soft_delete', '!=', 1)->get();
            foreach ($Purchasepaymentdata as $key => $Purchasepaymentdatas) {
                $purhcasepayment_s[] = $Purchasepaymentdatas;
            }

            $merge = array_merge($purchases, $purhcasepayment_s);
            if($merge != ''){
                foreach ($merge as $key => $datas) {

                    $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->get();
                    foreach ($PurchaseProducts as $key => $PurchaseProducts_arr) {

                        $productlist_ID = Productlist::findOrFail($PurchaseProducts_arr->productlist_id);
                        $terms[] = array(
                            'bag' => $PurchaseProducts_arr->bagorkg,
                            'kgs' => $PurchaseProducts_arr->count,
                            'price_per_kg' => $PurchaseProducts_arr->price_per_kg,
                            'total_price' => $PurchaseProducts_arr->total_price,
                            'product_name' => $productlist_ID->name,
                            'purchase_id' => $PurchaseProducts_arr->purchase_id,

                        );

                    }
                    $supplier = Supplier::findOrFail($datas->supplier_id);

                    if($datas->status != ""){
                        $paid = $datas->paid_amount;
                        $balance = $datas->balance_amount;
                        $type='PURHCASE';
                        $discount = '';
                    }else {
                        $paid = $datas->amount;
                        $balance = $datas->payment_pending;
                        $type='PAYMENT';
                        $discount = $datas->purchasepayment_discount;
                    }

                    $Purchase_data[] = array(
                        'unique_key' => $datas->unique_key,
                        'supplier_name' => $supplier->name,
                        'date' => $datas->date,
                        'time' => $datas->time,
                        'gross_amount' => $datas->gross_amount,
                        'paid_amount' => $paid,
                        'bill_no' => $datas->bill_no,
                        'purchase_order' => $datas->purchase_order,
                        'grand_total' => $datas->grand_total,
                        'balance_amount' => $balance,
                        'type' => $type,
                        'id' => $datas->id,
                        'terms' => $terms,
                        'discount' => $discount,
                        'status' => $datas->status,
                        'branchheading' => '',
                        'supplierheading' => $supplier->name,
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => $datas->date . $datas->time,

                    );
                }
            }else{

                $Purchase_data[] = array(
                    'unique_key' => '',
                        'supplier_name' => '',
                        'date' => '',
                        'time' => '',
                        'gross_amount' => '',
                        'paid_amount' => '',
                        'bill_no' => '',
                        'purchase_order' => '',
                        'grand_total' => '',
                        'balance_amount' => '',
                        'type' => '',
                        'id' => '',
                        'terms' => '',
                        'discount' => '',
                        'status' => '',
                        'branchheading' => '',
                        'supplierheading' => $supplier->name,
                        'fromdateheading' => date('d-M-Y', strtotime($purchasereport_fromdate)),
                        'todateheading' => date('d-M-Y', strtotime($purchasereport_todate)),
                        'datetime' => '',
    
                );
            }

        

        
        }


        usort($Purchase_data, function($a1, $a2) {
            $value1 = strtotime($a1['datetime']);
            $value2 = strtotime($a2['datetime']);
            return ($value1 < $value2) ? 1 : -1;
         });




        return view('page.backend.purchase.report', compact('purchasereport_fromdate', 'branch', 'supplierarr', 'purchasereport_todate','purchasereport_branch', 'purchasereport_supplier', 'Purchase_data'));
    }




    public function Checkinvoiceupdated()
    {
        $purchase_id = request()->get('purchase_id');
        $get_Purchase = Purchase::where('soft_delete', '!=', 1)
                                    ->where('unique_key', '=', $purchase_id)
                                    ->first();


        if($get_Purchase->total_amount != NULL){
            $userData['data'] = '1';
            echo json_encode($userData);
        }else {
            $userData['data'] = '0';
            echo json_encode($userData);
        }

    }

    // PURCHASE ORDER
    public function purchaseorder_index()
    {

        $today = Carbon::now()->format('Y-m-d');
        $data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('soft_delete', '!=', 1)->get();

        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];

        foreach ($data as $key => $datas)
        {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $supplier_name = Supplier::findOrFail($datas->supplier_id);
            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', '1')->get();

            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata)
            {
                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);
                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );
            }

            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', '1')->get();

            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr)
            {
                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,
                );
            }

            $All_supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

            foreach ($All_supplier as $key => $All_supplierarr)
            {
                $PurchaseArray = Purchase::where('supplier_id', '=', $All_supplierarr->id)->where('purchase_order', '=', '1')->where('gross_amount', '=', NULL)->where('soft_delete', '!=', 1)->first();
                if($PurchaseArray){
                    $null_grossarr[] = $PurchaseArray->id;
                }
            }

            $purchase_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_id' => $datas->branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_name' => $supplier_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'bill_no' => $datas->bill_no,
                'commission_ornet' => $datas->commission_ornet,
                'id' => $datas->id,
                'supplier_id' => $datas->supplier_id,
                'bank_id' => $datas->bank_id,
                'status' => $datas->status,
                'terms' => $terms,
                'Extracost_Arr' => $Extracost_Arr,
                'null_grossarr' => $null_grossarr,
            );
        }

        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('soft_delete', '!=', 1)->get();

        $Sales_Branch = [];

        foreach ($sales_branchwise_data as $key => $sales_Data)
        {
            $Sales_Branch[] = $sales_Data->branch_id;
        }

        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs)
        {
            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->get();

            $sales_Array = [];

            if($merge_salesProduct != "")
            {
                foreach ($merge_salesProduct as $key => $merge_salesProducts)
                {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            } else {
                $sales_Array[] = '';
            }

            foreach (array_unique($sales_Array) as $key => $sales_productlist)
            {
                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


                if($getSalebagcount != 0)
                {
                    $bag_count = $getSalebagcount;
                } else {
                    $bag_count = '';
                }

                if($getSalekgcount != 0)
                {
                    $kg_count = $getSalekgcount;
                } else {
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

        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $timenow = Carbon::now()->format('H:i');

        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        return view('page.backend.purchaseorder.purchaseorder_index', compact('purchase_data', 'today', 'productlist', 'allbranch', 'branch', 'supplier', 'timenow', 'bank', 'PSTodayStockArr'));
    }

    public function purchaseorderbranch($branch_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];
        foreach ($branchwise_data as $key => $branchwise_datas) {
            $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
            $supplier_name = Supplier::findOrFail($branchwise_datas->supplier_id);


            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', '1')->get();
            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);

                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );
            }

            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', '1')->get();
            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

                );

            }


            $All_supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            foreach ($All_supplier as $key => $All_supplierarr) {
                $PurchaseArray = Purchase::where('supplier_id', '=', $All_supplierarr->id)->where('purchase_order', '=', '1')->where('gross_amount', '=', NULL)->where('soft_delete', '!=', 1)->first();
                if($PurchaseArray){
                    $null_grossarr[] = $PurchaseArray->id;
                }

            }


           // dd($null_status);



            $purchase_data[] = array(
                'unique_key' => $branchwise_datas->unique_key,
                'branch_id' => $branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_name' => $supplier_name->name,
                'date' => $branchwise_datas->date,
                'time' => $branchwise_datas->time,
                'gross_amount' => $branchwise_datas->gross_amount,
                'bill_no' => $branchwise_datas->bill_no,
                'id' => $branchwise_datas->id,
                'terms' => $terms,
                'status' => $branchwise_datas->status,
                'Extracost_Arr' => $Extracost_Arr,
                'null_grossarr' => $null_grossarr,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


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

        return view('page.backend.purchaseorder.purchaseorder_index', compact('purchase_data', 'allbranch', 'branch_id', 'today', 'PSTodayStockArr'));
    }

    // public function purchaseorder_branchdata($today, $branch_id)
    // {
    //     $branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('branch_id', '=', $branch_id)->where('soft_delete', '!=', 1)->get();
    //     $purchase_data = [];
    //     $terms = [];
    //     $Extracost_Arr = [];
    //     $null_grossarr = [];
    //     foreach ($branchwise_data as $key => $branchwise_datas) {
    //         $branch_name = Branch::findOrFail($branchwise_datas->branch_id);
    //         $supplier_name = Supplier::findOrFail($branchwise_datas->supplier_id);


    //         $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', '1')->get();
    //         foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

    //             $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);

    //             $terms[] = array(
    //                 'bag' => $PurchaseProducts_arrdata->bagorkg,
    //                 'kgs' => $PurchaseProducts_arrdata->count,
    //                 'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
    //                 'note' => $PurchaseProducts_arrdata->note,
    //                 'total_price' => $PurchaseProducts_arrdata->total_price,
    //                 'product_name' => $productlist_ID->name,
    //                 'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

    //             );
    //         }

    //         $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $branchwise_datas->id)->where('purchase_order', '=', '1')->get();
    //         foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

    //             $Extracost_Arr[] = array(
    //                 'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
    //                 'extracost' => $PurchaseExtracosts_arr->extracost,
    //                 'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

    //             );

    //         }


    //         $All_supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
    //         foreach ($All_supplier as $key => $All_supplierarr) {
    //             $PurchaseArray = Purchase::where('supplier_id', '=', $All_supplierarr->id)->where('purchase_order', '=', '1')->where('gross_amount', '=', NULL)->where('soft_delete', '!=', 1)->first();
    //             if($PurchaseArray){
    //                 $null_grossarr[] = $PurchaseArray->id;
    //             }

    //         }


    //        // dd($null_status);



    //         $purchase_data[] = array(
    //             'unique_key' => $branchwise_datas->unique_key,
    //             'branch_id' => $branch_id,
    //             'branch_name' => $branch_name->shop_name,
    //             'supplier_name' => $supplier_name->name,
    //             'date' => $branchwise_datas->date,
    //             'time' => $branchwise_datas->time,
    //             'gross_amount' => $branchwise_datas->gross_amount,
    //             'bill_no' => $branchwise_datas->bill_no,
    //             'id' => $branchwise_datas->id,
    //             'terms' => $terms,
    //             'status' => $branchwise_datas->status,
    //             'Extracost_Arr' => $Extracost_Arr,
    //             'null_grossarr' => $null_grossarr,
    //         );
    //     }
    //     $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();

    //     $PSTodayStockArr = [];

    //     $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('soft_delete', '!=', 1)->get();
    //     $Sales_Branch = [];
    //     foreach ($sales_branchwise_data as $key => $sales_Data) {
    //         $Sales_Branch[] = $sales_Data->branch_id;
    //     }


    //     foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

    //         $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->get();
    //         $sales_Array = [];
    //         if($merge_salesProduct != ""){
    //             foreach ($merge_salesProduct as $key => $merge_salesProducts) {
    //                 $sales_Array[] = $merge_salesProducts->productlist_id;
    //             }
    //         }else {
    //             $sales_Array[] = '';
    //         }



    //         foreach (array_unique($sales_Array) as $key => $sales_productlist) {

    //             $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
    //             $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


    //             if($getSalebagcount != 0){
    //                 $bag_count = $getSalebagcount;
    //             }else {
    //                 $bag_count = '';
    //             }

    //             if($getSalekgcount != 0){
    //                 $kg_count = $getSalekgcount;
    //             }else {
    //                 $kg_count = '';
    //             }


    //                 $productlist_ID = Productlist::findOrFail($sales_productlist);

    //                 $PSTodayStockArr[] = array(
    //                     'branch_id' => $Merge_Branchs,
    //                     'product_name' => $productlist_ID->name,
    //                     'getSalebagcount' => $bag_count,
    //                     'getSalekgcount' => $kg_count,
    //                     'today' => $today,

    //                 );


    //         }

    //     }

    //     return view('page.backend.purchaseorder.purchaseorder_index', compact('purchase_data', 'allbranch', 'branch_id', 'today', 'PSTodayStockArr'));
    // }

    public function purchaseorder_datefilter(Request $request)
    {

        $today = $request->get('from_date');

        $data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $purchase_data = [];
        $terms = [];
        $Extracost_Arr = [];
        $null_grossarr = [];
        foreach ($data as $key => $datas) {
            $branch_name = Branch::findOrFail($datas->branch_id);
            $supplier_name = Supplier::findOrFail($datas->supplier_id);

            $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', '1')->get();
            foreach ($PurchaseProducts as $key => $PurchaseProducts_arrdata) {

                $productlist_ID = Productlist::findOrFail($PurchaseProducts_arrdata->productlist_id);
                $terms[] = array(
                    'bag' => $PurchaseProducts_arrdata->bagorkg,
                    'kgs' => $PurchaseProducts_arrdata->count,
                    'price_per_kg' => $PurchaseProducts_arrdata->price_per_kg,
                    'total_price' => $PurchaseProducts_arrdata->total_price,
                    'note' => $PurchaseProducts_arrdata->note,
                    'product_name' => $productlist_ID->name,
                    'purchase_id' => $PurchaseProducts_arrdata->purchase_id,

                );
            }


            $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $datas->id)->where('purchase_order', '=', '1')->get();
            foreach ($PurchaseExtracosts as $key => $PurchaseExtracosts_arr) {

                $Extracost_Arr[] = array(
                    'extracost_note' => $PurchaseExtracosts_arr->extracost_note,
                    'extracost' => $PurchaseExtracosts_arr->extracost,
                    'purchase_id' => $PurchaseExtracosts_arr->purchase_id,

                );

            }

            $All_supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
            foreach ($All_supplier as $key => $All_supplierarr) {
                $PurchaseArray = Purchase::where('supplier_id', '=', $All_supplierarr->id)->where('purchase_order', '=', '1')->where('gross_amount', '=', NULL)->where('soft_delete', '!=', 1)->first();
                if($PurchaseArray){
                    $null_grossarr[] = $PurchaseArray->id;
                }
            }


            $purchase_data[] = array(
                'unique_key' => $datas->unique_key,
                'branch_id' => $datas->branch_id,
                'branch_name' => $branch_name->shop_name,
                'supplier_name' => $supplier_name->name,
                'date' => $datas->date,
                'time' => $datas->time,
                'gross_amount' => $datas->gross_amount,
                'bill_no' => $datas->bill_no,
                'id' => $datas->id,
                'terms' => $terms,
                'Extracost_Arr' => $Extracost_Arr,
                'null_grossarr' => $null_grossarr,
                'status' => $datas->status,
            );
        }
        $allbranch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();


        $PSTodayStockArr = [];

        $sales_branchwise_data = Purchase::where('date', '=', $today)->where('purchase_order', '=', '1')->where('soft_delete', '!=', 1)->get();
        $Sales_Branch = [];
        foreach ($sales_branchwise_data as $key => $sales_Data) {
            $Sales_Branch[] = $sales_Data->branch_id;
        }


        foreach (array_unique($Sales_Branch) as $key => $Merge_Branchs) {

            $merge_salesProduct = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->get();
            $sales_Array = [];
            if($merge_salesProduct != ""){
                foreach ($merge_salesProduct as $key => $merge_salesProducts) {
                    $sales_Array[] = $merge_salesProducts->productlist_id;
                }
            }else {
                $sales_Array[] = '';
            }



            foreach (array_unique($sales_Array) as $key => $sales_productlist) {

                $getSalebagcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'bag')->sum('count');
                $getSalekgcount = PurchaseProduct::where('branch_id', '=', $Merge_Branchs)->where('purchase_order', '=', '1')->where('date', '=', $today)->where('productlist_id', '=', $sales_productlist)->where('bagorkg', '=', 'kg')->sum('count');


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
        return view('page.backend.purchaseorder.purchaseorder_index', compact('purchase_data', 'allbranch', 'today', 'PSTodayStockArr'));

    }



    public function purchaseorder_create()
    {
        $productlist = Productlist::orderBy('id', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $today = Carbon::now()->format('Y-m-d');
        $timenow = Carbon::now()->format('H:i');



        return view('page.backend.purchaseorder.purchaseorder_create', compact('productlist', 'branch', 'supplier', 'today', 'timenow', 'bank'));
    }


    public function purchaseorder_store(Request $request)
    {

            $porandomkey = Str::random(5);

            $supplier_id = $request->get('supplier_id');


            $bill_branchid = $request->get('branch_id');
            $bill_date = $request->get('date');
            $s_bill_no = 1;

            if($request->get('payable_amount') != ""){
                $payment = $request->get('payable_amount');
            }else {
                $payment = 0;
            }

            // Branch
            $GetBranch = Branch::findOrFail($bill_branchid);
            $Branch_Name = $GetBranch->shop_name;
            $first_three_letter = substr($Branch_Name, 0, 3);
            $branch_upper = strtoupper($first_three_letter);

            //Date
            $billreport_date = date('dmY', strtotime($bill_date));


            $lastreport_OFBranch = Purchase::where('branch_id', '=', $bill_branchid)->where('purchase_order', '=', '1')->latest('id')->first();
            if($lastreport_OFBranch != '')
            {
                $added_billno = substr ($lastreport_OFBranch->bill_no, -2);
                $invoiceno = '0' . ($added_billno) + 1;
            } else {
                $invoiceno = '0' . $s_bill_no;
            }



            $data = new Purchase();

            $data->unique_key = $porandomkey;
            $data->supplier_id = $request->get('supplier_id');
            $data->branch_id = $request->get('branch_id');
            $data->date = $request->get('date');
            $data->time = $request->get('time');
            $data->bank_id = $request->get('bank_id');
            $data->total_amount = $request->get('total_amount');

            $data->commission_ornet = $request->get('commission_ornet');
            $data->commission_percent = $request->get('commission_percent');
            $data->commission_amount = $request->get('commission_amount');

            $data->tot_comm_extracost = $request->get('tot_comm_extracost');
            $data->gross_amount = $request->get('gross_amount');
            $data->old_balance = $request->get('old_balance');
            $data->grand_total = $request->get('grand_total');

            

            $data->paid_amount = $payment;
            $data->balance_amount = $request->get('pending_amount');
            $data->bill_no = $invoiceno;
            $data->purchase_order = '1';
            $data->save();

            $insertedId = $data->id;

            // Purchase Products Table
            foreach ($request->get('product_id') as $key => $product_id) {

                $poprandomkey = Str::random(5);

                $PurchaseProduct = new PurchaseProduct;
                $PurchaseProduct->unique_key = $poprandomkey;
                $PurchaseProduct->purchase_id = $insertedId;
                $PurchaseProduct->date = $data->date;
                $PurchaseProduct->branch_id = $data->branch_id;
                $PurchaseProduct->productlist_id = $product_id;
                $PurchaseProduct->bagorkg = $request->bagorkg[$key];
                $PurchaseProduct->count = $request->count[$key];
                $PurchaseProduct->note = $request->note[$key];
                $PurchaseProduct->price_per_kg = $request->price_per_kg[$key];
                $PurchaseProduct->total_price = $request->total_price[$key];
                $PurchaseProduct->purchase_order = '1';
                $PurchaseProduct->save();


            }


            foreach ($request->get('extracost_note') as $key => $extracost_note) {
                if ($extracost_note != "") {
                    $pecrandomkey = Str::random(5);

                    $PurchaseExtracost = new PurchaseExtracost;
                    $PurchaseExtracost->unique_key = $pecrandomkey;
                    $PurchaseExtracost->purchase_id = $insertedId;
                    $PurchaseExtracost->extracost_note = $extracost_note;
                    $PurchaseExtracost->extracost = $request->extracost[$key];
                    $PurchaseExtracost->purchase_order = '1';
                    $PurchaseExtracost->save();
                }
            }



            $PurchseData = BranchwiseBalance::where('supplier_id', '=', $supplier_id)->where('branch_id', '=', $bill_branchid)->first();
            if($PurchseData != ""){

                $old_grossamount = $PurchseData->purchase_amount;
                $old_paid = $PurchseData->purchase_paid;

                $gross_amount = $request->get('gross_amount');
                $payable_amount = $request->get('payable_amount');

                $new_grossamount = $old_grossamount + $gross_amount;
                $new_paid = $old_paid + $payable_amount;
                $new_balance = $new_grossamount - $new_paid;

                DB::table('branchwise_balances')->where('supplier_id', $supplier_id)->where('branch_id', $bill_branchid)->update([
                    'purchase_amount' => $new_grossamount,  'purchase_paid' => $new_paid, 'purchase_balance' => $new_balance
                ]);

            }else {
                $gross_amount = $request->get('gross_amount');
                $payable_amount = $request->get('payable_amount');
                $balance_amount = $gross_amount - $payment;

                $data = new BranchwiseBalance();

                $data->supplier_id = $supplier_id;
                $data->branch_id = $bill_branchid;
                $data->purchase_amount = $request->get('gross_amount');
                $data->purchase_paid = $payment;
                $data->purchase_balance = $balance_amount;
                $data->save();
            }

            return redirect()->route('purchaseorder.purchaseorder_index')->with('add', 'Purchase Data added successfully!');





    }



    public function purchaseorder_edit($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->where('purchase_order', '=', '1')->first();
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->where('purchase_order', '=', '1')->get();

        return view('page.backend.purchaseorder.purchaseorder_edit', compact('productlist', 'branch', 'supplier', 'bank', 'PurchaseData', 'PurchaseProducts'));
    }


    public function purchaseorder_update(Request $request, $unique_key)
    {

        $branch_id = $request->get('branch_id');


        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->where('purchase_order', '=', '1')->first();

        $Purchase_Data->supplier_id = $request->get('supplier_id');
        $Purchase_Data->branch_id = $request->get('branch_id');
        $Purchase_Data->date = $request->get('date');
        $Purchase_Data->time = $request->get('time');
        $Purchase_Data->bank_id = $request->get('bank_id');
        $Purchase_Data->update();

        $PurchaseId = $Purchase_Data->id;

        // Purchase Products Table

        $getinsertedP_Products = PurchaseProduct::where('purchase_id', '=', $PurchaseId)->where('purchase_order', '=', '1')->get();
        $Purchaseproducts = array();
        foreach ($getinsertedP_Products as $key => $getinserted_P_Products) {
            $Purchaseproducts[] = $getinserted_P_Products->id;
        }

        $updatedpurchaseproduct_id = $request->purchase_detail_id;
        $updated_PurchaseProduct_id = array_filter($updatedpurchaseproduct_id);
        $different_ids = array_merge(array_diff($Purchaseproducts, $updated_PurchaseProduct_id), array_diff($updated_PurchaseProduct_id, $Purchaseproducts));

        if (!empty($different_ids)) {
            foreach ($different_ids as $key => $different_id) {
                PurchaseProduct::where('id', $different_id)->delete();
            }
        }

        foreach ($request->get('purchase_detail_id') as $key => $purchase_detail_id) {
            if ($purchase_detail_id > 0) {

                $updateproduct_id = $request->product_id[$key];

                $ids = $purchase_detail_id;
                $purchaseID = $PurchaseId;
                $productlist_id = $request->product_id[$key];
                $bagorkg = $request->bagorkg[$key];
                $count = $request->count[$key];
                $date = $request->get('date');
                $purchase_branch_id = $request->get('branch_id');
                $purchase_order = '1';

                DB::table('purchase_products')->where('id', $ids)->update([
                    'purchase_id' => $purchaseID,  'productlist_id' => $updateproduct_id,  'bagorkg' => $bagorkg,  'count' => $count,  'date' => $date,  'branch_id' => $purchase_branch_id,  'purchase_order' => $purchase_order
                ]);

            } else if ($purchase_detail_id == '') {
                if ($request->product_id[$key] > 0) {


                    $p_prandomkey = Str::random(5);

                    $PurchaseProduct = new PurchaseProduct;
                    $PurchaseProduct->unique_key = $p_prandomkey;
                    $PurchaseProduct->purchase_id = $PurchaseId;
                    $PurchaseProduct->date = $request->get('date');
                    $PurchaseProduct->branch_id = $request->get('branch_id');
                    $PurchaseProduct->productlist_id = $request->product_id[$key];
                    $PurchaseProduct->bagorkg = $request->bagorkg[$key];
                    $PurchaseProduct->count = $request->count[$key];
                    $PurchaseProduct->purchase_order = '1';
                    $PurchaseProduct->save();



                }
            }
        }

        return redirect()->route('purchaseorder.purchaseorder_index')->with('update', 'Updated Purchase information has been added to your list.');

    }



    public function getpurchaseorderview()
    {
        $purchase_id = request()->get('purchase_id');
        $get_Purchase = Purchase::where('soft_delete', '!=', 1)->where('purchase_order', '=', '1')
                                    ->where('id', '=', $purchase_id)
                                    ->get();
        $output = [];
        foreach ($get_Purchase as $key => $get_Purchase_data) {

            $Supplier_namearr = Supplier::where('id', '=', $get_Purchase_data->supplier_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $branch_namearr = Branch::where('id', '=', $get_Purchase_data->branch_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            $bank_namearr = Bank::where('id', '=', $get_Purchase_data->bank_id)->where('soft_delete', '!=', 1)->where('status', '!=', 1)->first();
            if($bank_namearr != ""){
                $bank_name = $bank_namearr->name;
            }else {
                $bank_name = '';
            }
            $output[] = array(
                'suppliername' => $Supplier_namearr->name,
                'supplier_contact_number' => $Supplier_namearr->contact_number,
                'supplier_shop_name' => $Supplier_namearr->shop_name,
                'supplier_shop_address' => $Supplier_namearr->shop_address,
                'branchname' => $branch_namearr->name,
                'branch_contact_number' => $branch_namearr->contact_number,
                'branch_shop_name' => $branch_namearr->shop_name,
                'branch_address' => $branch_namearr->address,

                'date' => date('d m Y', strtotime($get_Purchase_data->date)),
                'time' => date('h:i A', strtotime($get_Purchase_data->time)),

                'bank_namedata' => $bank_name,
                'purchase_total_amount' => $get_Purchase_data->total_amount,
                'commission_amount' => $get_Purchase_data->commission_amount,
                'commission_percent' => $get_Purchase_data->commission_percent,
                'commission_ornet' => $get_Purchase_data->commission_ornet,
                'tot_comm_extracost' => $get_Purchase_data->tot_comm_extracost,
                'purchase_gross_amount' => $get_Purchase_data->gross_amount,
                'purchase_old_balance' => $get_Purchase_data->old_balance,
                'purchase_grand_total' => $get_Purchase_data->grand_total,
                'purchase_paid_amount' => $get_Purchase_data->paid_amount,
                'purchase_balance_amount' => $get_Purchase_data->balance_amount,
                'purchase_bill_no' => $get_Purchase_data->bill_no,
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


    public function purchaseorder_invoice($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->where('purchase_order', '=', '1')->first();
        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->where('purchase_order', '=', '1')->get();



        return view('page.backend.purchaseorder.purchaseorder_invoice', compact('productlist', 'branch', 'supplier', 'bank', 'PurchaseData', 'PurchaseProducts'));
    }



    public function purchaseorder_invoiceupdate(Request $request, $unique_key)
    {


        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->first();

        $Purchase_Data->bank_id = $request->get('bank_id');
        $Purchase_Data->total_amount = $request->get('total_amount');

        $Purchase_Data->commission_ornet = $request->get('commission_ornet');
        $Purchase_Data->commission_percent = $request->get('commission_percent');
        $Purchase_Data->commission_amount = $request->get('commission_amount');

        $Purchase_Data->tot_comm_extracost = $request->get('tot_comm_extracost');
        $Purchase_Data->gross_amount = $request->get('gross_amount');
        $Purchase_Data->old_balance = $request->get('old_balance');
        $Purchase_Data->grand_total = $request->get('grand_total');
        $Purchase_Data->paid_amount = $request->get('payable_amount');
        $Purchase_Data->balance_amount = $request->get('pending_amount');
        $Purchase_Data->status = 1;
        $Purchase_Data->update();






        $PurchaseId = $Purchase_Data->id;

        // Purchase Products Table



        foreach ($request->get('purchase_detail_id') as $key => $purchase_detail_id) {
            if ($purchase_detail_id > 0) {

                $updateproduct_id = $request->product_id[$key];

                $ids = $purchase_detail_id;
                $purchaseID = $PurchaseId;
                $price_per_kg = $request->price_per_kg[$key];
                $total_price = $request->total_price[$key];

                DB::table('purchase_products')->where('id', $ids)->update([
                    'purchase_id' => $purchaseID, 'price_per_kg' => $price_per_kg, 'total_price' => $total_price
                ]);

            }
        }


        foreach ($request->get('extracost_note') as $key => $extracost_note) {
            if ($extracost_note != "") {
                $pecrandomkey = Str::random(5);

                $PurchaseExtracost = new PurchaseExtracost;
                $PurchaseExtracost->unique_key = $pecrandomkey;
                $PurchaseExtracost->purchase_id = $PurchaseId;
                $PurchaseExtracost->extracost_note = $extracost_note;
                $PurchaseExtracost->extracost = $request->extracost[$key];
                $PurchaseExtracost->purchase_order = '1';
                $PurchaseExtracost->save();
            }
        }



        $PurchseData = BranchwiseBalance::where('supplier_id', '=', $Purchase_Data->supplier_id)->where('branch_id', '=', $Purchase_Data->branch_id)->first();
        if($PurchseData != ""){

            $old_grossamount = $PurchseData->purchase_amount;
            $old_paid = $PurchseData->purchase_paid;

            $gross_amount = $request->get('gross_amount');
            $payable_amount = $request->get('payable_amount');

            $new_grossamount = $old_grossamount + $gross_amount;
            $new_paid = $old_paid + $payable_amount;
            $new_balance = $new_grossamount - $new_paid;

            DB::table('branchwise_balances')->where('supplier_id', $Purchase_Data->supplier_id)->where('branch_id', $Purchase_Data->branch_id)->update([
                'purchase_amount' => $new_grossamount,  'purchase_paid' => $new_paid, 'purchase_balance' => $new_balance
            ]);

        }else {
            $gross_amount = $request->get('gross_amount');
            $payable_amount = $request->get('payable_amount');
            $balance_amount = $gross_amount - $payable_amount;

            $data = new BranchwiseBalance();

            $data->supplier_id = $Purchase_Data->supplier_id;
            $data->branch_id = $Purchase_Data->branch_id;
            $data->purchase_amount = $request->get('gross_amount');
            $data->purchase_paid = $request->get('payable_amount');
            $data->purchase_balance = $balance_amount;
            $data->save();
        }

        return redirect()->route('purchaseorder.purchaseorder_index')->with('update', 'Updated Purchase information has been added to your list.');

    }



    public function purchaseorder_invoiceedit($unique_key)
    {
        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->first();
        $productlist = Productlist::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $branch = Branch::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $supplier = Supplier::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $bank = Bank::orderBy('name', 'ASC')->where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->get();
        $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $PurchaseData->id)->get();

        return view('page.backend.purchaseorder.purchaseorder_invoiceedit', compact('productlist', 'branch', 'supplier', 'bank', 'PurchaseData', 'PurchaseProducts', 'PurchaseExtracosts'));
    }


    public function purchaseorder_invoiceeditupdate(Request $request, $unique_key)
    {

        $Purchase_Data = Purchase::where('unique_key', '=', $unique_key)->first();

        $purchase_branch_id = $Purchase_Data->branch_id;
        $purchase_supplier_id = $Purchase_Data->supplier_id;


        $PurchasebranchwiseData = BranchwiseBalance::where('supplier_id', '=', $purchase_supplier_id)->where('branch_id', '=', $purchase_branch_id)->first();
        if($PurchasebranchwiseData != ""){


            $old_grossamount = $PurchasebranchwiseData->purchase_amount;
            $old_paid = $PurchasebranchwiseData->purchase_paid;

            $oldentry_grossamount = $Purchase_Data->gross_amount;
            $oldentry_paid = $Purchase_Data->paid_amount;

            $gross_amount = $request->get('gross_amount');
            $payable_amount = $request->get('payable_amount');



            if($oldentry_grossamount > $gross_amount){
                $newgross = $oldentry_grossamount - $gross_amount;
                $updated_gross = $old_grossamount - $newgross;
            }else if($oldentry_grossamount < $gross_amount){
                $newgross = $gross_amount - $oldentry_grossamount;
                $updated_gross = $old_grossamount + $newgross;
            }else if($oldentry_grossamount == $gross_amount){
                $updated_gross = $old_grossamount;
            }


            if($oldentry_paid > $payable_amount){
                $newPaidAmt = $oldentry_paid - $payable_amount;
                $updated_paid = $old_paid - $newPaidAmt;
            }else if($oldentry_paid < $payable_amount){
                $newPaidAmt = $payable_amount - $oldentry_paid;
                $updated_paid = $old_paid + $newPaidAmt;
            }else if($oldentry_paid == $payable_amount){
                $updated_paid = $old_paid;
            }

            $new_balance = $updated_gross - $updated_paid;

            DB::table('branchwise_balances')->where('supplier_id', $purchase_supplier_id)->where('branch_id', $purchase_branch_id)->update([
                'purchase_amount' => $updated_gross,  'purchase_paid' => $updated_paid, 'purchase_balance' => $new_balance
            ]);

        }

        $Purchase_Data->total_amount = $request->get('total_amount');
        $Purchase_Data->commission_ornet = $request->get('commission_ornet');
        $Purchase_Data->commission_percent = $request->get('commission_percent');
        $Purchase_Data->commission_amount = $request->get('commission_amount');

        $Purchase_Data->tot_comm_extracost = $request->get('tot_comm_extracost');
        $Purchase_Data->gross_amount = $request->get('gross_amount');
        $Purchase_Data->old_balance = $request->get('old_balance');
        $Purchase_Data->grand_total = $request->get('grand_total');
        $Purchase_Data->paid_amount = $request->get('payable_amount');
        $Purchase_Data->balance_amount = $request->get('pending_amount');
        $Purchase_Data->purchase_remark = $request->get('purchase_remark');
        $Purchase_Data->update();


        $PurchaseId = $Purchase_Data->id;

        foreach ($request->get('purchase_detail_id') as $key => $purchase_detail_id) {
            if ($purchase_detail_id > 0) {
                $updateproduct_id = $request->product_id[$key];

                    $ids = $purchase_detail_id;
                    $PurchaseId = $PurchaseId;
                    $productlist_id = $request->product_id[$key];
                    $bagorkg = $request->bagorkg[$key];
                    $count = $request->count[$key];
                    $price_per_kg = $request->price_per_kg[$key];
                    $total_price = $request->total_price[$key];

                    DB::table('purchase_products')->where('id', $ids)->update([
                        'purchase_id' => $PurchaseId,  'productlist_id' => $updateproduct_id,  'bagorkg' => $bagorkg,  'count' => $count, 'price_per_kg' => $price_per_kg, 'total_price' => $total_price
                    ]);

            }

        }

        return redirect()->route('purchaseorder.purchaseorder_index')->with('update', 'Updated Purchase information has been added to your list.');

    }




    public function purchaseorder_printview($unique_key)
    {

        $PurchaseData = Purchase::where('unique_key', '=', $unique_key)->first();

        $suppliername = Supplier::where('id', '=', $PurchaseData->supplier_id)->first();
        $supplier_upper = strtoupper($suppliername->name);
        $branchname = Branch::where('id', '=', $PurchaseData->branch_id)->first();
        $bankname = Bank::where('id', '=', $PurchaseData->bank_id)->first();

        $productlist = Productlist::where('soft_delete', '!=', 1)->where('status', '!=', 1)->get();
        $PurchaseProducts = PurchaseProduct::where('purchase_id', '=', $PurchaseData->id)->get();
        $extracostamount = $PurchaseData->tot_comm_extracost - $PurchaseData->commission_amount;
        $PurchaseExtracosts = PurchaseExtracost::where('purchase_id', '=', $PurchaseData->id)->get();

        return view('page.backend.purchaseorder.purchaseorder_printview', compact('PurchaseData', 'suppliername', 'branchname', 'bankname', 'PurchaseProducts', 'productlist', 'supplier_upper', 'extracostamount', 'PurchaseExtracosts'));
    }
















}
