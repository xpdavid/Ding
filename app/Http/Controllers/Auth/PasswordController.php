<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords {
        sendResetLinkEmail as sendResetLinkEmail_parent;
    }

    protected $subject = "Your Email Reset Link - NUSDing";

    protected $redirectTo = "/";
    
    
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware());
    }

    /**
     * Send a reset link to the given user. (override)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendResetLinkEmail(Request $request) {
        $this->validate($request, [
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        return $this->sendResetLinkEmail_parent($request);
    }
    
    
}
