<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        return view('backend.profile.index');
    }

    public function update(Request $request)
    {

        $user = Auth::user();

        $this->validate($request, [
            'name' => 'required |max:190|string',
            'email' => 'required|email|max:190|unique:users,email,' . $user->id,

        ]);

        // Update user info
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        // upload images
        if ($request->hasFile('avatar')) {
            $user->addMedia($request->avatar)->toMediaCollection('avatar');
        }
        // return with success msg
        notify()->success('Profile Successfully Updated.', 'Updated');
        return back();
    }

    public function changePassword(){

        return view('backend.profile.password');

    }

    public function updatePassword(Request $request)
    {

        $this->validate($request, [
            'password' => 'required |confirmed',
            'current_password' => 'required'

        ]);
        $hashedPassword = Auth::user()->password;
        if (Hash::check($request->current_password, $hashedPassword)) {
            if (!Hash::check($request->password, $hashedPassword)) {
                Auth::user()->update([
                    'password' => Hash::make($request->password)
                ]);
                Auth::logout();
                notify()->success('Password Successfully Changed.', 'Success');
                return redirect()->route('login');
            } else {
                notify()->warning('New password cannot be the same as old password.', 'Warning');
            }
        } else {
            notify()->error('Current password not match.', 'Error');
        }
        return redirect()->back();
    }



}
