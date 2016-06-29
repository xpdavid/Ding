<?php

namespace App\Http\Controllers;

use App\Bookmark;
use Auth;
use App\User;
use App\Topic;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class SubscribeController extends Controller
{
    /**
     * SubscribeController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Response ajax request to subscribe/unsubscribe a question
     *
     * @param $question_id
     * @param $request
     * @return array(json)
     */
    public function postQuestion($question_id, Request $request) {
        $user = Auth::user();
        $question = Question::findOrFail($question_id);

        if ($request->exists('op') && $request->get('op') == 'unsubscribe') {
            // unsubscribe a question
            $user->subscribe->questions()->detach($question_id);
        } else {

            // you cannot subscribe a question twice
            if (!$user->subscribe->checkHasSubscribed($question_id, 'question')) {
                $user->subscribe->questions()->save($question);
            }
        }

        return [
            'status' => true,
            'numSubscriber' => $question->subscribers()->count()
        ];

    }

    /**
     * Response ajax request to subscribe/unsubscribe a topic
     *
     * @param $topic_id
     * @param $request
     * @return array(json)
     */
    public function postTopic($topic_id, Request $request) {
        $user = Auth::user();
        if ($request->exists('op') && $request->get('op') == 'unsubscribe') {
            // unsubscribe a topic
            $user->subscribe->topics()->detach($topic_id);
        } else {
            $topic = Topic::findOrFail($topic_id);
            // check duplicate subscribe
            if (!$user->subscribe->checkHasSubscribed($topic_id, 'topic')) {
                $user->subscribe->topics()->save($topic);
            }
        }

        return [
            'status' => true,
            'numSubscriber' => $topic->subscribers()->count()
        ];

    }

    /**
     * Response ajax request to subscribe/unsubscribe a user
     *
     * @param $user_id
     * @param $request
     * @return array(json)
     */
    public function postUser($user_id, Request $request) {
        $user = Auth::user();
        if ($request->exists('op') && $request->get('op') == 'unsubscribe') {
            // unsubscribe a user
            $user->subscribe->users()->detach($user_id);
        } else {
            // subscribe a user
            if (!$user_id == $user->id) {
                $follow_user = User::findOrFail($user_id);
                // check duplicate subscribe
                if (!$user->subscribe->checkHasSubscribed($user_id, 'user')) {
                    $user->subscribe->users()->save($follow_user);
                }
            }
        }

        return [
            'status' => true
        ];
    }

    /**
     * Subscribe a bookmark
     *
     * @param $bookmark_id
     * @param Request $request
     * @return array
     */
    public function postBookmark($bookmark_id, Request $request) {
        $user = Auth::user();
        $bookmark = Bookmark::findOrFail($bookmark_id);
        if (!$bookmark->is_public || $bookmark->owner->id == $user->id) {
            // you cannot subscribe a unpublic bookmark
            // you cannot subscribe yourself bookmark
            return  [
                'status' => false
            ];
        }
        if ($request->exists('op') && $request->get('op') == 'unsubscribe') {
            // unsubscribe a user
            $user->subscribe->bookmarks()->detach($bookmark_id);
        } else {
            // subscribe a bookmark
            // check duplicate subscribe
            if (!$user->subscribe->checkHasSubscribed($bookmark_id, 'bookmark')) {
                $user->subscribe->bookmarks()->save($bookmark);
            }
        }

        return [
            'status' => true,
            'numSubscriber' => $bookmark->subscribers()->count()
        ];
    }
}
