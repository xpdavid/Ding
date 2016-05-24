<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * Define the fillable attribute so that the request will not accidentally written other column in database
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];

    /**
     * Define eloquent relationship to Conversation.
     * A message belong to a certain conversation
     */
    public function conversation() {
        return $this->belongsTo('App\Conversation');
    }

    /**
     * Define eloquent relationship to User.
     * A message has unread users.
     */
    public function unreadUsers() {
        return $this->belongsToMany('App\User', 'unreadMessage_user');
    }

    /**
     * Define eloquent relationship to User
     * A message is sent by a user
     */
    public function sendBy() {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * @Override
     * Override Delte method to detach the belongsToMany relationship
     */
    public function delete() {
        // first delete all the relevant unreadUsers
        $this->unreadUsers()->detach();

        // then call parent method delete
        parent::delete();
    }
}
