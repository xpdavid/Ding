<?php

namespace App\Http\Controllers;

use App\Notification;
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
     * InboxController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth_real');
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
        $users_id = $request->get('users');
        // check blocking
        foreach ($users_id as $user_id) {
            $to_user = User::findOrFail($user_id);
            if (!$current_user->canSendMessageTo($to_user)) {
                return redirect()
                    ->back()
                    ->withErrors(['There are some users who block you or some users set they only can receive message from users who they subscribe to.']);
            }
        }


        // Every store will create a new conversation
        $conversation = Conversation::create(['can_reply' => true]);

        // a message belong to a conversation
        $message = Message::create($request->all());
        $conversation->messages()->save($message);

        // current user can have many conversations
        $conversation->users()->save($current_user);
        // current user send the message
        $current_user->sentMessages()->save($message);
        
        // participators have many conversation
        foreach ($users_id as $user_id) {
            if ($user_id == $current_user->id) continue;
            $user = User::findOrFail($user_id);
            $conversation->users()->save($user);

            // send notification to participators with type 11 notification
            Notification::notification($user, 11, $current_user->id, $message->id);
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
        $user = Auth::user();
        $conversation = Conversation::findOrFail($id);
        if ($user->isInConversation($conversation)) {
            return view('inbox.show', compact('conversation'));
        } else {
            abort(401); // 401 forbidden
        }

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
        $conversation = Conversation::findOrFail($id);
        if (!$current_user->isInConversation($conversation)) {
            abort(401); // 401 forbidden
        }

        // because there is only one field, we can validate here
        $this->validate($request, [
            'reply' => 'required'
        ]);

        // create message object
        $message = Message::create(['content' => $request->get('reply')]);
        // the message is send by current user
        $current_user->sentMessages()->save($message);

        // set relationship to current conversation
        $conversation->messages()->save($message);

        // notification to participators
        foreach ($conversation->users as $user) {
            if ($current_user->id == $user->id) continue;
            // notify participators (type 11 notification)
            Notification::notification($user, 11, $current_user->id, $message->id);
        }

        return redirect(route('inbox.show', $id));
    }

    /**
     * Remove a user from a conversation.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $current_user = Auth::user();
        $conversation = Conversation::findOrFail($id);

        // we must ensure that the user is in the conversation
        if ($current_user->isInConversation($conversation)) {
            // detach the user from the conversation
            $conversation->users()->detach($current_user->id);

            // return sucess message
            return [
                'status' => 1
            ];
        } else {
            return [
                'status' => 0
            ];
        }
    }


}
