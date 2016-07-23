<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mail;

class MailRobot extends Model
{
    /**
     * Send welcome message to user
     *
     * @param $user
     */
    public static function welcome($user) {
        return Mail::send('email.welcome', ['user' =>$user], function ($m) use($user) {
            $m->from('robot@nusding.info', 'NUSDing');
            $m->to($user->email, $user->name)->subject('Welcome, ' . $user->name . ' - NUSDing');
        });
    }

    /**
     * Send notification to user email
     *
     * @param $user
     * @param $type
     */
    public static function notification($user, $notification) {
        // check user setting
        $settings = $user->settings;
        if ($notification->type == 11 && !$settings->email_messages) {
            return ;
        }
        if ($notification->type == 1 && !$settings->email_invitations) {
            return ;
        }
        if ($notification->type == 2 && !$settings->email_updates) {
            return ;
        }
        if ($notification->type == 6 && !$settings->email_replies) {
            return ;
        }
        if ($notification->type == 7 && !$settings->email_votings) {
            return ;
        }
        if ($notification->type == 9 && !$settings->email_reply_votings) {
            return ;
        }
        if ($notification->type == 10 && !$settings->email_subscriptions) {
            return ;
        }

        return Mail::send('email.notification', ['user' =>$user, 'notification' => $notification], function ($m) use($user, $notification) {
            $m->from('robot@nusding.info', 'NUSDing');
            $m->to($user->email, $user->name)->subject('New notification : ' . $notification->title . ' - NUSDing');
        });
    }
}
