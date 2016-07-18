<?php

namespace App\Http\Controllers\Auth;

use App\Point;
use App\Subscribe;
use App\User;
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

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

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

        $subscribe = Subscribe::create();
        $user->subscribe()->save($subscribe);

        $this->generateUrlName($user);

        // add point to user
        Point::add_point($user, 18, []);

        return $user;
    }


    /**
     * Auto-generate url-name for user.
     *
     * @param User $user
     * @return string
     */
    private function generateUrlName(User $user) {
        $url_name = $user->name;

        // replace all the non-alphanumeric characters
        $url_name = preg_replace("/[^A-Za-z0-9 ]/", '', $url_name);
        // replace the space with underscore
        $url_name = str_replace(' ', '_', $url_name);

        // in order to prevent using same name, add user id at last.
        $user->url_name = $url_name . $user->id;

        $user->save();
    }


}
