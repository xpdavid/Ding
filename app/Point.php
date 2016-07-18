<?php

namespace App;

use Carbon\Carbon;
use Auth;
use View;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{

    protected $fillable = [
        'type',
        'point',
        'param1',
        'param2',
        'param3'
    ];

    /**
     * create a new point.
     * for some type of point, return the existing one
     *
     * @param $user
     * @param array $attributes
     * @return bool|static
     */
    public static function add($user, array $attributes = []) {
        $query = $user->points()->whereType($attributes['type']);
        $created_point = false;

        if (in_array($attributes['type'], [10, 11, 12, 13, 14, 15, 16, 17])) {
            // can be created many times
            $point = static::create($attributes);
            $user->points()->save($point);

            $created_point = $point;
        } else {
            // cannot gain many times
            if(isset($attributes['param1'])) {
                $query = $query->whereParam1($attributes['param1']);
            }
            if(isset($attributes['param2'])) {
                $query = $query->whereParam2($attributes['param2']);
            }
            if(isset($attributes['param3'])) {
                $query = $query->whereParam3($attributes['param3']);
            }
            if (!$query->exists()) {
                $point = static::create($attributes);
                $user->points()->save($point);

                $created_point = $point;
            } else {
                // update the time
                $point = $query->first();
                $point->updated_at = Carbon::now();
                $point->save();
            }
        }

        // add flash message
        if ($created_point && Auth::user()->id == $user->id) {
            // send flash message
            session()->flash('add_point', $created_point->point);
        }

        // update user group
        $user->adjustAuthGroup();

        return $created_point;
    }


    /**
     * Add point to user
     *
     * @param $user
     * @param $type
     * @param $additional
     * @return Point|bool
     */
    public static function add_point($user, $type, $additional) {
        switch ($type) {
            case 1:
                $question = Question::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => -$question->reward,
                    'param1' => $question->id
                ]);
                return $created_point;
                break;
            case 2:
                $answer = Answer::findOrFail($additional[0]);
                $numAnswers = $answer->question->answers()->count();
                $reward = $answer->question->reward;
                $numSubscriber = $answer->question->subscribers()->count();
                $point = 5 +
                    $reward / 3
                    - min(($numAnswers - 1) * ($numAnswers - 2) * ($numAnswers - 3), 1)
                    * $reward / 3 + $numSubscriber / 5;
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => $point,
                    'param1' => $answer->id
                ]);
                return $created_point;
                break;
            case 3:
                $answer = Answer::findOrFail($additional[0]);
                if($user->points()->today()->whereType(3)->count() < 5) {
                    $created_point = Point::add($user, [
                        'type' => $type,
                        'point' => 1,
                        'param1' => $answer->id,
                    ]);
                    return $created_point;
                }
                return false;
                break;
            case 4:
                $answer = Answer::findOrFail($additional[0]);
                $by_user = User::findOrFail($additional[1]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => max($answer->netVotes, 1)  * max($answer->question->reward, 5) / 10,
                    'param1' => $answer->id,
                    'param2' => $by_user->id
                ]);
                return $created_point;
                break;
            case 5:
                $answer = Answer::findOrFail($additional[0]);
                $by_user = User::findOrFail($additional[1]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => 0.5,
                    'param1' => $answer->id,
                    'param2' => $by_user->id
                ]);
                return $created_point;
                break;
            case 6:
                $question = Question::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => 2,
                    'param1' => $question->id,
                ]);
                return $created_point;
                break;
            case 8:
                $topic = Topic::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => 2,
                    'param1' => $topic->id,
                ]);
                return $created_point;
                break;
            case 9:
                $topic = Topic::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => 5,
                    'param1' => $topic->id,
                ]);
                return $created_point;
                break;
            case 10:
                $history = History::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => - ($history->forItem->reward / 3 + 5),
                    'param1' => $history->id
                ]);
                return $created_point;
                break;
            case 12:
                $history = History::findOrFail($additional[0]);
                $by_user = User::findOrFail($additional[1]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => -($history->forItem->subscribers()->count() / 30 + 5),
                    'param1' => $history->id,
                    'param2' => $by_user->id,
                ]);
                return $created_point;
                break;
            case 13:
                $question = Question::findOrFail($additional[0]);
                $by_user = User::findOrFail($additional[1]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => -10,
                    'param1' => $question->id,
                    'param2' => $by_user->id,
                ]);
                return $created_point;
                break;
            case 14:
                $answer = Answer::findOrFail($additional[0]);
                $by_user = User::findOrFail($additional[1]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => -10,
                    'param1' => $answer->id,
                    'param2' => $by_user->id,
                ]);
                return $created_point;
                break;
            case 15:
                $topic = Topic::findOrFail($additional[0]);
                $by_user = User::findOrFail($additional[1]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => -20,
                    'param1' => $topic->id
                ]);
                return $created_point;
                break;
            case 16:
                $by_user = User::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => -(abs($user->point) * 2 / 3),
                    'param1' => $by_user->id,
                ]);
                return $created_point;
                break;
            case 17:
                $by_user = User::findOrFail($additional[0]);
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => 8,
                    'param1' => $by_user->id,
                ]);
                return $created_point;
                break;
            case 18:
                $created_point = Point::add($user, [
                    'type' => $type,
                    'point' => 10,
                ]);
                return $created_point;
                break;

        }
    }

    /**
     * A point is belongs to an user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner() {
        return $this->belongsTo('App\User');
    }

    /**
     * Define query scope to get today points
     *
     * @param $query
     * @return mixed
     */
    public function scopeToday($query) {
        return $query->whereDate('created_at', '=', date('Y-m-d'));
    }

    /**
     * Get render content
     */
    public function getRenderedContentAttribute() {
        $view = View::make('points.type_' . $this->type,
            [
                'point' => $this,
            ]);
        $content = $view->render();

        return $content;
    }
}
