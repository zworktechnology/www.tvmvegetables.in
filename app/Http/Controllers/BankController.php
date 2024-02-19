<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankController extends Controller
{
    public function index()
    {
        $data = Bank::where('soft_delete', '!=', 1)->get();

        return view('page.backend.bank.index', compact('data'));
    }

    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Bank();

        $data->unique_key = $randomkey;
        $data->name = $request->get('name');
        $data->details = $request->get('details');
        
        $data->save();


        return redirect()->route('bank.index')->with('add', 'bank added successfully!');
    }


    public function edit(Request $request, $unique_key)
    {
        $BankData = Bank::where('unique_key', '=', $unique_key)->first();
        $BankData->name = $request->get('name');
        $BankData->details = $request->get('details');
        $BankData->status = $request->get('status');
        
        $BankData->update();

        return redirect()->route('bank.index')->with('update', 'bank updated successfully!');
    }

    public function delete($unique_key)
    {
        $data = Bank::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('bank.index')->with('soft_destroy', 'Successfully deleted the bank !');
    }
}
