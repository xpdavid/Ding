<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * A question belongs to a user (is posted by a user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User');
    }

    /**
     * A question has many answers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers() {
        return $this->hasMany('App\Answer');
    }

    /**
     * A question may have replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function replies() {
        return $this->morphMany('App\Reply', 'for_item');
    }

    /**
     * A question belongs to many topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function topics() {
        return $this->belongsToMany('App\Topic');
    }
}
