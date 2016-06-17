<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    /**
     * A Answer belong to a question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question() {
        return $this->belongsTo('App\Question');
    }

    

    /**
     * A answer belongs to a user (question answered by a user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function owner() {
        return $this->belongsToMany('App\User');
    }

    /**
     * A answer can be voted up by many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function vote_up_users() {
        return $this->belongsToMany('App\User', 'user_vote_up_answer');
    }

    /**
     * A answers can be voted down by many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function vote_down_users() {
        return $this->belongsToMany('App\User', 'user_vote_down_answer');
    }

    /**
     * A answer may have replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function replies() {
        return $this->morphMany('App\Reply', 'for_item');
    }
}
