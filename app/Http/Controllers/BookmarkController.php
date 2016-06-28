<?php

namespace App\Http\Controllers;

use App\Bookmark;
use App\Question;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Http\Requests;

class BookmarkController extends Controller
{
    /**
     * How many items should we display in one page
     * @var int
     */
    protected $itemInPage = 8;


    /**
     * Find url_name and show his/her bookmarks
     *
     * @param $url_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($url_name) {
        $user = User::findUrlName($url_name);

        return view('profile.bookmark', compact('user'));
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
        if ($request->get('op') == 'add') {
            switch ($request->get('item_type')) {
                // detach first, we cannot afford bookmark twice
                case 'question':
                    $bookmark->questions()->detach($request->get('item_id'));
                    $bookmark->questions()->attach($request->get('item_id'));
                    break;
                case 'answer':
                    $bookmark->answers()->detach($request->get('item_id'));
                    $bookmark->answers()->attach($request->get('item_id'));
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
        if ($request->exists('id')) {
            $user = User::findOrFail($request->get('id'));
        }
        // get necessary parameters
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $pages = ceil($user->bookmarks()->count() / $itemInPage);

        // check if we have parameter request check whether an item is in bookmark
        $current_item_id = $request->get('current_id');
        $current_item_type = $request->get('current_type');
        $current_item_check = $current_item_id && $current_item_type;

        $results = [];
        foreach ($user->bookmarks->forPage($page, $itemInPage) as $bookmark) {
            // generate representatives
            $representatives = [];
            foreach ($bookmark->questions()->take(2) as $question) {
                array_push($representatives, [
                    'name' => $question,
                    'url' => '/question/' . $question->id
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

        // determine whether the results is empty
        if(empty($results)) {
            return [
                'data' => $results,
                'status' => false,
                'pages' => $pages
            ];
        } else {
            return [
                'data' => $results,
                'status' => true,
                'pages' => $pages
            ];
        }
    }
}
