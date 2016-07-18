<?php

namespace App\Http\Controllers;

use App\Answer;
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
     * Show all user bookmark
     */
    public function bookmark() {
        $user = Auth::user();
        $myBookmark_count = $user->bookmarks()->count();
        $subscribedBookmark_count = $user->subscribe->bookmarks()->count();
        return view('userCenter.bookmark', compact('myBookmark_count', 'subscribedBookmark_count'));
    }

    /**
     * Get user draft
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function draft() {
        $user = Auth::user();
        $draft_question_count = $user->questions()->whereStatus(2)->count();
        $draft_answer_count = $user->answers()->whereStatus(2)->count();
        return view('userCenter.draft', compact('draft_question_count', 'draft_answer_count'));
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
                    'url_name' => $inviter->url_name
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
                'visit' => $question->hit->total,
                'numSubscriber' => $question->subscribers()->count(),
                'isClosed' => $question->isClosed(),
                'subscribed' => true
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

        $questions = Question::news($page, $itemInPage);
        // filter base on user hide topics attribute
        $questions = $user->filterQuestions($questions);

        foreach ($questions as $question) {
            $answer = $question->hotAnswer;
            // generate topics
            $topics = [];
            foreach ($question->topics as $topic) {
                array_push($topics, [
                    'name' => $topic->name,
                    'id' => $topic->id
                ]);
            }
            $answer_arr = false;
            if ($answer != null) {
                array_push($results, $answer->jsonAnswerSummary());
            } else {
                array_push($results, [
                    'id' => $question->id,
                    'topics' => $topics,
                    'topic_pic' => DImage($question->topics->first()->avatar_img_id, 40, 40),
                    'answer' => $answer_arr,
                    'title' => $question->title,
                    'subscribed' => $user->subscribe->checkHasSubscribed($question->id, 'question'),
                ]);
            }
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
     * Answer ajax request to get draft question
     *
     * @param Request $request
     * @return array
     */
    public function postDraftQuestion(Request $request) {
        return $this->generateDraft('question', $request);
    }

    /**
     * Answer ajax request to get draft answer
     *
     * @param Request $request
     * @return array
     */
    public function postDraftAnswer(Request $request) {
        return $this->generateDraft('answer', $request);
    }

    /**
     * AJAX helper to get user draft
     *
     * @param $type
     * @param Request $request
     * @return array
     */
    public function generateDraft($type, Request $request) {
        $user = Auth::user();
        $page = $request->get('page') ? $request->get('page') : 1;
        $itemInPage = $request->get('itemInPage') ? $request->get('itemInPage') : $this->homeItemInPage;

        switch ($type) {
            case 'question':
                $drafts = $user->questions()->whereStatus(2)->orderBy('updated_at')->get()
                    ->forPage($page, $itemInPage);
                $results = [];
                foreach ($drafts as $draft) {
                    array_push($results, $draft->toJsonSummary());
                }
                return [
                    'questions' => $results,
                    'status' => count($results) != 0,
                ];
                break;
            case 'answer':
                $drafts = $user->answers()->whereStatus(2)->orderBy('updated_at')->get()
                    ->forPage($page, $itemInPage);
                $results = [];
                foreach ($drafts as $draft) {
                    array_push($results, $draft->jsonAnswerSummary());
                }
                return [
                    'questions' => $results,
                    'status' => count($results) != 0,
                ];
                break;
        }
    }

    /**
     * delete draft created by user
     *
     * @param $draft_id
     * @param Request $request
     * @return array
     */
    public function deleteDraft($draft_id, Request $request) {
        $user = Auth::user();
        switch ($request->get('type')) {
            case 'answer':
                $answer = Answer::findOrFail($draft_id);
                if ($answer->owner->id != $user->id || $answer->status != 2) {
                    // the answer doesn't belong to the user
                    // the answer status is not draft status
                    return [
                        'status' => false,
                    ];
                }
                $answer->delete();
                break;
            case 'question':
                $question = Question::findOrFail($draft_id);
                if ($question->owner->id != $user->id || $question->status != 2) {
                    // the answer doesn't belong to the user
                    // the answer status is not draft status
                    return [
                        'status' => false,
                    ];
                }
                // detach all topics
                $question->topics()->detach();
                $question->delete();
                break;
        }
        return [
            'status' => true,
        ];
    }

    /**
     * Publish draft and notify users.
     *
     * @param $id
     * @param Request $request
     * @return array
     */
    public function publishDraft($id, Request $request) {
        $user = Auth::user();
        switch ($request->get('type')) {
            case 'question':
                $question = Question::findOrFail($id);
                $status = $question->publish();
                if ($status) {
                    $user->notifySubscriber(12, $question);
                }

                return [
                    'status' => $status,
                    'location' => '/question/' . $question->id
                ];
                break;
            case 'answer':
                $answer = Answer::findOrFail($id);
                $status = $answer->publish();
                $question = $answer->question;
                if ($status) {
                    // notification
                    $question->notifySubscriber($answer);
                    $user->notifySubscriber(2, $answer);
                }

                return [
                    'status' => $status,
                    'location' => '/answer/' . $answer->id
                ];
                break;
        }

        return [
            'status' => false
        ];

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

    /**
     * Response ajax request to get point item
     *
     * @param Request $request
     * @return array
     */
    public function postPoint(Request $request) {
        $user = Auth::user();
        // stable sort thus we sort all first
        $points = $user->points()->orderBy('created_at', 'desc')->get();
        $day = $request->get('day') ? $request->get('day') : 1;
        // group by date and sort by date
        $pointsByDay = $points->groupBy(function ($point) {
            return Carbon::parse($point->updated_at)->format('d/m/Y');
        })->sortByDesc(function($item, $key) {
            return Carbon::createFromFormat('d/m/Y', $key)->timestamp;
        })->forPage($day, 1);

        // format result
        $results = [];
        foreach ($pointsByDay as $date => $points) {
            $results['date'] = $date;
            $partials = [];
            foreach ($points as $point) {
                array_push($partials, [
                    'content' => $point->renderedContent,
                    'type' => $point->type
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
