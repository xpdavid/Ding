<?php

namespace App\Http\Controllers;

use App\Question;
use App\Subscribe;
use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;

class UserCenterController extends Controller
{
    protected $homeItemInPage = 15;

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
        $user = Auth::user();
        $settings = $user->settings;
        $answers = $user->answers;
        $voteCount = 0;
        foreach ($answers as $answer){
            $voteCount = $voteCount + count($answer->vote_up_users) + count($answer->vote_down_users);
        }
        return view('userCenter.home', compact('user', 'settings', 'voteCount'));
    }

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
        return $results;
    }

    /**
     * Response ajax request to get notification item
     *
     * @param Request $request
     * @return array
     */
    public function postNotification(Request $request) {
        $user = Auth::user();
        $notifications = $user->notifications;
        $day = $request->get('day') ? $request->get('day') : 1;
        // group by date and sort by date
        $notificationsByDay = $notifications->groupBy(function ($notification) {
            return Carbon::parse($notification->updated_at)->format('d/m/Y');
        })->sort()->reverse()->forPage($day, 1);
        
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

        return $results;
    }
}
