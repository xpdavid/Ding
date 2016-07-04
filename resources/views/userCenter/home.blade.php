@extends('layouts.userCenter')


@section('content')
    <div class="clearfix">
        <a href="#" class="float-left"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>News</a>
        <a href="/settings/blocking" class="float-right"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>Settings</a>
    </div>
    <hr class="small_hr">

    <div id="home">

    </div>

    <button class="btn btn-default btn-block" type="button" id="home_more" onclick="genericGet('home');">More</button>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            genericGet('home');
            userHome_vote_button_trigger();
        });
    </script>
@endsection