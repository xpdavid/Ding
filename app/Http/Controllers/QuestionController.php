<?php

namespace App\Http\Controllers;

use Auth;
use App\Topic;
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

        // get necessary parameters & determine whether the user subscribe the question
        $user = Auth::user();
        $subscribe = $user->subscribe->checkHasSubscribed($question_id, 'question');

        // generate also_interest questions
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


        return view('question.show', compact('question', 'answers', 'sorted', 'subscribe', 'also_interest'));
    }
    

    /**
     * response form request to store question
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function ask(Request $request) {
        $this->validate($request, [
            'question_title' => 'required',
            'question_topics' => 'required'
        ]);

        // get necessary param
        $user = Auth::user();
        $question = Question::create([
            'title' => $request->get('question_title'),
            'content' => $request->get('question_detail'),
        ]);

        // the user post the question
        $user->questions()->save($question);

        // the question belongs to many topics
        foreach ($request->get('question_topics') as $topic_id) {
            $topic = Topic::findOrFail($topic_id);
            $topic->questions()->save($question);
        }

        return redirect(action('QuestionController@show', $question->id));
    }

}
