<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Visitor;
use App\Reply;
use App\Topic;
use App\Answer;
use App\History;
use App\Question;
use App\Notification;
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

        if($question->status == 2) {
            // you cannot view draft question
            abort(404);
        }

        // Visit count
        Visitor::visit($question);

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

        return view('question.show', compact('question', 'answers', 'sorted', 'highlight'));
    }

    /**
     * Answer ajax call to get questions detail
     *
     * @param $question_id
     * @return array
     */
    public function postQuestion($question_id) {
        $user = Auth::user();
        $question = Question::findOrFail($question_id);

        if($question->status != 2 || $question->owner->id == $user->id) {
            // only if you are the owner, then you can visit it
            return $question->toJsonFull();
        } else {
            // you cannot view unpulished question
            abort(404);
        }
    }


    /**
     * Answer ajax call to store draft
     *
     * @param Request $request
     * @return array
     */
    public function storeDraft(Request $request) {
        $this->validate($request, [
            'question_title' => 'required',
            'question_topics' => 'required|array',
            'text' => 'required|min:5'
        ]);

        // get necessary param
        $user = Auth::user();

        $draft = false;
        if ($request->has('id')) {
            $draft = Question::findOrFail($request->get('id'));
            if ($draft->owner->id != $user->id) {
                dd(1);
                // the user does not have the question
                return [
                    'status' => false
                ];
            }
        }

        if ($draft) {
            // already saved answer
            $question = $draft;
            if ($question->saveDraft([
                'title' => $request->get('question_title'),
                'content' => $request->get('text')
            ])) {
                // save topics relationship
                $question->topics()->sync($request->get('question_topics'));
            } else {
                return [
                    'status' => false
                ];
            }

        } else {
            // create question
            $question = Question::create([
                'title' => $request->get('question_title'),
                'content' => $request->get('text'),
                'status' => 2 // draft status
            ]);
            $question->save();

            // save relationship
            $user->questions()->save($question);
            $question->topics()->sync($request->get('question_topics'));
        }

        return [
            'id' => $question->id
        ];
    }


    /**
     * Answer ajax call to get the latest draft
     * if there is no latest draft, return status false
     *
     * @return array
     */
    public function latestDraft() {
        $user = Auth::user();
        $draft_query = $user->questions()->whereStatus(2)->orderBy('updated_at', 'desc');
        if ($draft_query->exists()) {
            $latestDraft = $draft_query->first();
            $results =  $latestDraft->toJsonFull();
            $results['status'] = true;
            return $results;
        } else {
            return [
                'status' => false
            ];
        }
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
            'question_topics' => 'required|array'
        ]);

        // get necessary param
        $user = Auth::user();

        if ($request->has('question_draft_id')) {
            $question = Question::findOrFail($request->get('question_draft_id'));
            if($question->owner->id != $user->id) {
                // the question is not owned by user
                abort(401);
            }

            // save draft last time
            $question->saveDraft([
                'title' => $request->get('question_title'),
                'content' => $request->get('question_detail')
            ]);

            // save topics relationship
            $question->topics()->sync($request->get('question_topics'));

            $question->publish();

        } else {
            $question = Question::create([
                'title' => $request->get('question_title'),
                'content' => $request->get('question_detail'),
            ]);

            // the user post the question
            $user->questions()->save($question);

            // the question belongs to many topics
            $question->topics()->sync($request->get('question_topics'));
        }

        $user->notifySubscriber(12, $question);
        // notification to user subscribers

        return redirect(action('QuestionController@show', $question->id));
    }

    /**
     * Update question
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request) {
        $question = Question::findOrFail($request->get('question_edit_id'));

        // you must have enough authority to edit it
        if(!Auth::user()->operation(9) && $question->owner->id != Auth::user()->id) {
            abort(401);
        }
        // cannot update for draft question
        if($question->status == 2) {
            // you cannot update unpulished question
            abort(404);
        }

        $this->validate($request, [
            'question_edit_id' => 'required|integer',
            'question_title' => 'required',
            'question_topics' => 'required|array',
        ]);



        // record topic change
        $question->recordTopicsHistory($request->get('question_topics'));
        $question->topics()->sync($request->get('question_topics'));
        
        $question->update([
            'title' => $request->get('question_title'),
            'content' => $request->get('question_detail')
        ], ['history' => true]);

        return redirect()->action('QuestionController@show', $question->id);
    }

    /**
     * Answer ajax request to close a question
     *
     * @param $question_id
     * @param Request $request
     * @return array
     */
    public function close($question_id, Request $request) {
        $question = Question::findOrFail($question_id);

        if ($question->close()) {
            $question->histories()->save(History::create([
                'user_id' => Auth::user()->id,
                'type' => 5,
                'text' => $request->get('reason')
            ]));

            return [
                'status' => true
            ];
        } else {
            return [
                'status' => false
            ];
        }

    }

    /**
     * Answer ajax request to close a question
     *
     * @param $question_id
     * @param Request $request
     * @return array
     */
    public function open($question_id, Request $request) {
        $question = Question::findOrFail($question_id);

        if ($question->open()) {
            $question->histories()->save(History::create([
                'user_id' => Auth::user()->id,
                'type' => 6,
                'text' => $request->get('reason')
            ]));

            return [
                'status' => true
            ];
        } else {
            return [
                'status' => false
            ];
        }

    }



    /**
     * Generate user who would possible answer the question
     *
     * @param $question_id
     * @param Request $request
     * @return array(json)
     */
    public function invite_panel($question_id, Request $request) {
        $users = [];
        $max_users = 20;
        $question = Question::findOrFail($question_id);
        $auth_user = Auth::user();
        $topics = $question->topics;
        if ($request->has('user')) {
            // user search
            $users = User::noneSimilarMatch($request->get('user'))->take($max_users)->lists('id')->all();
        } else {
            // recommend invitee
            $each_take = ceil($max_users / $question->topics()->count());
            // priority to specialists
            foreach ($topics as $topic) {
                if ($topic->specialists()->count() < $each_take) {
                    foreach ($topic->specialists() as $specialist) {
                        if (!in_array($specialist->id, $users)) {
                            array_push($users, $specialist->id);
                        }
                    }
                } else {
                    if ($topic->specialists()->count() > 0) {
                        $tmp_users = $topic->specialists->random($each_take);
                        foreach ($tmp_users as $tmp_user) {
                            if (!in_array($tmp_user->id, $users)) {
                                array_push($users, $tmp_user->id);
                            }
                        }
                    }
                }
            }

            // second to answered user (magic algorithm)
            if (count($users) < $max_users) {
                foreach ($topics as $topic) {
                    $publishedQuestions = $topic->publishedQuestions;
                    $take_item = $publishedQuestions->count() > $max_users ? $max_users : $topic->questions()->count();
                    if ($take_item > 1) {
                        foreach ($topic->publishedQuestions->random($take_item) as $question) {
                            if ($question->answers()->count() > 0) {
                                $user = $question->answers->random()->owner;
                                if (!in_array($user->id, $users)) {
                                    array_push($users, $user->id);
                                }
                            }
                        }
                    }
                }
            }
        }

        // delete self if any
        if(($key = array_search(Auth::user()->id, $users)) !== false) {
            unset($users[$key]);
        }
        

        $results = [];
        foreach ($users as $user_id) {
            $user = User::findOrFail($user_id);

            // check invitation validation
            if(!$user->canBeInvitedBy($auth_user)) {
                continue;
            }

            $topics_arr = [];
            foreach ($topics as $topic) {
                array_push($topics_arr, [
                    'numAnswerInTopic' => $user->answersInTopic($topic->id)->count(),
                    'name' => $topic->name,
                    'id' => $topic->id,
                ]);
            }
            array_push($results, [
                'name' => $user->name,
                'img' => DImage($user->settings->profile_pic_id, 30, 30),
                'id' => $user->id,
                'bio' => $user->bio,
                'topics' => $topics_arr,
                'question_id' => $question_id,
                'url_name' => $user->url_name,
                'invited' =>
                    Notification::hasNotification($user->notifications(), 1, $auth_user->id, $question->id)
            ]);
        }

        return $results;

    }

    /**
     * Response ajax request to invite people to answer a question
     *
     * @param Request $request
     * @return array(json)
     */
    public function invite(Request $request) {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'question_id' => 'required|integer'
        ]);

        $invitee = User::findOrFail($request->get('user_id'));
        $inviter = Auth::user();

        if (!$invitee->canBeInvitedBy($inviter)) {
            return [
                'false' => true,
                'error' => 'The user has set only the people he/she subscribe to can send invitation'
            ];
        }

        $question = Question::findOrFail($request->get('question_id'));

        // send notification to user
        Notification::notification($invitee, 1, $inviter->id, $question->id);

        return [
            'status' => true
        ];
    }

}
