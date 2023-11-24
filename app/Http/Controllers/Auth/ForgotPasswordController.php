<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Redirect;
;
use Hash;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function forgetPasswordLoad()
    {
        return view('auth.passwords.forgetPassword');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
                'email.required' => 'The email field is required.',
                'email.email' => 'Invalid Email Id.',
            ]);
        $otp = 1234;
        $request->session()->put('email', $request->email);
        $user = Admin::where('email', $request->email)->first();

        if (!$user) {
            return Redirect::back()->withErrors(['email' => 'Invalid Email Id']);
        }
        Admin::where("id", $user->id)->update(['email_otp' => $otp]);
        return view('auth.passwords.otpVerification');
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|integer',
        ]);
        $sessionEmail = $request->session()->get('email');
        $user = Admin::where('email', $sessionEmail)->first();
        if ($request->input('otp') == $user->email_otp) {
            return view('auth.passwords.resetPassword');
        } else {
            return view('auth.passwords.otpVerification')->withErrors(['otp' => 'You entered wrong OTP']);
        }
    }
    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ], [
                'password.required' => 'Please enter your new password.',
                'password.string' => 'The password must be a string.',
            ]);

        if ($request->input('password') != $request->input('password_confirmation')) {
            return view('auth.passwords.resetPassword')->withErrors(['password' => 'The password confirmation does not match.']);
        }
        $sessionEmail = $request->session()->get('email');
        $user = Admin::where('email', $sessionEmail)->first();
        Admin::where("id", $user->id)->update(['password' => Hash::make($request->password)]);
        return redirect()->route('login')->with('status', 'Password updated successfully!');
    }
}