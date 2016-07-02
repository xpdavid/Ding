<?php

namespace App;

use App\Conversation;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'url_name', 'sex', 'self_intro', 'bio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Defined new query scope so that we can find user by their self-defined url_name
     *
     * @param $query
     * @param $url_name
     * @return mixed
     */
    public function scopeFindUrlName($query, $url_name) {
        return $query->where('url_name', $url_name)->firstOrFail();
    }

    /**
     * Define eloquent relationship to conversation model
     * A user can have many conversations.
     */
    public function conversations() {
        return $this->belongsToMany('App\Conversation');
    }

    /**
     * Define eloquent relationship to message
     * A user can send many messages
     */
    public function sentMessages() {
        return $this->hasMany('App\Message');
    }

    /**
     * Determine whether the user is in the conversation
     *
     * @param \App\Conversation $conversation
     * @return bool
     */
    public function isInConversation(Conversation $conversation) {
        return in_array($this->id, $conversation->users->lists('id')->all());
    }

    /**
     * Defined eloquent relationship : A student could have many education experience
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function educationExps() {
        return $this->belongsToMany('App\EducationExp', 'user_educationExp', 'user_id', 'educationExp_id');
    }

    /**
     * A user can post many question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions() {
        return $this->hasMany('App\Question');
    }

    /**
     * A user can answer some question
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers() {
        return $this->hasMany('App\Answer');
    }

    /**
     * A user can write many replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies() {
        return $this->hasMany('App\Reply', 'user_id');
    }

    /**
     * A user can vote up some answers
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function vote_up_answers() {
        return $this->belongsToMany('App\Answer', 'user_vote_up_answer');
    }

    /**
     * A user can vote down some answers
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function vote_down_answers() {
        return $this->belongsToMany('App\Answer', 'user_vote_down_answer');
    }

    /**
     * A user can vote up for some replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vote_up_replies() {
        return $this->belongsToMany('App\Reply', 'user_vote_up_reply');
    }

    /**
     * A user has subscribe plan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function subscribe() {
        return $this->hasOne('App\Subscribe');
    }

    /**
     * A user created many bookmarks
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookmarks() {
        return $this->hasMany('App\Bookmark');
    }


    /**
     * A user is subscribed by many users' subscribe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers() {
        return $this->belongsToMany('App\Subscribe');
    }

    /**
     * A user could have many notifications
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications() {
        return $this->hasMany('App\Notification');
    }

    /**
     * define query scope. similar match $name
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeSimilarMatch($query, $name, $range = null) {
        $result = $query->where('name', 'REGEXP', '[' . $name . ']');
        if ($range != null) {
          $result = $result->where('created_at', '>', Carbon::now()->subDays($range)->toDateTimeString());
        }
        return $result;
    }

    /**
     * define query scope. like match $name
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeNoneSimilarMatch($query, $name, $range = null) {
        $result = $query->where('name', 'LIKE', '%' . $name . '%');
        if ($range != null) {
            $result = $result->where('created_at', '>', Carbon::now()->subDays($range)->toDateTimeString());
        }
        return $result;
    }

    /**
     * Get the answers that belong to the topic
     *
     * @param $topic_id
     */
    public function answersInTopic($topic_id) {
        return $this->answers->filter(function($answer) use ($topic_id) {
            return in_array($topic_id, $answer->question->topics->lists('id')->all());
        });
    }


    /**
     * Defined eloquent relationship : A student could have many jobs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs() {
        return $this->belongsToMany('App\Job', 'user_job', 'user_id', 'job_id');
    }    

    /**
     * Defined eloquent relationship : A student could have many specializationss
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specializations() {
        return $this->belongsToMany('App\Topic', 'user_specialization', 'user_id', 'topic_id');
    }    

    /** Define eloquent relationship between User and Settings
    *   A user has one setting
    */
    public function settings() {
        return $this->hasOne('App\Settings');
    }

    /**
     *  Define eloquent relationship between User and Blocking
    *   A user may block many other users
    */
    public function blockings() {
        return $this->belongsToMany('App\User', 'blockings', 'user_id', 'blocked_id');
    }

    /**
     * A user could be blocked by many other users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function be_blocked() {
        return $this->belongsToMany('App\User', 'blockings', 'blocked_id', 'user_id');
    }

}
