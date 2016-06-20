<?php

namespace App\Http\Controllers;

use Auth;
use App\Reply;
use App\Answer;
use App\Question;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    protected $itemInPage = 8; // define how many item will be display in each page

    /**
     * For AJAX server side. return all the replies data
     *
     * @param Request $request
     * @return array
     */
    public function postReplyList(Request $request) {

        // validate the incomming request
        $this->validate($request, [
            'type' => 'required|',
            'item_id' => 'required|integer',
            'page' => 'required|integer'
        ]);

        $item_id = $request->get('item_id');
        $page = ($request->get('page') < '0') ? 1 : $request->get('page'); // page must be positive numbers
        $user = Auth::user();

        switch ($request->get('type')) {
            case 'question' :
                $question = Question::findOrFail($item_id);

                $results = [];
                foreach ($question->replies->sortBy('created_at')->forPage($page, $this->itemInPage) as $reply) {
                    array_push($results, [
                        'id' => $reply->id,
                        'user_name' => $reply->owner->name,
                        'user_id' => $reply->owner->id,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'votes' => $reply->vote_up_users->count(),
                    ]);
                }

                return [
                    'data' => $results,
                    'itemInPage' => $this->itemInPage,
                    'pages' => ceil($question->replies->count() / $this->itemInPage)
                ];

            case 'answer':
                $answer = Answer::findOrFail($item_id);

                $results = [];
                foreach ($answer->replies->sortBy('created_at')->forPage($page, $this->itemInPage) as $reply) {
                    $vote_up_class = ($reply->vote_up_users->contains($user->id)) ? 'active' : '';
                    array_push($results, [
                        'id' => $reply->id,
                        'user_name' => $reply->owner->name,
                        'user_id' => $reply->owner->id,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'votes' => $reply->vote_up_users->count(),
                        'vote_up_class' => $vote_up_class,
                    ]);
                }

                return [
                    'data' => $results,
                    'itemInPage' => $this->itemInPage,
                    'pages' => ceil($answer->replies->count() / $this->itemInPage)
                ];
        }
    }

    /**
     * Response ajax request to store reply
     *
     * @param $question_id
     * @param Request $request
     * @return bool
     */
    public function storeQuestionComment($question_id, Request $request) {
        $this->validate($request, [
            'user_question_reply' => 'required'
        ]);

        // get necessary param
        $user = Auth::user();
        $question = Question::findOrFail($question_id);

        // create comment
        $comment = Reply::create([
            'reply' => $request->get('user_question_reply')
        ]);
        $comment->save();

        // set relationship
        $user->replies()->save($comment);
        $question->replies()->save($comment);

        // response ajax request
        return [
            'status' => true,
            'numPages' => ceil($question->replies->count() / $this->itemInPage),
            'numReplies' => $question->replies->count()
        ];

    }


    /**
     * response ajax request to store replies for specific answer
     *
     * @param $answer_id
     * @param Request $request
     * @return array
     */
    public function storeAnswerReply($answer_id, Request $request) {
        $this->validate($request, [
            'reply' => 'required'
        ]);

        // get necessary param
        $answer = Answer::findOrFail($answer_id);
        $user = Auth::user();

        // create reply
        $reply = Reply::create($request->all());
        $reply->save();

        // set relationship
        $user->replies()->save($reply);
        $answer->replies()->save($reply);

        // response ajax request
        return [
            'status' => true,
            'numPages' => ceil($answer->replies->count() / $this->itemInPage),
            'numReplies' => $answer->replies->count()
        ];
    }

    /**
     * Response ajax request to vote (up) for reply
     * Reply can only be voted up.
     *
     * @param $reply_id
     * @param Request $request
     * @return array
     */
    public function vote($reply_id, Request $request) {
        // operation name must exist
        $this->validate($request, [
            'op' => 'required'
        ]);

        // get necessary param
        $user = Auth::user();
        $reply = Reply::findOrFail($reply_id);

        // detach all first
        $reply->vote_up_users()->detach($user->id);

        // determine which operation
        switch ($request->get('op')) {
            case 'up' :
                $reply->vote_up_users()->save($user);
                break;
        }

        return [
            'numVotes' => $reply->vote_up_users()->count(),
            'status' => true
        ];

    }

}
