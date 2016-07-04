<?php

namespace App\Http\Controllers;

use Auth;
use App\Question;
use App\Http\Requests;
use Illuminate\Http\Request;

class HighlightController extends Controller
{
    protected $itemInPage = 5;

    /**
     * HighlightController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display highlight page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        return view('highlight.index');
    }
    
    public function getRecommendations() {
        return view('highlight.recommendations');
    }

    /**
     * Response ajax request to show recommend question
     *
     * @param Request $request
     * @return array(json)
     */
    public function postRecommend(Request $request) {
        $user = Auth::user();
        $page = $request->get('page') ? $request->get('page') : 1;
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $results = [];
        // get question
        $questions = Question::recommendQuestions();

        // page system
        $questions = $questions->forPage($page, $itemInPage);

        foreach ($questions as $question) {
            $answer = $question->hotAnswer;
            // generate topics
            $topics = [];
            foreach ($question->topics as $topic) {
                array_push($topics, [
                    'name' => $topic->name,
                    'id' => $topic->id,
                ]);
            }

            $answer_arr = false;
            if ($answer != null) {
                // check if the user has voted the answer
                $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
                $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
                $answer_arr = [
                    'id' => $answer->id,
                    'owner' => [
                        'name' => $answer->owner->name,
                        'bio' => $answer->owner->bio,
                        'url_name' => $answer->owner->url_name,
                    ],
                    'answer' => $answer->answer,
                    'netVotes' => $answer->netVotes,
                    'numComment' => $answer->replies()->count(),
                    'vote_up_class' => $vote_up_class,
                    'vote_down_class' => $vote_down_class
                ];
            }

            array_push($results, [
                'id' => $question->id,
                'topics' => $topics,
                'topic_pic' => DImage($question->topics->first()->avatar_img_id, 40, 40),
                'answer' => $answer_arr,
                'title' => $question->title,
                'subscribed' => $user->subscribe->checkHasSubscribed($question->id, 'question')
            ]);
        }

        return $results;
    }

    /**
     * response ajax request to show day/week hot question
     *
     * @param Request $request
     * @return array(json)
     */
    public function postHot(Request $request) {
        $user = Auth::user();
        $page = $request->get('page') ? $request->get('page') : 1;
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        // calculate how many question each topic should take
        $results = [];
        // determine week or month
        switch ($request->get('hot')) {
            case 'week':
                $questions = Question::weekQuestions();
                break;
            default :
                $questions = Question::monthQuestions();
                break;
        }
        // page system
        $questions = $questions->forPage($page, $itemInPage);
        foreach ($questions as $question) {
            $answer = $question->hotAnswer;
            // generate topics
            $topics = [];
            foreach ($question->topics as $topic) {
                array_push($topics, [
                    'name' => $topic->name,
                    'id' => $topic->id
                ]);
            }
            $answer_arr = false;
            if ($answer != null) {
                // check if the user has voted the answer
                $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
                $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
                $answer_arr = [
                    'id' => $answer->id,
                    'owner' => [
                        'name' => $answer->owner->name,
                        'bio' => $answer->owner->bio,
                        'url_name' => $answer->owner->url_name,
                    ],
                    'answer' => $answer->answer,
                    'netVotes' => $answer->netVotes,
                    'numComment' => $answer->replies()->count(),
                    'vote_up_class' => $vote_up_class,
                    'vote_down_class' => $vote_down_class
                ];
            }
            array_push($results, [
                'id' => $question->id,
                'topics' => $topics,
                'topic_pic' => DImage($question->topics->first()->avatar_img_id, 40, 40),
                'answer' => $answer_arr,
                'title' => $question->title,
                'subscribed' => $user->subscribe->checkHasSubscribed($question->id, 'question')
            ]);
        }

        return $results;
    }
}
