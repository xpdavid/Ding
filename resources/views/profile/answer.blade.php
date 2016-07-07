@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])


    <div class="userHome_layoutDiv" id="answer" data-topic="{{ $topic }}">
        <div class="userHome_layoutDivHead">
            @if ($topic)
                <a href="/people/{{$user->url_name}}" class="float-left">{{ $user->name }}</a> 's Answers under topic:
                <a href="/topic/{{ $topic }}">{{ App\Topic::findOrFail($topic)->name }}</a>
            @else
                <a href="/people/{{$user->url_name}}" class="float-left">{{ $user->name }}</a> 's Answers
            @endif
            <a href="/people/{{$user->url_name}}" class="float-right">&lt;&lt; Back to the index</a>
        </div>
        <div class="userHome_layoutDivContent profile_bottom_content" id="answer_content">

        </div>
        <div class="text-center" id="answer_nav">

        </div>
    </div>

    @include('partials._bookmark_model')

@endsection

@section('side')
    @include('partials._user_home_sidebar', ['user' => $user])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showUserAnswerPage('answer', 8, '{{ $user->url_name }}', 1, false);
        });
    </script>
@endsection