@extends('layouts.userCenter')


@section('content')
    <div class="clearfix">
        <a href="#" class="float-left"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>News</a>
        <a class="float-right"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span>Settings</a>
    </div>
    <hr class="small_hr">

    <div id="user_home_question">

    </div>

    <button class="btn btn-default btn-block" type="button" id="home_question_more" onclick="getHomeQuestion()">More</button>

@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            getHomeQuestion();
            userHome_vote_button_trigger();
        });
    </script>
@endsection