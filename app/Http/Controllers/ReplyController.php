<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\Http\Requests;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    /**
     * For AJAX server side. return all the replies data
     *
     * @param Request $request
     * @return array
     */
    public function getReplyList(Request $request) {
        // validate the incomming request
        $this->validate($request, [
            'type' => 'required|',
            'item_id' => 'required|numeric',
        ]);

        $item_id = $request->get('item_id');

        switch ($request->get('type')) {
            case 'question' :
                $question = Question::findOrFail($item_id);

                $results = [];
                foreach ($question->replies as $reply) {
                    array_push($results, [
                        'id' => $reply->id,
                        'user_name' => $reply->owner->name,
                        'user_id' => $reply->owner->id,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'votes' => $reply->vote_up_users->count()
                    ]);
                }

                return $results;

            case 'answer':
                $answer = Answer::findOrFail($item_id);

                $results = [];
                foreach ($answer->replies as $reply) {
                    array_push($results, [
                        'id' => $reply->id,
                        'user_name' => $reply->owner->name,
                        'user_id' => $reply->owner->id,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'votes' => $reply->vote_up_users->count()
                    ]);
                }

                return $results;
        }
    }
}
