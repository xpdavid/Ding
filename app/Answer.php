<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * Define fillable field in the model
     *
     * @var array
     */
    protected $fillable = [
        'answer'
    ];

    /**
     * A Answer belong to a question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question() {
        return $this->belongsTo('App\Question', 'question_id');
    }

    

    /**
     * A answer belongs to a user (question answered by a user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
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

    /**
     * Display the net votes of the current answer
     *
     * @return mixed
     */
    public function getNetVotesAttribute() {
        return $this->vote_up_users()->count() - $this->vote_down_users()->count();
    }
}