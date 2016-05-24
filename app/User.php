<?php

namespace App;

use App\Conversation;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Define eloquent relationship to conversation model
     * A user can have many conversations.
     */
    public function conversations() {
        return $this->belongsToMany('App\Conversation');
    }

    /**
     * Define eloquent relationship to message
     * A user can have many unread message
     */
    public function unreadMessages() {
        return $this->belongsToMany('App\Message', 'unreadMessage_user');
    }

    /**
     * Define eloquent relationship to message
     * A user can send many messages
     */
    public function sentMessages() {
        return $this->hasMany('App\Message');
    }

    /**
     * Determine whether the user is in the conversation
     *
     * @param \App\Conversation $conversation
     * @return bool
     */
    public function isInConversation(Conversation $conversation) {
        return in_array($this->id, $conversation->users->lists('id')->all());
    }
}
