<?php

namespace App\Http\Controllers;

use App\LoginMethod;
use App\MailRobot;
use App\User;
use Auth;
use Carbon\Carbon;
use Route;
use App\Point;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Redirect;

class LoginMethodController extends Controller
{
    public function __construct() {
        $this->middleware('auth_real', ['only' =>
            [
                'unbindIVLE'
            ]]);
    }

    /**
     * Response login with IVLE method
     *
     * @return Redirect
     */
    public function IVLE() {
        $ivle_base = 'https://ivle.nus.edu.sg/api/login';
        $apikey = config('ding.IVLE_API_Key');
        $back_url = action('LoginMethodController@IVLE_callback');
        return redirect($ivle_base . '?apikey=' . $apikey . '&url=' . $back_url);
    }

    /**
     * Response ivle Callback
     *
     * @param Request $request
     * @return Redirect
     */
    public function IVLE_callback(Request $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $token = $request->get('token');
        $id = $this->getID($token);
        $user = LoginMethod::findUser('IVLE', $id);
        if ($user) {
            Auth::login($user, true);
            $this->refreshToken($user, 'IVLE');
            return redirect('/');
        } else {
            $user = User::create([
                'name' => 'Ding User',
                'password' => bcrypt(str_random(10)),
                'remember_token' => str_random(10),
            ]);

            $user->loginMethods()->save(LoginMethod::create([
                'type' => 'IVLE',
                'token' => $token
            ]));
            $this->updateID($user, 'IVLE');
            $this->updateName($user, 'IVLE');
            $this->updateEmail($user, 'IVLE');

            // set user password
            $user->setDefaultPassword();

            // regenerate url name
            $user->generateUrlName();

            // send  welcome message
            MailRobot::welcome($user);

            // add point to user
            Point::add_point($user, 18, []);

            Auth::login($user, true);
            return redirect('/');
        }

    }

    /**
     * refresh user token
     *
     * @param $user
     * @param $type
     * @return bool
     */
    public function refreshToken($user, $type) {
        $method = $user->loginMethod($type);
        if ($method) {
            if (Carbon::parse($method->expired_at)->lt(Carbon::now())) {
                $apikey = config('ding.IVLE_API_Key');
                $content = file_get_contents('https://ivle.nus.edu.sg/api/Lapi.svc/Validate?APIKey='
                    . $apikey . '&Token=' . $method->token);

                $results = json_decode($content, true);

                if ($results['Success']) {
                    $method->expired_at = Carbon::parse($results['ValidTill_js']);
                    $method->token = $results['Token'];
                    $method->save();
                } else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * update user name according to ivle
     *
     * @param $user
     * @param $type
     * @return bool
     */
    public function updateName($user, $type) {
        $method = $user->loginMethod($type);
        if ($method) {
            $apikey = config('ding.IVLE_API_Key');
            $content = file_get_contents('https://ivle.nus.edu.sg/api/Lapi.svc/UserName_Get?APIKey='
                . $apikey . '&Token=' . $method->token);

            $results = json_decode($content);

            if ($results != "") {
                $user->name = $results;
            } else {
                $user->name = "Ding User";
            }
            $user->save();
            return true;
        }
        return false;
    }

    /**
     * Update email address in IVLE
     *
     * @param $user
     * @param $type
     * @return bool
     */
    public function updateEmail($user, $type) {
        $method = $user->loginMethod($type);
        if ($method) {
            $apikey = config('ding.IVLE_API_Key');
            $content = file_get_contents('https://ivle.nus.edu.sg/api/Lapi.svc/UserEmail_Get?APIKey='
                . $apikey . '&Token=' . $method->token);

            $results = json_decode($content);

            if ($results != "") {
                $user->email = $results;
            } else {
                $user->email = "";
            }
            $user->save();
            return true;
        }
        return false;
    }


    /**
     * Get user id in IVLE
     *
     * @param $user
     * @param $type
     * @return bool
     */
    public function updateID($user, $type) {
        $method = $user->loginMethod($type);
        if ($method) {
            $apikey = config('ding.IVLE_API_Key');
            $content = file_get_contents('https://ivle.nus.edu.sg/api/Lapi.svc/UserID_Get?APIKey='
                . $apikey . '&Token=' . $method->token);

            $results = json_decode($content);

            if ($results != "") {
                $method->option1 = $results;
                $method->save();
                return false;
            } else {
                return false;
            }
        }
        return false;
    }


    /**
     * Get id according to the token
     *
     * @param $token
     * @return $string
     */
    public function getID($token) {
        $apikey = config('ding.IVLE_API_Key');
        $content = file_get_contents('https://ivle.nus.edu.sg/api/Lapi.svc/UserID_Get?APIKey='
            . $apikey . '&Token=' . $token);
        return json_decode($content);
    }


    /**
     * Bind user login with IVLE
     *
     * @return Redirect
     */
    public function bindIVLE() {
        $ivle_base = 'https://ivle.nus.edu.sg/api/login';
        $apikey = config('ding.IVLE_API_Key');
        $back_url = urlencode(action('LoginMethodController@bindIVLE_callback'));
        return redirect($ivle_base . '?apikey=' . $apikey . '&url=' . $back_url);
    }

    /**
     * Bind ivle login method
     *
     * @param Request $request
     * @return mixed
     */
    public function bindIVLE_callback(Request $request) {
        $user = Auth::user();
        $this->validate($request, [
            'token' => 'required'
        ]);

        // delete old token
        if ($user->loginMethod('IVLE')) {
            $user->loginMethod('IVLE')->delete();
        }

        // check unique id
        if (LoginMethod::findUser('IVLE', $this->getID($request->get('token')))) {
            // people has bind the ivle id
            // abort
            return redirect('/settings/account')->withErrors(['The IVLE ID has been used by another user']);
        }

        // store new token
        $user->loginMethods()->save(LoginMethod::create([
            'type' => 'IVLE',
            'token' => $request->get('token')
        ]));

        // refresh token to get expired one
        $this->refreshToken($user, 'IVLE');
        $this->updateID($user, 'IVLE');

        session()->flash('messages', ['Bind IVLE login method success']);

        // update user group
        $user->authGroup_id = 1;
        $user->save();
        $user->adjustAuthGroup();

        return redirect()->action('SettingsController@getAccount');
    }

    /**
     * Unbind ivle login method
     */
    public function unbindIVLE() {
        $user = Auth::user();

        // delete old token
        if ($user->loginMethod('IVLE')) {
            $user->loginMethod('IVLE')->delete();
            session()->flash('messages', ['Unbind IVLE login Method success']);

            // turn to unbind user
            $user->authGroup_id = 8;
            $user->save();

        }

        return redirect()->action('SettingsController@getAccount');
    }
}
