@extends('layouts.userCenter')


@section('content')

    <h4 class="font-black">All Notification</h4>
    <hr class="small_hr">

    <div id="notification">

    </div>


    <button class="btn btn-default btn-block" type="button" id="notification_more" onclick="getHomeQuestion()">More..</button>

@endsection

@section('javascript')
<script type="text/javascript">
    $(function() {
        getOneDayNotification();
    });
</script>
@endsection