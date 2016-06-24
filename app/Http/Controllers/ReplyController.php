<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Reply;
use App\Answer;
use App\Question;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    protected $itemInPage = 8; // define how many item will be display in each page

    public function show($reply_id) {
        $reply = Reply::findOrFail($reply_id);
        $for_item = $reply->for_item;
        if (get_class($for_item) == 'App\Question') {
            return redirect(action('QuestionController@show', [
                'question_id' => $for_item->id,
                'highlight_reply' => $reply->id
            ]));
        } else if(get_class($for_item) == 'App\Answer') {
            return redirect(action('AnswerController@show', [
                'question_id' => $for_item->question->id,
                'answer_id' => $for_item->id,
                'highlight_reply' => $reply->id
            ]));
        } else {
            redirect('/');
        }
    }

    /**
     * For AJAX server side. return all the replies data
     *
     * @param Request $request
     * @return array
     */
    public function replyList(Request $request) {

        // validate the incoming request
        $this->validate($request, [
            'type' => 'required|',
            'item_id' => 'required|integer',
            'page' => 'required|integer'
        ]);

        // get necessary param
        $item_id = $request->get('item_id');
        $page = ($request->get('page') < '0') ? 1 : $request->get('page'); // page must be positive numbers
        $user = Auth::user();

        // determine the operation
        switch ($request->get('type')) {
            case 'question' :
                $question = Question::findOrFail($item_id);

                $results = [];
                foreach ($question->replies->sortBy('created_at')->forPage($page, $this->itemInPage) as $reply) {
                    $vote_up_class = ($reply->vote_up_users->contains($user->id)) ? 'active' : '';
                    $from = [
                        'user_id' => $reply->owner->id,
                        'user_name' => $reply->owner->name
                    ];

                    // whether the reply is to someone
                    if ($reply->reply_to()->count() > 0) {
                        $to = [
                            'reply_id' => $reply->reply_to->id,
                            'user_name' => $reply->reply_to->owner->name,
                            'user_id' => $reply->reply_to->owner->name,
                        ];
                    } else {
                        $to = [];
                    }

                    array_push($results, [
                        'id' => $reply->id,
                        'from' => $from,
                        'to' => $to,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'for_item' => 'question',
                        'for_item_id' => $question->id,
                        'votes' => $reply->vote_up_users->count(),
                        'vote_up_class' => $vote_up_class,
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
                    $from = [
                        'user_id' => $reply->owner->id,
                        'user_name' => $reply->owner->name
                    ];

                    // whether the reply is to someone
                    if ($reply->reply_to()->count() > 0) {
                        $to = [
                            'reply_id' => $reply->reply_to->id,
                            'user_name' => $reply->reply_to->owner->name,
                            'user_id' => $reply->reply_to->owner->name,
                        ];
                    } else {
                        $to = [];
                    }

                    array_push($results, [
                        'id' => $reply->id,
                        'from' => $from,
                        'to' => $to,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'for_item' => 'answer',
                        'for_item_id' => $answer->id,
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
     * @param $item_id
     * @param Request $request
     * @return bool
     */
    public function storeReply($item_id, Request $request) {
        $this->validate($request, [
            'text' => 'required'
        ]);

        // determine related model
        switch ($request->get('type')) {
            case 'question':
                $item = Question::findOrFail($item_id);
                break;
            case 'answer':
                $item = Answer::findOrFail($item_id);
        }

        // get current user
        $user = Auth::user();

        // create comment
        $comment = Reply::create([
            'reply' => $request->get('text')
        ]);
        $comment->save();

        // set relationship
        $user->replies()->save($comment);
        $item->replies()->save($comment);

        // check has reply to users
        if($request->exists('reply_to_reply_id')) {
            $to_reply = Reply::findOrFail($request->get('reply_to_reply_id'));
            $to_reply->receive_replies()->save($comment);
        }

        // response ajax request
        return [
            'status' => true,
            'numPages' => ceil($item->replies->count() / $this->itemInPage),
            'numReplies' => $item->replies->count()
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

    /**
     * Response ajax request to show conversation
     *
     * @param Request $request
     * @return array
     */
    public function showConversation(Request $request) {
        // operation name must exist
        $this->validate($request, [
            'initial_reply_id' => 'required|integer'
        ]);

        $results = [];
        $visited = [];
        $reply = Reply::findOrFail($request->get('initial_reply_id'));

        $this->generateConversation($reply, $results, $visited);

        return [
            'status' => true,
            'data' => $results
        ];
    }

    /**
     * Iteration step to generate conversation array
     *
     * @param Reply $reply
     * @param $results
     * @param $visited
     */
    protected function generateConversation($reply, &$results ,&$visited) {
        while($reply && !in_array($reply->id, $visited)) {
            // check $reply is not null , lazy &&
            $user = Auth::user();
            $vote_up_class = ($reply->vote_up_users->contains($user->id)) ? 'active' : '';
            $from = [
                'reply_id' => $reply->id,
                'user_id' => $reply->owner->id,
                'user_name' => $reply->owner->name
            ];

            // whether the reply is to someone
            if ($reply->reply_to()->count() > 0) {
                $to = [
                    'reply_id' => $reply->reply_to->id,
                    'user_name' => $reply->reply_to->owner->name,
                    'user_id' => $reply->reply_to->owner->name,
                ];
            } else {
                $to = [];
            }
            array_unshift($results, [
                'id' => $reply->id,
                'from' => $from,
                'to' => $to,
                'reply' => $reply->reply,
                'created_at' => $reply->createdAtHumanReadable,
                'votes' => $reply->vote_up_users->count(),
                'vote_up_class' => $vote_up_class,
            ]);
            // mark as visited
            array_push($visited, $reply->id);
            $reply = $reply->reply_to; //iteration
        }
    }

}
