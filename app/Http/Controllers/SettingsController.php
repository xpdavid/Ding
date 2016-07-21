<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Redirect;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;

class SettingsController extends Controller
{
    /**
     * AnswerController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('realname', ['except' => ['getAccount']]);
    }

    /**
     * get the basic settings page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBasic() {
        $user = Auth::user();
        $settings = $user->settings;
        return view('settings.basic', compact('user', 'settings'));
    }

    /**
     * get the account settings page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAccount() {
        $user = Auth::user();
        $settings = $user->settings;
        return view('settings.account', compact('user', 'settings'));
    }

    /**
     * get the notification setting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getNotification() {
        $user = Auth::user();
        $settings = $user->settings;
        return view('settings.notification', compact('user', 'settings'));
    }

    /**
     * get blocking user setting
     *
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getBlocking() {
        $user = Auth::user();
        $settings = $user->settings;
        return view('settings.block', compact('user', 'settings'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function postUpdate(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings;
        $blockings = $user->blockings;
        
        if ($request->has('name')){
            $user->update(['name' => $request->get('name')]);
        }

        if ($request->has('personal_domain')) {
            // you need be authoried to do this
            if ($user->operation(10) && !$user->settings->personal_domain_modified) {
                // check not exisit the same name
                $findUser =  User::where('url_name', $request->get('personal_domain'));
                if ($findUser->exists() && $findUser->first()->id == $user->id) {
                    // do nothing as the user doesn't change the personal domain
                } else if (!$findUser->exists()) {
                    $settings->update(['personal_domain_modified' => true]);
                    $user->update(['url_name' => $request->get('personal_domain')]);
                } else {
                    return redirect('/settings/basic')->withErrors(['The domain name has been used by other user']);
                }

            }
        }

        if ($request->has('new_password') && $request->has('old_password') && $request->has('confirm_password')){
            if (Hash::check($request->get('old_password'), $user->password)){
                if($request->get('confirm_password') == $request->get('new_password')) {
                    $user->update([
                        'password' => Hash::make($request->get('new_password')),
                    ]);
                } else {
                    return Redirect::back()->withErrors(['Password mismatch']);
                }
            } else {
                return Redirect::back()->withErrors(['Old Password Wrong']);
            }
        }

        if ($request->has('receiving_messages')){

            $settings->update([
                'receiving_messages' => $request->get('receiving_messages'),
                'email_messages' => $request->get('email_messages'),
                'receiving_invitations' => $request->get('receiving_invitations'),
                'email_invitations' => $request->get('email_invitations'),
                'receiving_updates' => $request->get('receiving_updates'),
                'email_updates' => $request->get('email_updates'),
                'receiving_replies' => $request->get('receiving_replies'),
                'email_replies' => $request->get('email_replies'),
                'receiving_votings' => $request->get('receiving_votings'),
                'email_votings' => $request->get('email_votings'),
                'receiving_reply_votings' => $request->get('receiving_reply_votings'),
                'email_reply_votings' => $request->get('email_reply_votings'),
                'receiving_subscriptions' => $request->get('receiving_subscriptions'),
                'email_subscriptions' => $request->get('email_subscriptions')
                ]);
        }

        if ($request->has('cancel_block')) {
            $user = Auth::user();
            $user->blockings()->detach($request->get('cancel_block'));
        }

        if ($request->has('block_users') != ''){
            $user = Auth::user();
            foreach ($request->get('block_users') as $block_user_id) {
                // i think you cannot block yourself
                if ($block_user_id == $user->id) continue;
                $user->blockings()->detach($block_user_id);
                $user->blockings()->attach($block_user_id);
            }
            return Redirect::back();
        }

        if ($request->has('hide_topics')) {
            $user = Auth::user();
            foreach ($request->get('hide_topics') as $topic_id) {
                $user->hide_topics()->attach($topic_id);
            }
        }

        if ($request->has('cancel_hide_topic')) {
            $user = Auth::user();
            $cancel_topic_id = $request->get('cancel_hide_topic');

            $user->subscribe->topics()->detach($cancel_topic_id);
            $user->hide_topics()->detach($cancel_topic_id);
        }

        return Redirect::back();
    }
}
