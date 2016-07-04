<?php

namespace App;

use Auth;
use App\Subscribe;
use Carbon\Carbon;
use App\Settings;
use App\Conversation;
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
     * Override create method for creating necessary associate model
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes = []) {
        $user = parent::create($attributes); // TODO: Change the autogenerated stub
        // use have subscribe model one to one
        $subscribe = Subscribe::create();
        $user->subscribe()->save($subscribe);
        // use have setting model one to one
        $settings = Settings::create();
        $user->settings()->save($settings);
        // user profile hit
        Hit::create([
            'owner_type' => 'App\User',
            'owner_id' => $user->id
        ]);
        return $user;
    }

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
     * A user profile page has a hit
     */
    public function getHitAttribute() {
        return Hit::whereOwnerType('App\User')->whereOwnerId($this->id)->first();
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

    /**
     * An user can hide some topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hide_topics() {
        return $this->belongsToMany('App\Topic', 'hide_topic', 'user_id', 'topic_id');
    }

    /**
     * Filter questions collection base on interest
     *
     * @param $questions
     * @return mixed
     */
    public function filterQuestions($questions) {
        // filter base on user setting
        $filter = $questions;
        if ($this->hide_topics()->count() > 0) {
            $filter = $questions->filter(function($question) {
                $result = true;
                foreach ($question->topics as $topic) {
                    foreach ($this->hide_topics as $hide_topic) {
                        $result = $result && !$topic->isSubtopicOf($hide_topic);
                        if (!$result) {
                            break;
                        }
                    }
                    if (!$result) {
                        break;
                    }
                }
                return $result;
            });
        }

        return $filter;

    }

    /**
     * Filter certain topics base on user setting
     *
     * @param $topics
     * @return mixed
     */
    public function filterTopics($topics) {
        // filter base on user setting
        $filter = $topics;
        if ($this->hide_topics()->count() > 0) {
            $filter = $topics->filter(function($topic) {
                $result = true;
                foreach ($this->hide_topics as $hide_topic) {
                    $result = $result && !$topic->isSubtopicOf($hide_topic);
                    if (!$result) {
                        break;
                    }
                }
                return $result;
            });
        }
        return $filter;
    }


    /**
     * Check if user can send message to user
     *
     * @param $user
     * @return bool
     */
    public function canSendMessageTo($user) {
        if (in_array($this->id, $user->blockings->lists('id')->all())) {
            return false;
        } else if ($this->settings->receiving_messages == 1) {
            // Only people I subscribe to can messages me
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }

        return true;
    }

    /**
     * filter invitation base on user setting
     *
     * @param $users
     * @return bool
     */
    public function canBeInvitedBy($user) {
        if ($user->id == $this->id) return true;

        if ($this->settings->receiving_invitations == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Filter question update base on user setting
     *
     * @param $user
     * @return bool
     */
    public function canReceiveQuestionUpdateBy($user) {
        if ($user->id == $this->id) return true;

        if ($this->settings->receiving_updates == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

    /**
     * filter `can replied` base on user setting
     *
     * @param $user
     * @return bool
     */
    public function canReplyBy($user) {
        if ($user->id == $this->id) return true;

        if ($this->settings->receiving_replies == 0) {
            return false;
        } else if ($this->settings->receiving_replies == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

    /**
     * determine an answer can be voted by a user
     *
     * @param $user
     * @return bool
     */
    public function canAnswerVoteBy($user) {
        if ($user->id == $this->id) return true;

        if ($this->settings->receiving_votings == 0) {
            return false;
        } else if ($this->settings->receiving_votings == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

    /**
     * determine if user's replies can be voted
     *
     * @param $user
     * @return bool
     */
    public function canReplyVoteBy($user) {
        if ($user->id == $this->id) return true;

        if ($this->settings->receiving_reply_votings == 0) {
            return false;
        } else if ($this->settings->receiving_reply_votings == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

    /**
     * determine if user can subscribe to a user
     *
     * @param $user
     * @return bool
     */
    public function canSubscribedBy($user) {
        if ($this->settings->receiving_subscriptions == 0) {
            return false;
        } else if ($this->settings->receiving_subscriptions == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

}
