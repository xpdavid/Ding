<?php

namespace App\Http\Controllers;

use App\Image;
use App\User;
use App\EducationExp;
use App\Job;
use App\Topic;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use IImage;
use File;
use Hash;
use Log;

class PeopleController extends Controller
{
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
     * Update the specified resource in storage.
     * This is logic to response AJAX request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $url_name
     * @return \Illuminate\Http\Response
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
     * Remove the specified resource from storage.
     *
     * @param  string $url_name
     * @return \Illuminate\Http\Response
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
