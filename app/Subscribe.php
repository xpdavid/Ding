<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    /**
     * A subscribe belong to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User');
    }

    /**
     * A subscribe has subscribed question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions() {
        return $this->belongsToMany('App\Question', 'subscribe_question');
    }

    /**
     * A subscribe has subscribed topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function topics() {
        return $this->belongsToMany('App\Topic', 'subscribe_topic');
    }

    /**
     * A subscribe has subscribed users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('App\User', 'subscribe_user');
    }

    /**
     * Check has subscribed to a specific model
     *
     * @param $model_id
     * @param $type
     * @return bool
     */
    public function checkHasSubscribed($model_id, $type) {
        switch ($type) {
            case 'question' :
                return DB::table('subscribe_question')
                    ->whereSubscribeId($this->id)
                    ->whereQuestionId($model_id)
                    ->count() > 0;
            case 'topic' :
                return DB::table('subscribe_topic')
                    ->whereSubscribeId($this->id)
                    ->whereTopicId($model_id)
                    ->count() > 0;
            case 'user' :
                return DB::table('subscribe_user')
                    ->whereSubscribeId($this->id)
                    ->whereUserId($model_id)
                    ->count() > 0;
            default :
                return false;
        }
    }


}
