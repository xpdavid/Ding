<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    /**
     * Define fillable field in the model
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * A topic will have many question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions() {
        return $this->belongsToMany('App\Question');
    }


    /**
     * define query scope. similar match $name
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeSimilarMatch($query, $name) {
        return $query->where('name', 'REGEXP', '[' . $name . ']');
    }

    /**
     * Defined eloquent relationship : A topic can be the specialization of many students
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specialist() {
        return $this->belongsToMany('App\User', 'user_specialization', 'user_id', 'topic_id');
    }

    /**
     * find the topic in database
     * if it does not exsit, then create it and return
     *
     * @param $topicName
     * @return Topic
     */
    public static function findOrCreate($topicName) {
        $candidates = Topic::where('name', $topicName);
        if($candidates->count() > 0) {
            return $candidates->first();
        } else {
            $newTopic = Topic::create([
                'name' => $topicName,
                'description' => ''
            ]);
            return $newTopic;
        }
    }


    /**
     * A topic has it parent topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent_topics() {
        return $this->belongsToMany('App\Topic', 'topic_subtopic', 'subtopic_id', 'parent_topic_id');
    }

    /**
     * A topic has many child topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subtopics() {
        return $this->belongsToMany('App\Topic', 'topic_subtopic', 'parent_topic_id', 'subtopic_id');
    }

    /**
     * define query scope. like match $name
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeNoneSimilarMatch($query, $name) {
        return $query->where('name', 'LIKE', '%' . $name . '%');
    }

    public function scopeTopParentTopics($query) {
        return $query->whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('topic_subtopic')
                ->whereRaw('topic_subtopic.subtopic_id = topics.id');
        });
    }

    /**
     * Get all the distinct designations in the database
     *
     * @return array
     */
    public static function getTopicList() {
        $topic_list = topic::select('name')
            ->distinct()
            ->get()
            ->lists('name')
            ->all();

        return $topic_list;
    }
}
