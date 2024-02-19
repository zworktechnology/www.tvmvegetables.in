<?php

namespace App\Http\Controllers;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

use Illuminate\Http\Request;

class ChangeProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index_password()
    {
        $today = Carbon::now()->format('Y-m-d');

        return view('page.backend.settings');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);

        return redirect()->back()->with('update', 'Password Updated !');
    }

    public function index_profile()
    {
        $today = Carbon::now()->format('Y-m-d');

        return view('page.backend.profile');
    }

    public function update_profile(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        User::find(auth()->user()->id)->update(['name'=> $request->name]);

        return redirect()->back()->with('update', 'Profile Updated !');
    }
}
