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

        if ($request->has('personal_domain')){
            $settings->update(['personal_domain_modified' => true]);
            $user->update(['url_name' => $request->get('personal_domain')]);
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

        return Redirect::back();
    }
}
