<?php

namespace App\Http\Controllers\web;

use App\User;
use App\Mail\OtpSendEmail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;

class OtpVerificationController extends Controller
{
    /**
     * show otp submit page
     */
    public function show()
    {
        return view('pages.otp.show');
    }

    /**
     * verify the otp
     */
    public function verify_otp(Request $request)
    {
        $otp = $request->input('otp');
        $user = Auth::user()->id;
        $cacheOtp = Cache::get('otp');

        if ($otp == $cacheOtp) {
            User::where('id', $user)->update(['isVerified' => true]);
            return redirect('/home');
        } else {
            return Redirect::back()->withErrors(['msg' => 'Invalid OTP']);
        }
    }

    /**
     * Resend new otp code
     */
    public function resend_code()
    {
        $myEmail = Auth::user()->email;
        $value = rand(100000, 999999);
        $otp = [
            'value' => $value
        ];
        Cache::put(['otp' => $value], now()->addMinute(1));
        Mail::to($myEmail)->send(new OtpSendEmail($otp));
        return redirect()->back();
    }
}
