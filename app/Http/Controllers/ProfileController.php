<?php

namespace App\Http\Controllers;

use SweetAlert;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Carbon\Carbon;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user = User::where('id', Auth::user()->id)->first();
        return view('profile.index', compact('user'));
    }
    public function update(Request $request)
    {
        //validation password format
        $this->validate(
            $request,
            [
                'password' =>  'confirmed'
            ]
        );

        $user = User::where('id', Auth::user()->id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nohp = $request->nohp;
        $user->alamat = $request->alamat;
        if (!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->update();

        alert()->success('Profile Berhasil Di Update', 'SUKSES');
        return redirect('profile');
    }
}
