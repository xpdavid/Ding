@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <!-- My Question -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            My Questions
            <a href="{{ route('people.question', $user->url_name) }}"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
        </div>
        <div class="userHome_layoutDivContent">
            <div id="question_content" class="profile_bottom_content">

            </div>
            <br>
            <button class="btn btn-default btn-sm btn-block" id="question_button"
                    onclick="profileGetMoreQuestion('{{ $user->url_name }}')">More</button>
            <br>
        </div>
    </div>


    <!-- My Answers -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            My Answers
            <a href="{{ route('people.answer', $user->url_name) }}"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
        </div>
        <div class="userHome_layoutDivContent">
            <div id="answer_content" class="profile_bottom_content">

            </div>
            <button class="btn btn-default btn-sm btn-block" id="answer_button"
                    onclick="profileGetMoreAnswer('{{ $user->url_name }}')">More</button>
            <br>
        </div>
    </div>

    {{--user updates--}}
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            Updates
        </div>
        <div class="userHome_layoutDivContent">
            <div id="updates_content" class="profile_bottom_content">

            </div>
            <button class="btn btn-default btn-sm btn-block" id="updates_button"
                    onclick="getMoreUpdates('{{ $user->url_name }}')">More</button>
            <br>
        </div>
    </div>



@endsection


@section('side')
    @include('partials._user_home_sidebar', ['user' => $user])
@endsection


@section('javascript')
    <script type="text/javascript">
        $(function() {
            profileGetMoreQuestion('{{ $user->url_name }}');
            profileGetMoreAnswer('{{ $user->url_name }}');
            getMoreUpdates('{{ $user->url_name }}');
        });
    </script>
@endsection