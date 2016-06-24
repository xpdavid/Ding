<?php

namespace App\Http\Controllers;

use Auth;
use App\Reply;
use App\Answer;
use App\Question;
use App\Http\Requests;
use Illuminate\Http\Request;
use Symfony\Component\Yaml\Tests\A;

class AnswerController extends Controller
{
    protected $itemInPage = 8;

    /**
     * Show specific answer for question
     *
     * @param $question_id
     * @param $answer_id
     * @param $request
     * @return View
     */
    public function show($question_id, $answer_id, Request $request) {
        $question = Question::findOrFail($question_id);
        $answer = Answer::findOrFail($answer_id);
        if ($answer->question->id != $question->id) {
            // the answer doesn't belong to the question
            return redirect(action('QuestionController@show', $question_id));
        }

        // generate also_interest questions
        $also_interest = [];
        foreach ($question->topics->shuffle()->take(3) as $topic) {
            $also_interest = array_merge($also_interest, $topic->questions->take(2)->all());
        }

        // determine whether to highlight a reply
        $highlight = null;
        if($request->exists('highlight_reply')) {
            $target_id = $request->get('highlight_reply');
            $target_reply = Reply::findOrFail($target_id);
            $highlight = $target_reply->highlightParameters;
        }
        
        return view('question.answer', compact('question', 'answer', 'also_interest', 'highlight'));
        
    }

    /**
     * response ajax request to get all answers
     *
     * @param Request $request
     * @return array
     */
    public function postAnswers(Request $request) {
        $this->validate($request, [
            'question_id' => 'required|integer',
            'page' => 'required|integer',
        ]);

        // get necessary param
        $page = $request->get('page');
        $question_id = $request->get('question_id');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $question = Question::findOrFail($question_id);
        $user = Auth::user();

        // determine sorting method
        $answers = $question->answers;
        if ($request->exists('sorted') && $request->get('sorted') == 'created') {
            $answers = $answers->sortByDesc('created_at');
        } else {
            $answers = $answers->sortByDesc('netVotes');
        }

        $results = [];
        foreach ($answers->forPage($page, $itemInPage) as $answer) {
            $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
            $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
            array_push($results, [
                'id' => $answer->id,
                'user_name' => $answer->owner->name,
                'user_id' => $answer->owner->id,
                'user_bio' => $answer->owner->bio,
                'answer' => $answer->answer,
                'created_at' => $answer->createdAtHumanReadable,
                'votes' => $answer->netVotes,
                'numComment' => $answer->replies->count(),
                'vote_up_class' => $vote_up_class,
                'vote_down_class' => $vote_down_class
            ]);
        }

        return $results;

    }


    /**
     * Response AJAX request to store answer for the question
     *
     * @param $question_id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAnswer($question_id, Request $request) {
        $this->validate($request, [
            'user_answer' => 'required|min:10'
        ]);

        // get necessary param
        $user = Auth::user();
        $question = Question::findOrFail($question_id);

        // create answers
        $answer = Answer::create([
            'answer' => $request->get('user_answer')
        ]);
        $answer->save();

        // save relationship
        $user->answers()->save($answer);
        $question->answers()->save($answer);

        // return success data
        return [
            'id' => $answer->id,
            'user_name' => $answer->owner->name,
            'user_id' => $answer->owner->id,
            'user_bio' => $answer->owner->bio,
            'answer' => $answer->answer,
            'created_at' => $answer->createdAtHumanReadable,
            'votes' => $answer->netVotes,
            'numComment' => $answer->replies->count(),
            'status' => true
        ];
    }


    /**
     * response ajax request to vote for a answer
     *
     * @param $answer_id
     * @param Request $request
     * @return bool
     */
    public function vote($answer_id, Request $request) {
        $this->validate($request, [
            'op' => 'required'
        ]);

        // get necessary param
        $user = Auth::user();
        $answer = Answer::findOrFail($answer_id);

        // detach all relationship first
        $answer->vote_up_users()->detach($user->id);
        $answer->vote_down_users()->detach($user->id);

        switch ($request->get('op')) {
            case 'up' :
                $answer->vote_up_users()->save($user);
                break;
            case 'down' :
                $answer->vote_down_users()->save($user);
                break;
        }

        return [
            'netVotes' => $answer->netVotes,
            'status' => true
        ];
    }
}
