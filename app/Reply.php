<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    /**
     * Define fillable field in the model
     *
     * @var array
     */
    protected $fillable = [
        'reply'
    ];

    /**
     * A reply belongs to a user (is posted by a user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
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

    /**
     * Display human readable created at date
     *
     * @return string
     */
    public function getCreatedAtHumanReadableAttribute() {
        $created_at = Carbon::parse($this->created_at);
        // if the created at date is more than 3 month ago, display the date
        if (Carbon::now()->subMonth(3)->gte($created_at)) {
            return $created_at->format('Y-m-d');
        } else {
            // or display the humanReadble text (e.g 2 days ago)
            return $created_at->diffForHumans();
        }

    }
}
