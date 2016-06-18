<?php

namespace App\Http\Controllers;

use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class QuestionController extends Controller
{
    /**
     * Display the index page
     *
     */
    public function index() {
        $recommends = Question::take(5)->get();
        $current_day_hot = Question::skip(5)->take(15)->get();
        $current_month_hot = Question::skip(25)->take(15)->get();

        return view('question.index', compact('recommends', 'current_day_hot', 'current_month_hot'));
    }

    public function show($question_id) {
        $question = Question::findOrFail($question_id);

        $also_interest = [];
        foreach ($question->topics as $topic) {
            $also_interest = array_merge($also_interest, $topic->questions->take(3)->all());
        }


        return view('question.show', compact('question', 'also_interest'));
    }
}
