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
        $questions = Question::all();

        // page syste,
        $questions = $questions->forPage($page, $itemInPage);

        foreach ($questions as $question) {
            // take 10 recent answer
            $answer = $question->answers()->orderBy('created_at', 'desc')->take(5)->get();
            // use the answer with the highest vote
            $answer = $answer->sortByDesc('netVotes')->first();
            // generate topics
            $topics = [];
            foreach ($question->topics as $topic) {
                array_push($topics, [
                    'name' => $topic->name,
                    'id' => $topic->id
                ]);
            }
            // check if the user has voted the answer
            $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
            $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
            array_push($results, [
                'id' => $question->id,
                'topics' => $topics,
                'answer' => [
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
                ],
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
                $questions = Question::skip(5)->take(5)->get();
                break;
            default :
                $questions = Question::skip(20)->take(10)->get();
                break;
        }
        // page system
        $questions = $questions->forPage($page, $itemInPage);
        foreach ($questions as $question) {
            // take 10 recent answer
            $answer = $question->answers()->orderBy('created_at', 'desc')->take(5)->get();
            // use the answer with the highest vote
            $answer = $answer->sortByDesc('netVotes')->first();
            // generate topics
            $topics = [];
            foreach ($question->topics as $topic) {
                array_push($topics, [
                    'name' => $topic->name,
                    'id' => $topic->id
                ]);
            }
            // check if the user has voted the answer
            $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
            $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
            array_push($results, [
                'id' => $question->id,
                'topics' => $topics,
                'answer' => [
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
                ],
                'title' => $question->title,
                'subscribed' => $user->subscribe->checkHasSubscribed($question->id, 'question')
            ]);
        }

        return $results;
    }
}
