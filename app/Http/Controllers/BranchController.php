<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BranchController extends Controller
{
    public function index()
    {
        $data = Branch::where('soft_delete', '!=', 1)->get()->all();

        return view('page.backend.branch.index', compact('data'));
    }

    public function store(Request $request)
    {
        $randomkey = Str::random(5);

        $data = new Branch();

        $data->name = $request->get('name');
        $data->shop_name = $request->get('shop_name');
        $data->address = $request->get('address');
        $data->contact_number = $request->get('contact_number');
        $data->mail_address = $request->get('mail_address');
        $data->web_address = $request->get('web_address');
        $data->gst_number = $request->get('gst_number');

        $logo = $request->logo;
        $filename = $data->name . $randomkey . '.' . $logo->getClientOriginalExtension();
        $request->logo->move('asset/branch', $filename);
        $data->logo = $filename;

        $data->unique_key = $randomkey;

        $data->save();

        return redirect()->route('branch.index')->with('success', 'Successfully new branch details stored.');
    }

    public function edit(Request $request, $unique_key)
    {
        $data = Branch::where('unique_key', '=', $unique_key)->first();

        $data->name = $request->get('name');
        $data->shop_name = $request->get('shop_name');
        $data->address = $request->get('address');
        $data->contact_number = $request->get('contact_number');
        $data->mail_address = $request->get('mail_address');
        $data->web_address = $request->get('web_address');
        $data->gst_number = $request->get('gst_number');

        $data->update();

        return redirect()->route('branch.index')->with('update', 'Successfully branch details updated.');
    }

    public function delete($unique_key)
    {
        $data = Branch::where('unique_key', '=', $unique_key)->first();

        $data->soft_delete = 1;

        $data->update();

        return redirect()->route('branch.index')->with('soft_destroy', 'Successfully deleted the branch !');
    }
}
