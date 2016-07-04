<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use App\Visitor;
use App\Reply;
use App\Topic;
use App\Answer;
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

        // Visit count
        Visitor::visit($question);

        // generate also_interest questions
        $also_interest = $question->alsoInterestQuestions;

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
        
        // notification to user subscribers
        foreach ($user->subscribers as $subscriber) {
            $owner = $subscriber->owner;
            Notification::notification($owner, 12, $user->id, $question->id);
        }

        return redirect(action('QuestionController@show', $question->id));
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
                    $tmp_users = $topic->specialists->random($each_take);
                    foreach ($tmp_users as $tmp_user) {
                        if (!in_array($tmp_user->id, $users)) {
                            array_push($users, $tmp_user->id);
                        }
                    }
                }
            }

            // second to answered user (magic algorithm)
            if (count($users) < $max_users) {
                foreach ($topics as $topic) {
                    $take_item = $topic->questions()->count() > $max_users ? $max_users : $topic->questions()->count();
                    foreach ($topic->questions->random($take_item) as $question) {
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

        // delete self if any
        if(($key = array_search(Auth::user()->id, $users)) !== false) {
            unset($users[$key]);
        }


        $results = [];
        foreach ($users as $user_id) {
            $user = User::findOrFail($user_id);
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
        $question = Question::findOrFail($request->get('question_id'));

        // send notification to user
        Notification::notification($invitee, 1, $inviter->id, $question->id);

        return [
            'status' => true
        ];
    }

}
