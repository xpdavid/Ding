<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;
use App\Http\Requests;

class AnswerController extends Controller
{
    protected $itemInPage = 8;
    
    public function postAnswers(Request $request) {
        $this->validate($request, [
            'question_id' => 'required|integer',
            'page' => 'required|integer',
        ]);

        $page = $request->get('page');
        $question_id = $request->get('question_id');

        $question = Question::findOrFail($question_id);

        // determine sorting method
        $answers = $question->answers;
        if ($request->exists('sorted') && $request->get('sorted') == 'created') {
            $answers = $answers->sortByDesc('created_at');
        } else {
            $answers = $answers->sortByDesc('netVotes');
        }

        $results = [];
        foreach ($answers->forPage($page, $this->itemInPage) as $answer) {
            array_push($results, [
                'id' => $answer->id,
                'user_name' => $answer->owner->name,
                'user_id' => $answer->owner->id,
                'user_bio' => $answer->owner->bio,
                'answer' => $answer->answer,
                'created_at' => $answer->createdAtHumanReadable,
                'votes' => $answer->netVotes,
                'numComment' => $answer->replies->count()
            ]);
        }

        return $results;

    }
}
