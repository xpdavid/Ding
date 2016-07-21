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


    /**
     * Show recommendations page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
        $questions = Question::recommendQuestions($page, $itemInPage);

        // filter base on user setting
        $questions = $user ? $user->filterQuestions($questions)
         : $questions;


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

            if ($answer != null) {
                array_push($results, $answer->jsonAnswerSummary());
            } else {
                $arr = [
                    'id' => $question->id,
                    'topics' => $topics,
                    'topic_pic' => DImage($question->topics->first()->avatar_img_id, 40, 40),
                    'answer' => false,
                    'title' => $question->title,
                ];
                if (!Auth::guest()) {
                    $arr['subscribed'] = $user->subscribe->checkHasSubscribed($question->id, 'question');
                } else {
                    $arr['guest'] = true;
                }
                array_push($results, $arr);
            }

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
                $questions = Question::weekQuestions($page, $itemInPage);
                break;
            default :
                $questions = Question::monthQuestions($page, $itemInPage);
                break;
        }

        // filter base on user setting
        $questions = $user ? $user->filterQuestions($questions)
            : $questions;

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

            if ($answer != null) {
                array_push($results, $answer->jsonAnswerSummary());
            } else {
                $arr = [
                    'id' => $question->id,
                    'topics' => $topics,
                    'topic_pic' => DImage($question->topics->first()->avatar_img_id, 40, 40),
                    'answer' => false,
                    'title' => $question->title,
                ];
                if (!Auth::guest()) {
                    $arr['subscribed'] = $user->subscribe->checkHasSubscribed($question->id, 'question');
                } else {
                    $arr['guest'] = true;
                }
                array_push($results, $arr);
            }
        }

        return $results;
    }
}
