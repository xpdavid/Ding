<?php

namespace App\Http\Controllers;

use Auth;
use App\Reply;
use App\Topic;
use App\Question;
use Illuminate\Http\Request;

use App\Http\Requests;

class QuestionController extends Controller
{
    /**
     * QuestionController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
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

        // determine whether to highlight a reply
        $highlight = null;
        if($request->exists('highlight_reply')) {
            $target_id = $request->get('highlight_reply');
            $target_reply = Reply::findOrFail($target_id);
            $highlight = $target_reply->highlightParameters;
        }

        return view('question.show', compact('question', 'answers', 'sorted', 'also_interest', 'highlight'));
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
