<?php

namespace App;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * Define fillable field in the model
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'content'
    ];


    /**
     * Override create method for creating necessary associate model
     *
     * @param array $attributes
     * @return static
     */
    public static function create(array $attributes = []) {
        $question = parent::create($attributes); // TODO: Change the autogenerated stub
        // bookmark hit
        Hit::create([
            'owner_type' => 'App\Question',
            'owner_id' => $question->id
        ]);
        return $question;
    }

    /**
     * A question belongs to a user (is posted by a user)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User');
    }

    /**
     * A question has many answers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers() {
        return $this->hasMany('App\Answer');
    }

    /**
     * A question may have replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function replies() {
        return $this->morphMany('App\Reply', 'for_item');
    }

    /**
     * A question belongs to many topics
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function topics() {
        return $this->belongsToMany('App\Topic')->withTimestamps();
    }

    /**
     * A question is subscribed by many users' subscribe
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function subscribers() {
        return $this->belongsToMany('App\Subscribe', 'subscribe_question')->withTimestamps();
    }

    /**
     * A question may belongs to many bookmarks
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookmarks() {
        return $this->belongsToMany('App\Bookmark')->withTimestamps();
    }

    /**
     * Get the excerpt of the question detail
     *
     * @return string
     */
    public function getExcerptAttribute() {
        return truncateText($this->content, 200);
    }

    /**
     * Get also interest questions
     */
    public function getAlsoInterestQuestionsAttribute() {
        $also_interest = collect();
        foreach ($this->topics()->orderBy(DB::raw('RAND()'))->get() as $topic) {
            $also_interest = $also_interest->merge($topic->waitAnswerQuestions->take(3));
            $also_interest = $also_interest->merge($topic->highlightQuestions->take(3));
        }
        return $also_interest;
    }

    /**
     * Get news question
     *
     * @return mixed
     */
    public static function news() {
        $questions = Question::all();
        // order by some magic algorithm
        $questions = $questions->sortByDesc(function($question) {
            $timeDiff = Carbon::parse($question->created_at)->diffInDays(Carbon::now());
            $timeDiff = (30 - $timeDiff) > 0 ? 30 - $timeDiff : 0;
            $numSubscriber = $question->subscribers()->count();
            $numHit = $question->hit->day;
            $numVote = $question->highestVote;
            return $numHit * 3 + $numVote + $numSubscriber + $timeDiff * 5;
        });

        return $questions;
    }

    /**
     * Get recommend questions
     *
     * @return static
     */
    public static function recommendQuestions() {
        return Question::all()->sortByDesc(function($question) {
            $numVote = $question->highestVote;
            $numSubscriber = $question->subscribers()->count();
            $numHit = $question->hit->month;

            return $numVote * 2 + $numSubscriber * 3 + $numHit * 2;
        });
    }


    /**
     * get week hot questions
     *
     * @return static
     */
    public static function weekQuestions() {
        return Question::all()->sortByDesc(function($question) {
            $timeDiff = Carbon::parse($question->created_at)->diffInDays(Carbon::now());
            $timeDiff = (30 - $timeDiff) > 0 ? 30 - $timeDiff : 0;
            $numVote = $question->highestVote;
            $numSubscriber = $question->subscribers()->count();
            $numHit_week = $question->hit->week;

            return $numVote + $numSubscriber * 2 + $numHit_week * 5 + $timeDiff * 2;
        });
    }

    /**
     * Get current month hot questions
     *
     * @return static
     */
    public static function monthQuestions() {
        return Question::all()->sortByDesc(function($question) {
            $timeDiff = Carbon::parse($question->created_at)->diffInDays(Carbon::now());
            $timeDiff = (30 - $timeDiff) > 0 ? 30 - $timeDiff : 0;
            $numVote = $question->highestVote;
            $numSubscriber = $question->subscribers()->count();
            $numHit_month = $question->hit->month;

            return $numVote + $numSubscriber * 2 + $numHit_month * 5 + $timeDiff * 2;
        });
    }

    /**
     * Get one hot answer from the questions
     */
    public function getHotAnswerAttribute() {
        if ($this->answers()->count() > 0) {
            // use the answer with the highest vote
            $answer = $this->answers->sortByDesc(function($answer) {
                $timeDiff = Carbon::parse($answer->created_at)->diffInDays(Carbon::now());
                $timeDiff = (30 - $timeDiff) > 0 ? 30 - $timeDiff : 0;
                $numVote = $answer->netVotes;
                $numHit_month = $answer->hit->month;
                return $numVote + $numHit_month * 2 + $timeDiff * 2;
            })->first();
            return $answer;
        }
        return null;
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
     * Get the highest number of votes in answers of the current question
     *
     * @return int
     */
    public function getHighestVoteAttribute() {
        if($this->answers->count() > 0) {
            return $this->answers->max('netVotes');
        } else {
            return 0;
        }
    }

    /**
     * A question has a hit
     */
    public function getHitAttribute() {
        return Hit::whereOwnerType('App\Question')->whereOwnerId($this->id)->first();
    }

    /**
     * define query scope. similar match $name
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeSimilarMatch($query, $title) {
        return $query->where('title', 'REGEXP', '[' . $title . ']');
    }

    /**
     * define query scope. `like` match $name
     *
     * @param $query
     * @param $name
     * @return mixed
     */
    public function scopeNoneSimilarMatch($query, $title) {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }


    /**
     * search for both title and content
     *
     * @param $query
     * @param $key
     * @param $range (optional)
     * @return mixed
     */
    public function scopeNoneSimilarMatchAll($query, $key, $range = null) {
        if ($range != null) {
            return $query->where(function($query) use ($key) {
                return $query->orWhere('title', 'LIKE' , '%' . $key . '%')
                    ->orWhere('content', 'LIKE' , '%' . $key . '%');
            })->where('created_at', '>', Carbon::now()->subDays($range)->toDateTimeString());

        } else {
            return $query->orWhere('title', 'LIKE' , '%' . $key . '%')
                ->orWhere('content', 'LIKE' , '%' . $key . '%');
        }

    }
}
