<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Alert;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $data['users'] = User::where('role', '!=', 'SuperAdmin')
            ->where('deleted_at', null)
            ->get();

        return view('users.index', $data);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $user->update($request->all());

        Alert::success('success', 'Success update user');

        return redirect()->back();
    }

    public function destroy($id)
    {

        $user = User::find($id);

        $user->update([
            'deleted_at' => Carbon::now()
        ]);

        Alert::success('success', 'Success delete user');

        return redirect()->back();
    }
}
