<?php

namespace App\Http\Controllers;

use App\Notification;
use Auth;
use App\Http\Requests;
use Illuminate\Http\Request;


class NotificationController extends Controller
{
    /**
     * PeopleController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Response ajax request to do notification operation
     *
     * @param Request $request
     * @return array(json)
     */
    public function operation(Request $request) {
        // get necessary parameters
        $notification = Notification::findOrFail($request->get('id'));
        $user = Auth::user();

        if ($notification->owner->id != $user->id) {
            // you cannot change other's notification
            abort(401);
        }

        switch ($request->get('op')) {
            case 'read' :
                $notification->read();
                break;
        }

        return [
            'status' => true
        ];
    }
}
