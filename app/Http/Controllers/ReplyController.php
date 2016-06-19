<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\Http\Requests;
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
                    array_push($results, [
                        'id' => $reply->id,
                        'user_name' => $reply->owner->name,
                        'user_id' => $reply->owner->id,
                        'reply' => $reply->reply,
                        'created_at' => $reply->createdAtHumanReadable,
                        'votes' => $reply->vote_up_users->count()
                    ]);
                }

                return [
                    'data' => $results,
                    'itemInPage' => $this->itemInPage,
                    'pages' => ceil($answer->replies->count() / $this->itemInPage)
                ];
        }
    }

}
