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
     * Override create method for creating necessary associate model
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes = []) {
        $answer = parent::create($attributes); // TODO: Change the autogenerated stub
        // answer hit
        Hit::create([
            'owner_type' => 'App\Answer',
            'owner_id' => $answer->id
        ]);
        return $answer;
    }

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
     * A answer may belongs to many bookmarks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarks() {
        return $this->belongsToMany('App\Bookmark', 'bookmark_answer');
    }

    /**
     * A answer has a hit object
     */
    public function getHitAttribute() {
       return Hit::whereOwnerType('App\Answer')->whereOwnerId($this->id)->first();
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


    /**
     * Search for answers
     *
     * @param $query
     * @param $key
     * @param null $range
     * @return mixed
     */
    public function scopeNoneSimilarMatch($query, $key, $range = null) {
        if ($range != null) {
            return $query->where('created_at', '>', Carbon::now()->subDays($range)->toDateTimeString())
                ->where('answer', 'LIKE', '%' . $key . '%');

        } else {
            return $query->where('answer', 'LIKE' , '%' . $key . '%');
        }

    }
}
