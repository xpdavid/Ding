<?php

namespace App\Http\Controllers;

use App\Notification;
use Auth;
use App\Visitor;
use App\Reply;
use App\Answer;
use App\Question;
use App\Http\Requests;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    protected $itemInPage = 8;

    /**
     * AnswerController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        // an answer has been visited
        Visitor::visit($answer);

        if ($answer->question->id != $question->id) {
            // the answer doesn't belong to the question
            return redirect(action('QuestionController@show', $question_id));
        }

        // generate also_interest questions
        $also_interest = $question->alsoInterestQuestions;

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
                'user_pic' => DImage($answer->owner->settings->profile_pic_id, 25, 25),
                'user_url' => action('PeopleController@show', $answer->owner->url_name),
                'answer' => $answer->answer,
                'created_at' => $answer->createdAtHumanReadable,
                'votes' => $answer->netVotes,
                'numComment' => $answer->replies->count(),
                'vote_up_class' => $vote_up_class,
                'vote_down_class' => $vote_down_class,
                'canVote' => $answer->owner->canAnswerVoteBy($user)
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

        // notify all the question subscribe user
        foreach ($question->subscribers as $subscriber) {
            $owner = $subscriber->owner;
            // cannot be self-notified
            if ($owner->id == $user->id) continue;
            // check user settings
            if (!$subscriber->owner->canReceiveQuestionUpdateBy($user)) continue;
            // type 2 notification, user answer question
            Notification::notification($owner, 2, $user->id, $answer->id);
        }

        // notify all user subscribers
        foreach ($user->subscribers as $subscriber) {
            $owner = $subscriber->owner;
            // it is ok to notify twice as the sanme notification will mark as updated
            // rather than create a new one
            Notification::notification($owner, 2, $user->id, $answer->id);
        }

        // return success data
        return [
            'id' => $answer->id,
            'user_name' => $answer->owner->name,
            'user_id' => $answer->owner->id,
            'user_bio' => $answer->owner->bio,
            'user_pic' => DImage($answer->owner->settings->profile_pic_id, 25, 25),
            'answer' => $answer->answer,
            'created_at' => $answer->createdAtHumanReadable,
            'votes' => $answer->netVotes,
            'numComment' => $answer->replies->count(),
            'canVote' => true,
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

        // check user settings
        if (!$answer->owner->canAnswerVoteBy($user)) {
            // you cannot vote the user's answer
            return [
                'status' => false
            ];
        }

        // detach all relationship first
        $answer->vote_up_users()->detach($user->id);
        $answer->vote_down_users()->detach($user->id);

        switch ($request->get('op')) {
            case 'up' :
                $answer->vote_up_users()->save($user);
                // send notification (type 7 notification)
                // vote by yourself will not send notification to you
                if ($user->id != $answer->owner->id) {
                    Notification::notification($answer->owner, 7, $user->id, $answer_id);
                }

                // notify all user subscribers
                foreach ($user->subscribers as $subscriber) {
                    $owner = $subscriber->owner;
                    Notification::notification($owner, 7, $user->id, $answer_id);
                }

                break;
            case 'down' :
                $answer->vote_down_users()->save($user);
                // vote by yourself will not send notification to you
                // vote down notification (type 8 notification)
                if ($user->id != $answer->owner->id) {
                    Notification::notification($answer->owner, 8, $user->id, $answer_id);
                }
                break;
        }

        return [
            'netVotes' => $answer->netVotes,
            'status' => true
        ];
    }
}
