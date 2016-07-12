<?php

namespace App\Http\Controllers;

use App\Answer;
use Auth;
use App\Visitor;
use Carbon\Carbon;
use App\User;
use App\Bookmark;
use App\Question;
use App\Http\Requests;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    /**
     * How many items should we display in one page
     * @var int
     */
    protected $itemInPage = 8;
    protected $maxItem = 20;


    /**
     * Find url_name and show his/her bookmarks
     *
     * @param $url_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($url_name) {
        $user = User::findUrlName($url_name);

        return view('profile.bookmark', compact('user'));
    }

    /**
     * Show specific bookmark
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id) {
        $user = Auth::user();
        $bookmark = Bookmark::findOrFail($id);

        // it is not public and it is not owner
        if (!$bookmark->is_public && $user->id != $bookmark->owner->id) {
            abort(401);
        }

        // Visit count
        Visitor::visit($bookmark);

        return view('bookmark.show', compact('bookmark'));
    }

    /**
     * Answer ajax request to show bookmark
     *
     * @param $id
     * @param Request $request
     * @return array
     */
    public function postShow($id, Request $request) {
        $bookmark = Bookmark::findOrFail($id);
        $user = Auth::user();

        // check if is owner and is_public
        if (!$bookmark->is_public && $user->id != $bookmark->owner->id) {
            abort(401);
        }

        // get page parameters
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        switch ($request->get('type')) {
            case 'question':
                $pages = ceil($bookmark->questions()->count() / $itemInPage);

                $results = [];
                foreach ($bookmark->questions->forPage($page, $itemInPage) as $question) {
                    array_push($results, [
                        'id' => $question->id,
                        'title' => $question->title,
                        'numAnswer' => $question->answers()->count(),
                        'visit' => 13,
                        'numSubscriber' => $question->subscribers()->count(),
                        'subscribed' => $user->subscribe->checkHasSubscribed($question->id, 'question')
                    ]);
                }

                return [
                    'data' => $results,
                    'pages' => $pages
                ];

                break;
            case 'answer':
                $groupByQuestion = $bookmark->answers->groupBy(function($item, $key) {
                    return $item->question->id;
                });

                $pages = ceil($groupByQuestion->count() / $itemInPage);

                $results = [];

                foreach ($groupByQuestion->forPage($page, $itemInPage) as $question_id => $answers) {
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
                            'vote_down_class' => $vote_down_class
                        ]);
                    }
                    $arr['answers'] = $answers_arr;

                    array_push($results, $arr);
                }

                return [
                    'data' => $results,
                    'pages' => $pages
                ];

                break;
        }
    }



    /**
     * Response ajax call to store a new bookmark
     *
     * @param Request $request
     */
    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required',
        ]);
        // get necessary parameters
        $user = Auth::user();
        $isPublic = $request->get('isPublic') == "true" ? true : false;
        $description = $request->get('description');

        // create a bookmark
        $bookmark = Bookmark::create([
            'name' => $request->get('name'),
            'description' => $description,
            'is_public' => $isPublic
        ]);

        // save a bookmark
        $user->bookmarks()->save($bookmark);
    }

    /**
     * Answer ajax call to update a bookmark
     *
     * @param $bookmark_id
     * @param Request $request
     * @return array|void
     */
    public function update($bookmark_id, Request $request) {
        $bookmark = Bookmark::findOrFail($bookmark_id);
        if ($bookmark->owner->id != Auth::user()->id) {
            // the bookmark is not owner by the user
            return ;
        }
        if (!$request->get('is_public') && $bookmark->subscribers()->count() > 0) {
            // you cannot change to private if it has subscribers
            return [
                'status' => false
            ];
        }
        $bookmark->update([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'is_public' => $request->get('is_public')
        ]);
        return [
            'id' => $bookmark->id,
            'name' => $bookmark->name,
            'description' => $bookmark->description,
            'is_public' => $bookmark->is_public,
            'status' => true,
        ];
    }

    /**
     * AJAX delete bookmark
     *
     * @param $bookmark_id
     * @return array
     */
    public function delete($bookmark_id) {
        $user = Auth::user();
        $bookmark = Bookmark::findOrFail($bookmark_id);
        if ($bookmark->owner->id != Auth::user()->id) {
            // the bookmark is not owner by the user
            return [
                'status' => false
            ];
        }
        // we can't delete it if it has subscribers
        if ($bookmark->subscribers()->count() > 0) {
            return [
                'status' => false
            ];
        }
        // detach all relationship first
        $bookmark->questions()->detach();
        $bookmark->answers()->detach();
        $bookmark->delete();
        return [
            'status' => true,
            'redirect' => route('people.bookmark', $user->url_name)
        ];
    }


    /**
     * Ajax add a item in to the bookmark
     */
    public function operation(Request $request) {
        $this->validate($request, [
            'id' => 'required|integer',
            'item_type' => 'required',
            'item_id' => 'required',
            'op' => 'required'
        ]);
        $bookmark = Bookmark::findOrFail($request->get('id'));
        // security check, only can the owner make change
        if ($bookmark->owner->id != Auth::user()->id) {
            // terminate as it is not change by owner
            return ;
        }
        if ($request->get('op') == 'add') {
            // mark bookmark as updated
            $bookmark->updated_at = Carbon::now();
            switch ($request->get('item_type')) {
                // detach first, we cannot afford bookmark twice
                case 'question':
                    $bookmark->questions()->detach($request->get('item_id'));
                    $question = Question::findOrFail($request->get('item_id'));
                    if ($question->status == 1) {
                        // bookmark only published question
                        $bookmark->questions()->attach($request->get('item_id'));
                    }
                    break;
                case 'answer':
                    $bookmark->answers()->detach($request->get('item_id'));
                    $answer = Answer::findOrFail($request->get('item_id'));
                    if ($answer->status == 1) {
                        // bookmark only published answer
                        $bookmark->answers()->attach($request->get('item_id'));
                    }
                    break;
            }
        } else if ($request->get('op') == 'remove') {
            switch ($request->get('item_type')) {
                case 'question':
                    $bookmark->questions()->detach($request->get('item_id'));
                    break;
                case 'answer':
                    $bookmark->answers()->detach($request->get('item_id'));
                    break;
            }
        }

        return [
            'id' => $request->get('id'),
            'numAnswer' => $bookmark->answers()->count(),
            'numQuestion' => $bookmark->questions()->count(),
            'numSubscriber' => $bookmark->subscribers()->count(),
            'isIn' => $request->get('op') == 'add',
        ];
    }

    /**
     * Answer ajax call to get all bookmark
     *
     * @param Request $request
     * @return array
     */
    public function postBookmark(Request $request) {
        $user = Auth::user();
        $type = $request->has('type') ? $request->get('type') : 'user';

        if ($type == 'user') {
            $bookmarks = $user->bookmarks;
            // get auth user first && get public bookmark
            if ($request->has('id') && ($request->get('id') != $user->id)) {
                $user = User::findOrFail($request->get('id'));
                $bookmarks = $user->bookmarks()->where('is_public', true)->get();
            }
        } else if ($type = 'subscribed') {
            $bookmarks = $user->subscribe->bookmarks;
        }



        // get necessary parameters
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $pages = ceil($bookmarks->count() / $itemInPage);

        // check if we have parameter request check whether an item is in bookmark
        $current_item_id = $request->get('current_id');
        $current_item_type = $request->get('current_type');
        $current_item_check = $current_item_id && $current_item_type;

        $results = [];
        foreach ($bookmarks->forPage($page, $itemInPage) as $bookmark) {
            // generate representatives
            $representatives = [];
            // for question
            foreach ($bookmark->questions->take(2) as $question) {
                array_push($representatives, [
                    'name' => $question->title,
                    'url' => '/question/' . $question->id,
                    'type' => 'Question'
                ]);
            }
            // for answers
            foreach ($bookmark->answers->take(2) as $answer) {
                array_push($representatives, [
                    'name' => $answer->question->title,
                    'url' => '/question/' . $answer->question->id . '/answer/' . $answer->id,
                    'type' => 'Answer'
                ]);
            }
            $arr = [
                'id' => $bookmark->id,
                'name' => $bookmark->name,
                'numQuestion' => $bookmark->questions()->count(),
                'numAnswer' => $bookmark->answers()->count(),
                'numSubscriber' => $bookmark->subscribers()->count(),
                'representatives' => $representatives,
            ];
            // check if current_item is in the bookmark
            if ($current_item_check) {
                $arr['isIn'] = Bookmark::isIn($bookmark->id, $current_item_id, $current_item_type);
            }
            array_push($results, $arr);
        }

        return [
            'data' => $results,
            'status' => false,
            'pages' => $pages
        ];
    }

    /**
     * Answer ajax call to return hot topics
     */
    public function hot(Request $request) {
        $maxItem = $request->exists('maxItem') ? $request->get('maxItem') : $this->maxItem;
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $pages = ceil(Bookmark::publicItem()->count() / $itemInPage);

        $results = [];
        foreach (Bookmark::publicItem()->orderBy('updated_at')->take($maxItem)->get()
                     ->sortByDesc(function($item, $index) {
                            return $item->subscribers()->count();
                    })->forPage($page, $itemInPage) as $bookmark) {
            array_push($results, [
                'id' => $bookmark->id,
                'name' => $bookmark->name,
                'numQuestion' => $bookmark->questions()->count(),
                'numAnswer' => $bookmark->answers()->count(),
                'numSubscriber' => $bookmark->subscribers()->count(),
            ]);
        }

        return [
            'pages' => $pages,
            'data' => $results,
        ];
    }
}
