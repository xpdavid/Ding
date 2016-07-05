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
        return $this->belongsToMany('App\User', 'user_vote_up_reply')->withTimestamps();
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
     * A reply can reply to a reply
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reply_to() {
        return $this->belongsTo('App\Reply', 'reply_to_reply_id');
    }

    /**
     * A reply will receive a reply
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receive_replies() {
        return $this->hasMany('App\Reply', 'reply_to_reply_id');
    }

    /**
     * Generate highlight parameters
     *
     * @return array
     */
    public function getHighlightParametersAttribute() {
        $for_item = $this->for_item;
        $index = 1;
        // calculate the page number
        foreach ($for_item->replies->sortBy('created_at') as $reply) {
            if ($reply->id == $this->id) {
                break;
            }
            $index++;
        }
        $page = ceil($index / 8); // get the page number

        //parameter needed
        //highlight_reply(reply_id, base_id, type, item_id, page)
        $highlight = [
            'reply_id' => $this->id,
            'item_id' => $for_item->id,
            'page' => $page
        ];
        if(get_class($for_item) == 'App\Question') {
            $highlight['base_id'] = 'question_comment_' . $for_item->id;
            $highlight['type'] = 'question';
        } else if (get_class($for_item) == 'App\Answer') {
            $highlight['base_id'] = 'answer_comment_' . $for_item->id;
            $highlight['type'] = 'answer';
        }
        return $highlight;
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
