<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Http\Request;

class UserCenterController extends Controller
{
    /**
     * Show notification page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notification() {
        return view('userCenter.notification', compact('notificationsByDay'));
    }

    /**
     * Response ajax request to get notification item
     *
     * @param Request $request
     * @return array
     */
    public function postNotification(Request $request) {
        $user = Auth::user();
        $notifications = $user->notifications;
        $day = $request->get('day') ? $request->get('day') : 1;
        // group by date and sort by date
        $notificationsByDay = $notifications->groupBy(function ($notification) {
            return Carbon::parse($notification->updated_at)->format('d/m/Y');
        })->sort()->reverse()->forPage($day, 1);
        
        // format result
        $results = [];
        foreach ($notificationsByDay as $date => $notifications) {
            $results['date'] = $date;
            $partials = [];
            foreach ($notifications as $notification) {
                array_push($partials, [
                    'content' => $notification->renderedContent,
                    'type' => $notification->type
                ]);
            }
            $results['items'] = $partials;
        }

        return $results;
    }
}
