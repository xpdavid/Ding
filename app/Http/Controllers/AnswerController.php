<?php

namespace App\Http\Controllers;

use App\Notification;
use Auth;
use App\Visitor;
use App\Reply;
use App\Answer;
use App\Question;
use App\Http\Requests;
use Carbon\Carbon;
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
     * Answer ajax call to get answer
     *
     * @param $answer_id
     * @return mixed
     */
    public function postAnswer($answer_id) {
        $user = Auth::user();
        $answer = Answer::findOrFail($answer_id);
        if ($answer->status != 2 || $answer->owner->id == $user->id) {
            return $answer->answer;
        } else {
            abort(401); // you cannot get an unpublished answer
        }
    }

    /**
     * get answer full content
     *
     * @param $answer_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getAnswer($answer_id) {
        $answer = Answer::findOrFail($answer_id);
        return redirect()->action('AnswerController@show', [$answer->question->id, $answer->id]);
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

        if ($question->status != 1 || $answer->status != 1) {
            // you cannot view unpublished answer/ question
            abort(401);
        }

        if ($answer->question->id != $question->id) {
            // the answer doesn't belong to the question
            return redirect(action('QuestionController@show', $question_id));
        }

        // determine whether to highlight a reply
        $highlight = null;
        if($request->exists('highlight_reply')) {
            $target_id = $request->get('highlight_reply');
            $target_reply = Reply::findOrFail($target_id);
            $highlight = $target_reply->highlightParameters;
        }
        
        return view('question.answer', compact('question', 'answer', 'highlight'));
        
    }

    /**
     * Update answer with specific id
     *
     * @param $answer_id
     * @param Request $request
     * @return array(json)
     */
    public function update($answer_id, Request $request) {
        $this->validate($request, [
            'answer' => 'required'
        ]);

        $user = Auth::user();
        $answer = Answer::findOrFail($answer_id);

        // check if the answer belongs to the current user
        if ($user->id != $answer->owner->id) {
            return [
                'status' => false
            ];
        }

        // update answer with set history
        $answer->update([
            'answer' => $request->get('answer')
        ], ['history' => true]);

        return [
            'status' => true,
            'answer' => $answer->summary,
        ];
    }

    /**
     * response ajax request to get all answers
     *
     * @param Request $request
     * @return array
     */
    public function postAnswers(Request $request) {
        if ($request->has('ids')) {
            // get specific answer
            $answers = collect();
            foreach ($request->get('ids') as $answer_id) {
                $answer = Answer::findOrFail($answer_id);
                // only get published answer
                if ($answer->status == 1) {
                    $answers->push($answer);
                }
            }

        } else {
            // get all answer
            $this->validate($request, [
                'question_id' => 'required|integer',
                'page' => 'required|integer',
            ]);
            $question_id = $request->get('question_id');
            $question = Question::findOrFail($question_id);
            // cannot view unpublished question
            if ($question->status != 1) abort(401);
            // get published answer
            $answers = $question->answers()->whereStatus(1)->get();
        }

        // get necessary param
        $page = $request->get('page');
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $user = Auth::user();

        // determine sorting method
        if ($request->exists('sorted') && $request->get('sorted') == 'created') {
            $answers = $answers->sortByDesc('created_at');
        } else {
            $answers = $answers->sortByDesc('netVotes');
        }

        $results = [];
        foreach ($answers->forPage($page, $itemInPage) as $answer) {
            array_push($results, $answer->jsonAnswerDetail());
        }

        return $results;

    }

    /**
     * Post request to get draft by user
     *
     * @param $answer_id
     * @return array
     */
    public function postFullDraft($answer_id) {
        $user = Auth::user();
        // validation, the answer must be created by the user
        $answer = Answer::findOrFail($answer_id);
        if ($answer->owner->id != $user->id) {
            // the answer is not owned by the auth user
            return [
                'status' => false
            ];
        }

        if ($answer->status != 2) {
            abort(404); // this answer is not in draft status
        }

        return [
            'draft' => $answer->answer,
            'status' => true,
            'time' => Carbon::parse($answer->updated_at)->diffForHumans()
        ];
    }

    /**
     * Answer ajax request to store answer draft
     *
     * @param $question_id
     * @param Request $request
     * @return array
     */
    public function storeDraft($question_id, Request $request) {
        $this->validate($request, [
            'text' => 'required|min:5'
        ]);

        // get necessary param
        $user = Auth::user();
        $question = Question::findOrFail($question_id);
        $draft = $question->answerDraftBy($user->id);

        if ($question->status != 1) {
            abort(401); // the answer is not in published status.
        }

        if ($draft) {
            // already saved answer
            $answer = $draft;
            $answer->saveDraft([
                'answer' => $request->get('text')
            ]);

        } else {
            // no answer under the question
            // check has answer before
            if ($question->hasPublishedAnswerBy($user->id)) {
                // user already post the answer in the question
                return [
                    'status' => false
                ];
            }

            // create answers
            $answer = Answer::create([
                'answer' => $request->get('text'),
                'status' => 2 // draft status
            ]);
            $answer->save();

            // save relationship
            $user->answers()->save($answer);
            $question->answers()->save($answer);
        }

        return [
            'id' => $answer->id
        ];
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
            'user_answer' => 'required|min:5'
        ]);

        // get necessary param
        $user = Auth::user();
        $question = Question::findOrFail($question_id);

        if ($question->status != 1) {
            // you can only answer an published status question item
            abort(401);
        }

        if ($request->has('id')) {
            $answer = Answer::findOrFail($request->get('id'));
            // the post answer is an draft
            // save the draft immediately
            $answer->saveDraft([
                'answer' => $request->get('user_answer')
            ]);
            $answer->publish(); // the auth process is contained in the publish method
        } else {
            // user has already answer the question
            if ($question->hasPublishedAnswerBy($user->id)) {
                return [
                    'status' => false
                ];
            }

            // create answers
            $answer = Answer::create([
                'answer' => $request->get('user_answer')
            ]);
            $answer->save();

            // save relationship
            $user->answers()->save($answer);
            $question->answers()->save($answer);

            // notification
            $question->notifySubscriber($answer);
            $user->notifySubscriber(2, $answer);
        }

        // return success data
        $data = $answer->jsonAnswerDetail();
        $data['status'] = true;
        return $data;
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

        if ($answer->status != 1) {
            // the answer is in draft status
            abort(401);
        }

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
