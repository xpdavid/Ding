<?php

namespace App;

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
     * A topic has it parent topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent_topic() {
        return $this->belongsTo('App\Topic', 'parent_id');
    }

    /**
     * A topic has many child topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function child_topics() {
        return $this->hasMany('App\Topic', 'parent_id');
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
        return $query->where('parent_id', NULL);
    }
}
