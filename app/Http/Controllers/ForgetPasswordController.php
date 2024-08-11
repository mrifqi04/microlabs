<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use RealRashid\SweetAlert\Facades\Alert;

class ForgetPasswordController extends Controller
{
    public function index()
    {
        return view('auth.forgot-password');
    }

    public function validateData(Request $request)
    {
        $user = User::where('nik', $request->nik)
            ->where('username', $request->username)
            ->first();
        if ($user) {
            return view('auth.create-new-password', compact('user'));
        }
        return redirect()->back();
    }

    public function postNewPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'confirmed']
        ]);

        if ($validator->fails()) {
            return view('auth.forgot-password');
        }

        $user = User::find($request->user_id);
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('status', 'Berhasil mengubah password');
    }
}
