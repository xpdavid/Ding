<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;


class Conversation extends Model
{

    /**
     * Define the fillable attribute so that the request will not accidentally written other column in database
     *
     * @var array
     */
    protected $fillable = [
        'can_reply'
    ];

    /**
     * Define eloquent relationship to user.
     * A certain conversation belong to many user.
     * There could be more than two user participate in the conversation
     */
    public function users() {
        return $this->belongsToMany('App\User')->withTimestamps();
    }

    /**
     * Define eloquent relationship to message
     * A conversation can has many messages
     */
    public function messages() {
        return $this->hasMany('App\Message');
    }
    

}
