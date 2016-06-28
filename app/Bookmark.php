<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Bookmark extends Model
{
    /**
     * Define fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'is_public',
    ];

    /**
     * A bookmark blongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * A bookmark has many questions
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions() {
        return $this->belongsToMany('App\Question', 'bookmark_question');
    }

    /**
     * A bookmark has many answers
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function answers() {
        return $this->belongsToMany('App\Answer', 'bookmark_answer');
    }

    /**
     * A bookmark is subscribed by many users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers() {
        return $this->belongsToMany('App\Subscribe', 'subscribe_bookmark');
    }

    /**
     * Check if a item is in the bookmark
     *
     * @param $bookmark_id
     * @param $item_id
     * @param $item_type
     * @return bool
     */
    public static function isIn($bookmark_id, $item_id, $item_type) {
        if ($item_type == 'question') {
            return DB::table('bookmark_question')
                ->whereBookmarkId($bookmark_id)
                ->whereQuestionId($item_id)
                ->count() > 0;
        } else if ($item_type == 'answer') {
            return DB::table('bookmark_answer')
                ->whereBookmarkId($bookmark_id)
                ->whereAnswerId($item_id)
                ->count() > 0;
        }
    }
}
