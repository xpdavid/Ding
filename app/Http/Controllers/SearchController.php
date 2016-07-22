<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Topic;
use App\Answer;
use App\Question;
use App\Http\Requests;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected $itemInPage = 12;


    /**
     * UserCenterController constructor.
     *
     * define middleware
     */
    public function __construct()
    {
        $this->middleware('auth_real');
    }

    public function index(Request $request) {
        $this->validate($request, [
            'query' => 'required',
        ]);

        $type = $request->has('type') ? $request->get('type') : 'question';
        $query = $request->get('query');
        $range = $request->has('range') ? $request->get('range') : '';

        return view('search.index', compact('type' , 'query', 'range'));
    }

    public function postSearch(Request $request) {
        $this->validate($request, [
            'page' => 'required',
            'query' => 'required'
        ]);
        // get necessary parameters
        $page = $request->has('page') ? $request->get('page') : 1;
        $itemInPage = $request->has('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $type = $request->has('type') ? $request->get('type') : 'question';
        $query = $request->get('query');
        $range = $request->has('range') ? $request->get('range') : null;
        $user = Auth::user();
        // switch type
        switch ($type) {
            case 'question' :
                $pages = ceil(Question::noneSimilarMatchAll($query, $range)->count() / $itemInPage);
                $questions = Question::noneSimilarMatchAll($query, $range)->orderBy('created_at', 'desc')
                    ->skip(($page - 1) * $itemInPage)->take($itemInPage)->get();
                $results = [];
                foreach ($questions as $question) {
                    array_push($results, [
                        'id' => $question->id,
                        'title' => $question->title,
                        'content' => $question->content
                    ]);
                }
                return [
                    'pages' => $pages,
                    'data' => $results
                ];
            case 'answer' :
                $pages = ceil(Answer::noneSimilarMatch($query, $range)->count() / $itemInPage);
                $answers = Answer::noneSimilarMatch($query, $range)->orderBy('created_at', 'desc')
                    ->skip(($page - 1) * $itemInPage)->take($itemInPage)->get();

                $groupByQuestion = $answers->groupBy(function($item, $key) {
                    return $item->question->id;
                });
                $results = [];
                foreach ($groupByQuestion as $question_id => $answers) {
                    $question = Question::findOrFail($question_id);
                    $arr = [
                        'question' => [
                            'id' => $question_id,
                            'title' => $question->title
                        ]
                    ];
                    $answers_arr = [];
                    foreach ($answers as $answer) {
                        $vote_up_class = $answer->vote_up_users->contains($user->id) ? 'active' : '';
                        $vote_down_class = $answer->vote_down_users->contains($user->id) ? 'active' : '';
                        array_push($answers_arr, [
                            'id' => $answer->id,
                            'user_name' => $answer->owner->name,
                            'user_id' => $answer->owner->id,
                            'user_bio' => $answer->owner->bio,
                            'user_pic' => DImage($answer->owner->settings->profile_pic_id, 25, 25),
                            'answer' => $answer->answer,
                            'created_at' => $answer->createdAtHumanReadable,
                            'votes' => $answer->netVotes,
                            'numComment' => $answer->replies->count(),
                            'vote_up_class' => $vote_up_class,
                            'vote_down_class' => $vote_down_class,
                            'canVote' => $answer->owner->canAnswerVoteBy($user),
                            'canEdit' => $answer->owner->id == $user->id,
                        ]);
                    }
                    $arr['answers'] = $answers_arr;

                    array_push($results, $arr);
                }

                return [
                    'data' => $results,
                    'pages' => $pages
                ];
            case 'user':
                $pages = ceil(User::noneSimilarMatch($query)->count() / $itemInPage);
                $users = User::noneSimilarMatch($query)
                    ->skip(($page - 1) * $itemInPage)->take($itemInPage)->get();
                $results = [];
                foreach ($users as $s_user) {
                    array_push($results, [
                        'id' => $s_user->id,
                        'name' => $s_user->name,
                        'bio' => $s_user->bio,
                        'numAnswer' => $s_user->answers()->count(),
                        'numSubscriber' => $s_user->subscribers()->count(),
                        'isSubscribe' => $user->subscribe->checkHasSubscribed($s_user->id, 'user'),
                        'url_name' => $s_user->url_name,
                        'img' => DImage($s_user->settings->profile_pic_id, 50, 50),
                        'canSubscribe' => $s_user->canSubscribedBy($user)
                    ]);
                }

                return [
                    'data' => $results,
                    'pages' => $pages
                ];
            case 'topic':
                $pages = ceil(Topic::noneSimilarMatch($query)->count() / $itemInPage);
                $topics = Topic::noneSimilarMatch($query)
                    ->skip(($page - 1) * $itemInPage)->take($itemInPage)->get();
                $results = [];
                foreach ($topics as $topic) {
                    array_push($results, [
                        'id' => $topic->id,
                        'name' => $topic->name,
                        'description' => $topic->description,
                        'numSubtopic' => $topic->subtopics()->count(),
                        'isSubscribed' => $user->subscribe->checkHasSubscribed($topic->id, 'topic'),
                        'pic' => DImage($topic->avatar_img_id, 40, 40),
                    ]);
                }

                return [
                    'data' => $results,
                    'pages' => $pages
                ];
        }

    }
}
