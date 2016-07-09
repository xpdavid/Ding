<?php

namespace App\Http\Controllers;

use App\History;
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
