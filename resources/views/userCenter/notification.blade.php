@extends('layouts.userCenter')


@section('content')

    <h4 class="font-black">All Notification</h4>
    <hr class="small_hr">

    @foreach($notificationsByDay as $date => $notifications)
        <div class="notice_day">
            <h5>{{ $date }}</h5>
            <div class="notice_dayItem topborder">
                @foreach($notifications as $notification)
                    <div>
                        {!! $notification->renderedContent !!}
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <button class="btn btn-default notice_button" type="submit">More..</button>

@endsection