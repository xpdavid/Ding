<?php

namespace App\Http\Controllers;

use App\User;
use App\EducationExp;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('profile.edit', compact('user'));
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
        switch ($request->get('type')) {
            case 'education':
                $educationExp = EducationExp::findOrCreate($request->get('institution'), $request->get('major'));
                $user->educationExps()->save($educationExp);
                return ['educationExp_id' => $educationExp->id];
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
            default:
                break;
        }

        abort(401);
    }

    public function autocomplete(Request $request) {
        $this->validate($request, [
            'query' => 'required',
            'max_match' => 'required|integer',
            'use_similar' => 'required|boolean'
        ]);

        $query = $request->get('query'); // the keyword
        $max_matches = $request->get('max_match'); // the maximum match
        $use_similar = $request->get('use_similar'); // whether use similar

        // leave the max_matches as numbers only
        $max_matches = ($max_matches != "0") ? $max_matches : '0';

        // leave the user_similar as true or false only
        $use_similar = ($use_similar == "1") ? true : false;

        $users = $use_similar ? User::similarMatch($query) : User::noneSimilarMatch($query);
        $users = $users->take($max_matches)->get();

        return $users->lists('name', 'id')->all();
    }

}
