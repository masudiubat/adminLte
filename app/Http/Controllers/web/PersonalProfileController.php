<?php

namespace App\Http\Controllers\web;

use App\User;
use Illuminate\Http\Request;
use App\Library\ActivityLogLib;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class PersonalProfileController extends Controller
{
    /**
     * method for show personal profile detail
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('pages.user.personal-profile.show', ['user' => $user]);
    }

    /**
     *  method for change personal password
     */

    public function change_password(Request $request, $id)
    {

        $this->validate($request, [
            'current_password' => 'required',
            'password' => 'required | min:8',
            'password_confirmation' => 'required|same:password'
        ], [
            'current_password.required' => 'Current password field is mandatory field.',
            'password.required' => 'Password field is mandatory field.',
            'password.min' => 'Password length must be minimum 8 digits.'

        ]);

        $user = User::findOrFail($id);
        $currentPassword = $request->input('current_password');
        if (password_verify($currentPassword, $user->password)) {
            $update = User::find(auth()->user()->id)->update(['password' => bcrypt($request->input('password'))]);
            if ($update) {
                ActivityLogLib::addLog('User has changed password successfully.', 'success');
                Toastr::success('Your password has changed successfully.', 'success');
                return redirect()->back();
            } else {
                ActivityLogLib::addLog('User has tried to changed password.', 'error');
                Toastr::error('W00ps! Something went wrong. Try again.', 'error');
                return redirect()->back();
            }
        } else {
            ActivityLogLib::addLog('User has tried to changed password.', 'error');
            Toastr::error('Your current password is wrong.', 'error');
            return redirect()->back();
        }
    }
}
