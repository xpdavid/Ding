<?php

namespace App;

use DB;
use File;
use Auth;
use App\LoginMethod;
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
        // assign user with v1 group
        $user->authGroup_id = 1;
        // generate url name
        $user->generateUrlName();

        // set default image
        // check if user folder exist
        if (!File::exists(base_path('images/user/' . $user->id))) {
            File::makeDirectory(base_path('images/user/' . $user->id), $mode = 0777, true, true);
        }
        $filename = 'profile-' . $user->id . '.png';
        $file = 'images/user/' . $user->id .'/' . $filename;

        File::copy(base_path('public/static/images/default_user.png'),
            base_path('images/user/' . $user->id .'/' . $filename));

        // create new image instance
        $img_database = Image::create([
            'path' => $file,
            'width' => 600,
            'height' => 600
        ]);
        $img_database->save();

        // update reference id
        $img_database->reference_id = $img_database->id;
        $img_database->save();

        // update new user pic id
        $settings = $user->settings;
        $settings->profile_pic_id = $img_database->id;
        $settings->save();


        // user profile hit
        Hit::create([
            'owner_type' => 'App\User',
            'owner_id' => $user->id
        ]);
        return $user;
    }

    /**
     * Auto-generate url-name for user.
     *
     * @return string
     */
    public function generateUrlName() {
        $url_name = $this->name;

        // replace all the non-alphanumeric characters
        $url_name = preg_replace("/[^A-Za-z0-9 ]/", '', $url_name);
        // replace the space with underscore
        $url_name = str_replace(' ', '_', $url_name);

        // in order to prevent using same name, add user id at last.
        $this->url_name = $url_name . $this->id;

        $this->save();
    }

    /**
     * Send notification to subscribers
     *
     * @param $type
     * @param $item
     */
    public function notifySubscriber($type, $item) {
        // notify all user subscribers
        foreach ($this->subscribers as $subscriber) {
            $owner = $subscriber->owner;
            // it is ok to notify twice as the sanme notification will mark as updated
            // rather than create a new one
            Notification::notification($owner, $type, $this->id, $item->id);
        }
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
        return $this->belongsToMany('App\Conversation')->withTimestamps();
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
        return $this->belongsToMany('App\EducationExp', 'user_educationExp', 'user_id', 'educationExp_id')
            ->withTimestamps();
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
        return $this->belongsToMany('App\Answer', 'user_vote_up_answer')->withTimestamps();
    }

    /**
     * A user can vote down some answers
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function vote_down_answers() {
        return $this->belongsToMany('App\Answer', 'user_vote_down_answer')->withTimestamps();
    }

    /**
     * A user can vote up for some replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vote_up_replies() {
        return $this->belongsToMany('App\Reply', 'user_vote_up_reply')->withTimestamps();
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
        return $this->belongsToMany('App\Subscribe')->withTimestamps();
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
     * A user has many login methods
     */
    public function loginMethods() {
        return $this->hasMany('App\LoginMethod');
    }

    /**
     * Get the specific methods
     *
     * @param $type
     * @return $string / bool
     */
    public function loginMethod($type) {
        if ($this->loginMethods()->whereType($type)->exists()) {
            $method = $this->loginMethods()->whereType($type)->first();
            return $method;
        } else {
            return false;
        }
    }

    /**
     * An user has many point
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function points() {
        return $this->hasMany('App\Point');
    }

    /**
     * Count the overall point of the user
     */
    public function getPointAttribute() {
        $total = 0;
        foreach ($this->points as $point) {
            $total += $point->point;
        }
        return $total;
    }

    /**
     * Determine whether the user is baned
     */
    public function isBaned() {
        return $this->authGroup_id == 8;
    }

    /**
     * A user is belongs to a authgroup
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function authGroup() {
        return $this->belongsTo('App\AuthGroup', 'authGroup_id');
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
     * Defined eloquent relationship : A student could have many jobs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function jobs() {
        return $this->belongsToMany('App\Job', 'user_job', 'user_id', 'job_id')->withTimestamps();
    }


    /**
     * Defined eloquent relationship : A student could have many specializationss
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function specializations() {
        return $this->belongsToMany('App\Topic', 'user_specialization', 'user_id', 'topic_id')->withTimestamps();
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
        return $this->belongsToMany('App\User', 'blockings', 'user_id', 'blocked_id')->withTimestamps();
    }

    /**
     * A user could be blocked by many other users
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function be_blocked() {
        return $this->belongsToMany('App\User', 'blockings', 'blocked_id', 'user_id')->withTimestamps();
    }

    /**
     * An user can hide some topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hide_topics() {
        return $this->belongsToMany('App\Topic', 'hide_topic', 'user_id', 'topic_id')->withTimestamps();
    }

    /**
     * Generate user update in $date.
     * if there is not update in $date, get the early one.
     *
     * @param $date (Carbon)
     * @return array(collection)
     */
    public function generateUpdate($date) {
        $results = [];
        $date = $date->copy(); // do not affect other variable scope, pass by reference
        $end = Carbon::parse($this->created_at);
        while($end->lt($date)) {
            // question type 1
            $query = $this->questions()->whereStatus(1)->whereDate('created_at', '=', $date->toDateString());
            if ($query->count() > 0) {
                foreach ($query->get() as $question) {
                    array_push($results, [
                        'created_at' => $question->created_at,
                        'id' => $question->id,
                        'type' => 1
                    ]);
                }
            }
            // question subscribe type 2
            $query = DB::table('subscribe_question')->where('subscribe_id', $this->subscribe->id)
                ->whereDate('created_at', '=', $date->toDateString());
            if ($query->count() > 0) {
                foreach ($query->get() as $item) {
                    array_push($results, [
                        'created_at' => $item->created_at,
                        'id' => $item->question_id,
                        'type' => 2
                    ]);
                }
            }
            // answer type 3
            $query = $this->answers()->whereStatus(1)->whereDate('created_at', '=', $date->toDateString());
            if ($query->count() > 0) {
                foreach ($query->get() as $answer) {
                    array_push($results, [
                        'created_at' => $answer->created_at,
                        'id' => $answer->id,
                        'type' => 3
                    ]);
                }
            }
            // answer vote up type 4
            $query = DB::table('user_vote_up_answer')->where('user_id', $this->id)
                ->whereDate('created_at', '=', $date->toDateString());
            if ($query->count() > 0) {
                foreach ($query->get() as $item) {
                    array_push($results, [
                        'created_at' => $item->created_at,
                        'id' => $item->answer_id,
                        'type' => 4
                    ]);
                }
            }
            // topic subscribe type 5
            $query = DB::table('subscribe_topic')->where('subscribe_id', $this->subscribe->id)
                ->whereDate('created_at', '=', $date->toDateString());
            if ($query->count() > 0) {
                foreach ($query->get() as $item) {
                    array_push($results, [
                        'created_at' => $item->created_at,
                        'id' => $item->topic_id,
                        'type' => 5
                    ]);
                }
            }

            // break when have data
            if (count($results) > 0) {
                return [
                    'end' => $date,
                    'data' => collect($results)->sortByDesc(function($item) {
                        return Carbon::parse($item['created_at'])->timestamp;
                    })
                ];
            }

            // sub one day
            $date = $date->subDay();
        }

        return [
            'end' => $date,
            'data' => collect([])
        ];


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
     * Count total votes in topic
     *
     * @param $topic_id
     * @return int
     */
    public function votesInTopic($topic_id) {
        return Answer::countVotes($this->answersInTopic($topic_id));
    }


    /**
     * Get total votes of user's answers
     *
     * @return int
     */
    public function getTotalVotesAttribute() {
        return Answer::countVotes($this->answers);
    }

    /**
     * Get total likes of user's replies
     *
     */
    public function getTotalLikesAttribute() {
        return Reply::countLikes($this->replies);
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
            // being blocked
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
        if (in_array($user->id, $this->blockings->lists('id')->all())) {
            // being blocked
            return false;
        } else if ($this->settings->receiving_invitations == 1) {
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
        if (in_array($user->id, $this->blockings->lists('id')->all())) {
            // being blocked
            return false;
        } else if ($this->settings->receiving_replies == 0) {
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
        if (in_array($user->id, $this->blockings->lists('id')->all())) {
            // being blocked
            return false;
        } else if ($this->settings->receiving_reply_votings == 0) {
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
        if (in_array($user->id, $this->blockings->lists('id')->all())) {
            // being blocked
            return false;
        } else if ($this->settings->receiving_subscriptions == 0) {
            return false;
        } else if ($this->settings->receiving_subscriptions == 1) {
            if (!in_array($user->id, $this->subscribe->users->lists('id')->all())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check whether user is blocked by $user
     *
     * @param $user
     * @return bool
     */
    public function isBlockedBy($user) {
        return in_array($this->id, $user->blockings->lists('id')->all());
    }


    /**
     * Check the user whether have the authority to do operation
     *
     * @param $id
     * @return bool
     */
    public function operation($id) {
        return in_array($id,
            $this->authGroup->authorities->lists('id')->all());
    }

    /**
     * Adjust user auth group according to the point
     */
    public function adjustAuthGroup() {
        if ($this->authGroup_id <= 5) {
            $point = $this->point;
            $v1 = AuthGroup::find(1);
            $v2 = AuthGroup::find(2);
            $v3 = AuthGroup::find(3);
            $v4 = AuthGroup::find(4);
            $v5 = AuthGroup::find(5);

            if ($point >= $v1->point) {
                $this->authGroup_id = 1;
            }

            if ($point >= $v2->point) {
                $this->authGroup_id = 2;
            }

            if ($point >= $v3->point) {
                $this->authGroup_id = 3;
            }

            if ($point >= $v4->point) {
                $this->authGroup_id = 4;
            }

            if ($point >= $v5->point) {
                $this->authGroup_id = 5;
            }

            $this->save();
        }
    }

    /**
     * Set default password for user
     */
    public function setDefaultPassword() {
        $this->password = bcrypt(md5($this->id . env('APP_KEY')));
        $this->save();
    }

}
