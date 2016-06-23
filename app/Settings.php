<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
	protected $fillable = [
		'personal_domain', 
		'personal_domain_modified', 
		'receiving_messages',
		'receiving_invitations',
		'receiving_updates',
		'receiving_replies',
		'receiving_votings',
		'receiving_reply_votings',
		'receiving_subscriptions',
		'email_messages',
		'email_invitations',
		'email_updates',
		'email_replies',
		'email_votings',
		'email_reply_votings',
		'email_subscriptions',
		'display_facebook', //whether facebook account is displayed
		'display_email', // whether email account is displayed
		'profile_pic_name'
	];

	/** Define eloquent relationship between User and Settings
	*   Each setting belongs to one particular user
	*/
    public function user() {
        return $this->belongsTo('App\User');
    }
}
