<?php

namespace App\Http\Controllers;

use App\History;
use App\Question;
use App\Topic;
use App\User;
use App\Answer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;

use App\Http\Requests;

class HistoryController extends Controller
{
    protected $itemInPage = 6;


    /**
     * Show topic edit history
     *
     * @param $topic_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getTopicLog($topic_id) {
        $topic = Topic::findOrFail($topic_id);
        $parent_topics = $topic->parent_topics;
        $subtopics = $topic->subtopics;
        return view('topic.topic_log', compact('topic', 'parent_topics', 'subtopics'));
    }

    /**
     * Show question edit history
     *
     * @param $question_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getQuestionLog($question_id) {
        $question = Question::findOrFail($question_id);
        return view('question.question_log', compact('question'));
    }

    /**
     * ajax post request to get all question log
     *
     * @param $question_id
     * @param Request $request
     * @return array
     */
    public function postQuestionLog($question_id, Request $request) {
        $question = Question::findOrFail($question_id);

        // get necessary param
        $page = $request->get('page');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $pages = ceil($question->histories()->count() / $itemInPage);
        $histories = $question->histories()
            ->orderBy('created_at', 'desc')->get()->forPage($page, $itemInPage);

        $topics = [];
        // for topics
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 3 || $value->type == 4;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            $topic = Topic::findOrFail($history->text);
            array_push($topics, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'topic' => [
                    'name' => $topic->name,
                    'url' => '/topic/' . $topic->id
                ],
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp
            ]);
        }

        // title
        $titles = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 1;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($titles, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp
            ]);
        }
        array_unshift($titles, [
            'text' => $question->title
        ]);

        // content
        $contents = [];
        foreach ($histories->filter(function ($value, $key) {
            return $value->type == 2;
        }) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            array_push($contents, [
                'id' => $history->id,
                'type' => $history->type,
                'user' => $user_arr,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'timestamp' => Carbon::parse($history->created_at)->timestamp
            ]);
        }
        array_unshift($contents, [
            'text' => $question->content
        ]);

        return [
            'pages' => $pages,
            'data' => [
                'titles' => $titles,
                'contents' => $contents,
                'topics' => $topics,
            ]
        ];

    }

    /**
     * Show all answer edit history
     */
    public function getAnswerLog($answer_id) {
        $answer = Answer::findOrFail($answer_id);
        $question = $answer->question;
        return view('question.answer_log', compact('answer', 'question'));
    }

    /**
     * ajax post request to get all answer log
     *
     * @param $answer_id
     * @return array
     */
    public function postAnswerLog($answer_id, Request $request) {
        $answer = Answer::findOrFail($answer_id);
        $user = Auth::user();

        // get necessary param
        $page = $request->get('page');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $pages = ceil($answer->histories()->count() / $itemInPage);
        $histories = $answer->histories()->orderBy('created_at', 'desc')->get();

        $results = [];
        foreach ($histories->forPage($page, $itemInPage) as $history) {
            $user = User::findOrFail($history->user_id);
            $user_arr = [
                'id' => $user->id,
                'name' => $user->name,
                'url' => action('PeopleController@show', $user->url_name)
            ];
            $history_arr = [
                'id' => $history->id,
                'type' => $history->type,
                'text' => $history->text,
                'time' => Carbon::parse($history->created_at)->diffForHumans(),
                'canRollback' => $answer->owner->id == $user->id,
                'canReport' => $answer->owner->id != $user->id,
                'user' => $user_arr
            ];

            array_push($results, $history_arr);
        }

        // push current answer
        array_unshift($results, [
            'text' => $answer->answer,
        ]);

        return [
            'data' => $results,
            'pages' => $pages
        ];
    }

    /**
     * Rollback history
     *
     * @param $history_id
     * @return array|void
     */
    public function postRollback($history_id) {
        $history = History::findOrFail($history_id);
        $forItem = $history->forItem;
        $user = Auth::user();

        switch (get_class($forItem)) {
            case 'App\Answer' :
                $answer = $forItem;
                if ($answer->owner->id != $user->id) return;

                $answer->update([
                    'answer' => $history->text
                ]);

                break;
        }

        return [
            'status' => true
        ];
    }
}
