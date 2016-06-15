<?php

namespace App\Http\Controllers;

use App\EducationExp;
use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{
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
     * @param  string  $url_name
     * @return \Illuminate\Http\Response
     */
    public function edit($url_name)
    {
        $user = User::findUrlName($url_name);

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
    public function update(Request $request, $url_name)
    {
        $user = User::findUrlName($url_name);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }




    /**
     * Unused method
     */
    public function create()
    {
        //
    }

    /**
     * Unused method
     */
    public function index()
    {
        //
    }

    /**
     * Unused method
     */
    public function store(Request $request)
    {
        //
    }
}
