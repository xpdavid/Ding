<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class QuestionController extends Controller
{

    /**
     * Display index page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {
        $recommends = Question::take(5)->get();
        $current_day_hot = Question::skip(5)->take(15)->get();
        $current_month_hot = Question::skip(25)->take(15)->get();

        return view('question.index', compact('recommends', 'current_day_hot', 'current_month_hot'));
    }

    /**
     * show specific question detail
     *
     * @param $question_id
     * @param $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($question_id, Request $request) {
        $question = Question::findOrFail($question_id);

        $also_interest = [];
        foreach ($question->topics as $topic) {
            $also_interest = array_merge($also_interest, $topic->questions->take(3)->all());
        }

        // determine how to sort answers
        $answers = $question->answers;
        $sorted = 'rate';
        if ($request->exists('sorted') && $request->get('sorted') == 'created') {
            $sorted = 'created';
            $answers = $answers->sortByDesc('created_at');
        } else {
            $answers = $answers->sortByDesc('netVotes');
        }


        return view('question.show', compact('question', 'answers', 'sorted', 'also_interest'));
    }

}
