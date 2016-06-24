<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use App\Http\Requests;

class UserCenterController extends Controller
{
    /**
     * Show notification page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function notification() {
        $user = Auth::user();

        $notifications = $user->notifications;

        $notificationsByDay = $notifications->groupBy(function ($notification) {
            return Carbon::parse($notification->created_at)->format('d/m/Y');
        });

        return view('userCenter.notification', compact('notificationsByDay'));
    }
}
