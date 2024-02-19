<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UnitController extends Controller
{
    public function index()
    {
        $data = Unit::where('soft_delete', '!=', 1)->get();

        return view('page.backend.unit.index', compact('data'));
    }

    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Unit();

        $data->unique_key = $randomkey;
        $data->name = $request->get('name');
        
        $data->save();


        return redirect()->route('unit.index')->with('add', 'Unit added successfully!');
    }


    public function edit(Request $request, $unique_key)
    {
        $UnitData = Unit::where('unique_key', '=', $unique_key)->first();

        $UnitData->name = $request->get('name');
        $UnitData->status = $request->get('status');
        
        $UnitData->update();

        return redirect()->route('unit.index')->with('update', 'Unit updated successfully!');
    }

    public function delete($unique_key)
    {
        $data = Unit::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('unit.index')->with('soft_destroy', 'Successfully deleted the unit !');
    }


}
