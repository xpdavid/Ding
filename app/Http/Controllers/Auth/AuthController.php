<?php

namespace App\Http\Controllers\Auth;

use App\Point;
use App\Subscribe;
use App\User;
use App\MailRobot;
use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers {
        register as register_parent;
        login as login_parent;
    }

    use ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    protected $loginView = 'auth.login';
    protected $registerView = 'auth.register';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->generateUrlName($user);

        // real-name system
        // require binding with ivle
        $user->authGroup_id = 8;

        $user->save();

        // add point to user
        Point::add_point($user, 18, []);

        // send  welcome message
        MailRobot::welcome($user);

        return $user;
    }

    /**
     * Override register method to validate recapacha
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $this->validate($request, [
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
        return $this->register_parent($request);
    }

    /**
     * Override register method to validate recapacha
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $this->validate($request, [
            'g-recaptcha-response' => 'required|recaptcha',
        ]);
        return $this->login_parent($request);
    }


}
