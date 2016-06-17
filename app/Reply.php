<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /**
     * A reply belongs to a user (is posted by a user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User');
    }

    /**
     * A reply may be voted up by many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function vote_up_users() {
        return $this->belongsToMany('App\User', 'user_vote_up_reply');
    }

    /**
     * A reply belongs to a question or a answer
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function for_item() {
        return $this->morphTo();
    }
}
