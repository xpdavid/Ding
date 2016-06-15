<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Requests;
use Illuminate\Http\Request;

class PeopleController extends Controller
{
    /**
     * Display the user homepage according to their customer url name
     *
     * @param  int  $url_name
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
     * @param  int  $url_name
     * @return \Illuminate\Http\Response
     */
    public function edit($url_name)
    {
        $user = User::findUrlName($url_name);

        return view('profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
