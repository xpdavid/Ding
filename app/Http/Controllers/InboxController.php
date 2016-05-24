<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Message;
use App\Conversation;
use App\Http\Requests;
use Illuminate\Http\Request;

/**
 * Class InboxController
 *
 * This is a class to control the behavior in inbox view
 *
 * @package App\Http\Controllers
 */
class InboxController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display conversations which belong to users
     *
     * Get the current user and pass all the conversations belong to the user to the view
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $conversations = $user->conversations;
        return view('inbox.index', compact('conversations'));
    }

    /**
     * Store a newly created conversation in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $current_user = Auth::user();
        // Every store will create a new conversation
        $conversation = Conversation::create(['can_reply' => true]);

        // a message belong to a conversation
        $message = Message::create($request->all());
        $conversation->messages()->save($message);

        // current user can have many conversations
        $conversation->users()->save($current_user);
        // current user send the message
        $current_user->sentMessages()->save($message);

        // participators have many unread message
        // participators have many conversation
        $users_id = $request->get('users');
        foreach ($users_id as $user_id) {
            if ($user_id == $current_user->id) continue;
            $user = User::findOrFail($user_id);
            $message->unreadUsers()->save($user);
            $conversation->users()->save($user);
        }



        return redirect(route('inbox.index'));
    }

    /**
     * Display the specified conversations.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $conversation = Conversation::findOrFail($id);
        return view('inbox.show', compact('conversation'));
    }


    /**
     * Store a newly created message to a conversation
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $current_user = Auth::user();

        // because there is only one field, we can validate here
        $this->validate($request, [
            'reply' => 'required'
        ]);

        // create message object
        $message = Message::create(['content' => $request->get('reply')]);
        // the message is send by current user
        $current_user->sentMessages()->save($message);

        // set relationship to current conversation
        $conversation = Conversation::findOrFail($id);
        $conversation->messages()->save($message);

        return redirect(route('inbox.show', $id));
    }

    /**
     * Remove a conversation from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    
}
