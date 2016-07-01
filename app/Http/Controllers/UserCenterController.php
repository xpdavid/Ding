<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Question;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;

class UserCenterController extends Controller
{
    protected $homeItemInPage = 15;
    /**
     * UserCenterController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show notification page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notification() {
        return view('userCenter.notification', compact('notificationsByDay'));
    }

    /**
     * Show user custom index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home() {
        return view('userCenter.home');
    }

    /**
     * Show user subscribed question
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function subscribed() {
        $count = Auth::user()->subscribe->questions()->count();
        return view('userCenter.subscribed', compact('count'));
    }

    /**
     * Show user received invitation
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invitation() {
        $count = Auth::user()->notifications()->where('type', 1)->count();
        return view('userCenter.invitation', compact('count'));
    }

    /**
     * Response ajax request to get invitation
     *
     * @return array
     */
    public function postInvitation(Request $request) {
        $user = Auth::user();
        // type 1 is invitation to answer question
        $results = [];

        // get page parameters
        $page = $request->get('page') ? $request->get('page') : 1;
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->homeItemInPage;

        foreach ($user->notifications()->where('type', 1)->where('has_read', false)
                     ->get()->forPage($page, $itemInPage)
                 as $invitation) {
            $inviter = User::find($invitation->subject_id);
            $question = Question::find($invitation->object_id);
            array_push($results, [
                'inviter' => [
                    'name' => $inviter->name,
                    'id' => $inviter->id,
                ],
                'question' => [
                    'title' => $question->title,
                    'id' => $question->id,
                    'numAnswer' => $question->answers()->count(),
                    'numSubscriber' => $question->subscribers()->count(),
                ],
                'id' => $invitation->id,
            ]);
        }

        // determine whether the results is empty
        if(empty($results)) {
            return [
                'questions' => $results,
                'status' => false
            ];
        } else {
            return [
                'questions' => $results,
                'status' => true
            ];
        }
    }



    /**
     * Response ajax request to get subscribed questions
     *
     * @param Request $request
     * @return array
     */
    public function postSubscribed(Request $request) {
        $user = Auth::user();

        // get page parameters
        $page = $request->get('page') ? $request->get('page') : 1;
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->homeItemInPage;
        
        $results = [];
        foreach ($user->subscribe->questions->forPage($page, $itemInPage) as $question) {
            array_push($results, [
                'id' => $question->id,
                'title' => $question->title,
                'numAnswer' => $question->answers()->count(),
                'visit' => 13,
                'numSubscriber' => $question->subscribers()->count(),
            ]);
        }

        // determine whether the results is empty
        if(empty($results)) {
            return [
                'questions' => $results,
                'status' => false
            ];
        } else {
            return [
                'questions' => $results,
                'status' => true
            ];
        }
    }

    /**
     * Response ajax request to get custom questions
     *
     * @param Request $request
     * @return array
     */
    public function postHome(Request $request) {
        $user = Auth::user();
        $page = $request->get('page') ? $request->get('page') : 1;
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->homeItemInPage;
        // calculate how many question each topic should take
        $results = [];
        $questions = Question::orderBy('created_at', 'desc')
            ->skip($itemInPage * ($page - 1))
            ->take($itemInPage)->get();
        foreach ($questions as $question) {
            // take 10 recent answer
            $answer = $question->answers()->orderBy('created_at', 'desc')->take(5)->get();
            // skip the question without answer
            if ($question->answers()->count() == 0) continue;
            // use the answer with the highest vote
            $answer = $answer->sortByDesc('netVotes')->first();
            // generate topics
            $topics = [];
            foreach ($question->topics as $topic) {
                array_push($topics, [
                    'name' => $topic->name,
                    'id' => $topic->id
                ]);
            }
            // check if the user has voted the answer
            $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
            $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
            array_push($results, [
                'id' => $question->id,
                'topics' => $topics,
                'topic_pic' => DImage($question->topics->first()->avatar_img_id, 40, 40),
                'answer' => [
                    'id' => $answer->id,
                    'owner' => [
                        'name' => $answer->owner->name,
                        'bio' => $answer->owner->bio,
                        'url_name' => $answer->owner->url_name,
                    ],
                    'answer' => $answer->answer,
                    'netVotes' => $answer->netVotes,
                    'numComment' => $answer->replies()->count(),
                    'vote_up_class' => $vote_up_class,
                    'vote_down_class' => $vote_down_class
                ],
                'title' => $question->title,
                'subscribed' => $user->subscribe->checkHasSubscribed($question->id, 'question')
            ]);
        }
        // determine whether the results is empty
        if(empty($results)) {
            return [
                'questions' => $results,
                'status' => false
            ];
        } else {
            return [
                'questions' => $results,
                'status' => true
            ];
        }

    }

    /**
     * Response ajax request to get notification item
     *
     * @param Request $request
     * @return array
     */
    public function postNotification(Request $request) {
        $user = Auth::user();
        // stable sort thus we sort all first
        $notifications = $user->notifications()->orderBy('created_at', 'desc')->get();
        $day = $request->get('day') ? $request->get('day') : 1;
        // group by date and sort by date
        $notificationsByDay = $notifications->groupBy(function ($notification) {
            return Carbon::parse($notification->updated_at)->format('d/m/Y');
        })->sortByDesc(function($item, $key) {
            return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
        })->forPage($day, 1);
        
        // format result
        $results = [];
        foreach ($notificationsByDay as $date => $notifications) {
            $results['date'] = $date;
            $partials = [];
            foreach ($notifications as $notification) {
                array_push($partials, [
                    'content' => $notification->renderedContent,
                    'type' => $notification->type
                ]);
            }
            $results['items'] = $partials;
        }
        // determine whether the results is empty
        if(empty($results)) {
            $results['status'] = false;
        } else {
            $results['status'] = true;
        }

        return $results;
    }
}
