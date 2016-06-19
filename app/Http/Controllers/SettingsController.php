<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Blocking;

use App\Http\Requests;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $settings = $user->settings;
        $blockings = $user->blockings;
        return view('settings.index', compact('user', 'settings', 'blockings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $user = Auth::user();
        $settings = $user->settings;
        $blockings = $user->blockings;

        if ($request->name){
            $user->update(['name' => $request->name]);
        }

        if ($request->personal_domain != ''){
            $settings->update(['personal_domain_modified' => true]);
            $settings->update(['personal_domain' => $request->personal_domain]);
        }

        if ($request->new_password != ''){
            if ( Hash::check($request->old_password, $user->password)){
                $user->update([
                    'password' => Hash::make($request->new_password),
                    ]);
            }
            else{
                return 'Wrong password';
            }
        }

        if ($request->receiving_messages){
            $settings->update([
                'receiving_messages' => $request->receiving_messages,
                'receiving_invitations' => $request->receiving_invitations,
                'receiving_updates' => $request->receiving_updates,
                'receiving_replies' => $request->receiving_replies,
                'receiving_votings' => $request->receiving_votings,
                'receiving_reply_votings' => $request->receiving_reply_votings,
                'receiving_subscriptions' => $request->receiving_subscriptions
                ]);
        }

        if ($request->email_to_be_blocked != ''){
            $results = DB::select('select id from users where email = ?', [$request->email_to_be_blocked]);
            if ($results){
                $blocking = new Blocking([
                    'user_id' => $user->id,
                    'blocked_id' => $results[0]->id
                    ]);
                $blocking->save();
            }
            else{
                return 'User Not Found';
            }
        }

        return back();
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
}
