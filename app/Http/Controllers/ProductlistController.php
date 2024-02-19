<?php

namespace App\Http\Controllers;
use App\Models\Productlist;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductlistController extends Controller
{

    

    
    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Productlist();

        $data->unique_key = $randomkey;
        $data->name = $request->get('name');
        
        $data->save();


        return redirect()->route('product.index')->with('add', 'Product Data added successfully!');
    }


    public function edit(Request $request, $unique_key)
    {
        $ProductlistData = Productlist::where('unique_key', '=', $unique_key)->first();

        $ProductlistData->name = $request->get('name');
        $ProductlistData->update();

        return redirect()->route('product.index')->with('update', 'Product Data updated successfully!');
    }
}
