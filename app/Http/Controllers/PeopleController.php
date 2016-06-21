<?php

namespace App\Http\Controllers;

use App\User;
use App\EducationExp;
use App\Job;
use App\Specialization;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class PeopleController extends Controller
{
    /**
     * PeopleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'show',
        ]]);
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
        $profile = $user->userProfile;
        return view('profile.edit', compact('user', 'profile'));
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
        $profile = $user->userProfile;
        Log::info($request->get('job'));
        switch ($request->get('type')) {
            case 'education':
                $educationExp = EducationExp::findOrCreate($request->get('institution'), $request->get('major'));
                $user->educationExps()->save($educationExp);
                return ['educationExp_id' => $educationExp->id];
            case 'job':
                $job = Job::findOrCreate($request->get('job'));
                $user->jobs()->save($job);
                return ['job_id' => $job->id];
            case 'specialization':
                $specialization = Specialization::findOrCreate($request->get('specialization'));
                $user->specializations()->save($specialization);
                return ['specialization_id' => $specialization->id];
            case 'sex':
                $profile->update(['sex' => $request->get('sex')]);
                return 1;
            case 'facebook':
                if ($request->get('facebook') == 'Yes') {
                    $profile->update(['facebook' => true]);
                }
                else {
                    $profile->update(['facebook' => false]);   
                }
                return 1;
            case 'email':
                if ($request->get('email') == 'Yes') {
                    $profile->update(['email' => true]);
                }
                else {
                    $profile->update(['email' => false]);   
                }
                return 1;
            case 'bio':
                $profile->update(['bio' => $request->get('bio')]);
                return 1;
            case 'intro':
                $profile->update(['description' => $request->get('intro')]);
                return 1;
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

}
