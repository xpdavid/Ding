<?php

namespace App\Http\Controllers;

use Log;
use File;
use Hash;
use IImage;
use App\Job;
use App\User;
use App\Topic;
use App\Image;
use App\Visitor;
use App\Question;
use App\EducationExp;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{
    protected $itemInPage = 10;

    /**
     * PeopleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user homepage according to their customer url name
     *
     * @param  string  $url_name
     * @return \Illuminate\Http\Response
     */
    public function show($url_name)
    {
        $user = User::findUrlName($url_name);

        // Visitor count
        Visitor::visit($user);

        return view('profile.index', compact('user'));
    }


    /**
     * Show the form for editing the specified user profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {   
        $user = Auth::user();
        $settings = $user->settings;

        // generate process
        $count = 0;
        if ($settings->profile_pic_id != 0) {
            $count += 2;
        }
        if ($user->bio != "") {
            $count += 2;
        }
        if ($user->self_intro != "") {
            $count++;
        }
        if ($user->educationExps()->count() > 0) {
            $count += 2;
        }
        if ($user->specializations()->count() > 0) {
            $count += 3;
        }
        $progress = ceil(($count / 10) * 100);


        return view('profile.edit', compact('user', 'settings', 'progress'));
    }

    /**
     * Show all subscribed user
     */
    public function follow($url_name) {
        $user = User::findUrlName($url_name);

        return view('profile.follow', compact('user'));
    }

    /**
     * Show all user follower's
     *
     * @param $url_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function follower($url_name) {
        $user = User::findUrlName($url_name);

        return view('profile.follower', compact('user'));
    }

    /**
     * Show all user questions
     *
     * @param $url_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function question($url_name) {
        $user = User::findUrlName($url_name);

        return view('profile.question', compact('user'));
    }

    /**
     * Answer ajax call to get user's questions
     *
     * @param $url_name
     * @param Request $request
     *
     * @return array(json)
     */
    public function postQuestion($url_name, Request $request) {
        $user = User::findUrlName($url_name);

        $questions = $user->questions;

        // get page parameters
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $pages = ceil($questions->count() / $itemInPage);

        $results = [];
        foreach ($questions->forPage($page, $itemInPage) as $question) {
            array_push($results, [
                'id' => $question->id,
                'title' => $question->title,
                'numAnswer' => $question->answers()->count(),
                'visit' => $question->hit->total,
                'numSubscriber' => $question->subscribers()->count(),
            ]);
        }

        return [
            'data' => $results,
            'pages' => $pages
        ];

    }

    /**
     * Answer ajax call to get all user's answer
     *
     * @param $url_name
     * @param Request $request
     * @return array
     */
    public function postAnswer($url_name, Request $request) {
        $user = User::findUrlName($url_name);
        $answers = $user->answers;
        // get page parameters
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;

        $groupByQuestion = $answers->groupBy(function($item, $key) {
            return $item->question->id;
        });

        // calculate total page
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
                    'vote_down_class' => $vote_down_class,
                    'canVote' => $answer->owner->canAnswerVoteBy($user)
                ]);
            }
            $arr['answers'] = $answers_arr;

            array_push($results, $arr);
        }

        return [
            'data' => $results,
            'pages' => $pages
        ];
    }

    /**
     * Show all user answers
     *
     * @param $url_name
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function answer($url_name) {
        $user = User::findUrlName($url_name);

        return view('profile.answer', compact('user'));
    }

    /**
     * Answer ajax call
     * Show all user subscribed users
     *
     * @param $url_name
     * @param Request $request
     * @return array
     */
    public function postFollowFollower($url_name, Request $request) {
        $user = User::findUrlName($url_name);

        $auth_user = Auth::user();

        $users = $user->subscribe->users;
        switch ($request->get('type')) {
            case 'follow' :
                $users = $user->subscribe->users;
                break;
            case 'follower' :
                $users = $user->subscribers->map(function($subscribe) {
                    return $subscribe->owner;
                });
        }

        // get page parameters
        $page = $request->exists('page') ? $request->get('page') : 1;
        $itemInPage = $request->exists('itemInPage') ? $request->get('itemInPage') : $this->itemInPage;
        $pages = ceil($users->count() / $itemInPage);

        $results = [];
        foreach ($users->forPage($page, $itemInPage) as $s_user) {
            array_push($results, [
                'id' => $s_user->id,
                'name' => $s_user->name,
                'bio' => $s_user->bio,
                'numAnswer' => $s_user->answers()->count(),
                'numSubscriber' => $s_user->subscribers()->count(),
                'isSubscribe' => $auth_user->subscribe->checkHasSubscribed($s_user->id, 'user'),
                'url_name' => $s_user->url_name,
                'img' => DImage($s_user->settings->profile_pic_id, 50, 50),
                'canSubscribe' => $s_user->canSubscribedBy($auth_user)
            ]);
        }

        return [
            'data' => $results,
            'pages' => $pages
        ];
        
    }

    /**
     * Update the specified resource in storage.
     * This is logic to response AJAX request.
     *
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $settings = $user->settings;
        
        switch ($request->get('type')) {
            case 'education':
                $educationExp = EducationExp::findOrCreate($request->get('institution'), $request->get('major'));
                // prevent duplicate saving
                $user->educationExps()->detach($educationExp->id);
                $user->educationExps()->attach($educationExp->id);
                return [
                    'name' => $educationExp->full_name,
                    'id' => $educationExp->id,
                    'status' => true
                ];
            case 'job':
                $job = Job::findOrCreate($request->get('organization'), $request->get('designation'));
                // prevent duplicate saving
                $user->jobs()->detach($job->id);
                $user->jobs()->attach($job->id);
                return [
                    'name' => $job->full_name,
                    'id' => $job->id,
                    'status' => true
                ];
            case 'specialization':
                $results = [];
                foreach ($request->get('specializations') as $topic_id) {
                    // detach first to prevent multiple saving
                    $user->specializations()->detach($topic_id);
                    $user->specializations()->attach($topic_id);
                    // find topic
                    $topic = Topic::findOrFail($topic_id);
                    array_push($results, [
                        'id' => $topic->id,
                        'name' => $topic->name,
                        'img' => DImage($topic->avatar_img_id, 40, 40),
                    ]);
                }
                return $results;
            case 'sex':
                $user->update(['sex' => $request->get('sex')]);
                return 1;
            case 'facebook':
                if ($request->get('facebook') == 'Yes') {
                    $settings->update(['display_facebook' => true]);
                }
                else {
                    $settings->update(['display_facebook' => false]);   
                }
                return [
                    'facebook' => $settings->display_facebook
                ];
            case 'email':
                if ($request->get('email') == 'Yes') {
                    $settings->update(['display_email' => true]);
                }
                else {
                    $settings->update(['display_email' => false]);   
                }
                return [
                    'email' => $settings->display_email
                ];
            case 'bio':
                $user->update(['bio' => $request->get('bio')]);
                return [
                    'bio' => $user->bio
                ];
            case 'intro':
                $user->update(['self_intro' => $request->get('intro')]);
                return [
                    'self_intro' => $user->self_intro
                ];
            default:
                break;
        }

        abort(401);

    }

    /**
     * delete some field about the user
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        switch ($request->get('type')) {
            case 'education':
                $user->educationExps()->detach($request->get('educationExp_id'));
                return response(200);
            case 'job':
                $user->jobs()->detach($request->get('job_id'));
                return response(200);
            case 'specialization':
                $user->specializations()->detach($request->get('specialization_id'));
                return response(200);
            default:
                break;
        }

        abort(401);
    }


    /**
     * Answer ajax request to upload user profile pic
     *
     * @param Request $request
     * @return array
     */
    public function upload(Request $request) {
        $this->validate($request, [
            'croppedImage' => 'required|mimes:jpeg,bmp,png'
        ]);

        $img = $request->file('croppedImage');
        $user = Auth::user();

        // generate file name
        $filename = 'profile-' . $user->id . '.' . $img->extension();
        $relative_fullpath = 'images/user/' . $user->id . '/' . $filename;

        // check if user folder exist
        if (!File::exists(base_path('images/user/' . $user->id))) {
            File::makeDirectory(base_path('images/user/' . $user->id), $mode = 0777, true, true);
        }
        
        // resize the image
        $img_resize = IImage::make($img->getRealPath());
        // resize the image to a width of 1024 and constrain aspect ratio (auto height)
        $img_resize->resize(1024, null, function ($constraint) {
            $constraint->aspectRatio();
        });


        // delete old file
        // exists than delete
        if (Image::where('id', '=', $user->settings->profile_pic_id)->exists()) {
            $old_img = Image::findOrFail($user->settings->profile_pic_id);
            $old_img->deleteAll();
        }


        // create new image instance
        $img_database = Image::create([
            'path' => $relative_fullpath,
            'width' => 800,
            'height' => $img_resize->height()
        ]);
        $img_database->save();

        // update reference id
        $img_database->reference_id = $img_database->id;
        $img_database->save();

        // update new user pic id
        $settings = $user->settings;
        $settings->profile_pic_id = $img_database->id;
        $settings->save();

        // save new image
        $img_resize->save(base_path($relative_fullpath), 50); // medium quality

        return [
            'status' => 'true'
        ];
    }

}
