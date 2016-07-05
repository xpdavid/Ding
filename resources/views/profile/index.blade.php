@extends('layouts.profile')

@section('content')
    @include('partials._profile_card', ['user' => $user])

    <!-- Specialization -->
    @if($user->specializations()->count() > 0)
        <div class="userHome_layoutDiv">
            <div class="userHome_layoutDivHead">
                Specializations
                <a href=""><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
            </div>
            <div class="userHome_layoutDivContent">
                <div class="profile_bottom_content">
                    @foreach($user->specializations->chunk(2) as $topics)
                        <div class="row">
                            @foreach($topics as $topic)
                                <div class="col-sm-6">
                                    <div class="media clickable" id="specialization_{{ $topic->id }}"
                                        onclick="window.location.replace('{{ route('people.answer', [$user->url_name, 'topic' => $topic->id])}}')"
                                    >
                                        <div class="media-left">
                                            <img class="media-object img-circle" src="{{ DImage($topic->avatar_img_id, 50, 50) }}" alt="{{ $topic->name }}">
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading">{{ $topic->name }}</h4>
                                            <div class="topic_description_line font-greyLight">
                                                {{ $topic->description }}
                                            </div>
                                            <div class="font-greyLight">
                                                <span class="glyphicon glyphicon-heart"></span>
                                                <span class="space-right">{{ $user->votesInTopic($topic->id) }}</span>
                                                <span class="glyphicon glyphicon-comment"></span>
                                                <span class="space-right">{{ $user->answersInTopic($topic->id)->count() }}</span>
                                            </div>
                                            <div class="profile_right_arrow noneDisplay">
                                                <a href="#">
                                                    <span class="glyphicon glyphicon-chevron-right font-greyLight"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- My Question -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            Questions
            <a href="{{ route('people.question', $user->url_name) }}"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
        </div>
        <div class="userHome_layoutDivContent profile_bottom_content">
            <div id="question_content">

            </div>
            <br>
            <button class="btn btn-default btn-sm btn-block" id="question_button"
                    onclick="profileGetMoreQuestion('{{ $user->url_name }}')">More</button>
        </div>
    </div>


    <!-- My Answers -->
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            Answers
            <a href="{{ route('people.answer', $user->url_name) }}"><span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span></a>
        </div>
        <div class="userHome_layoutDivContent profile_bottom_content">
            <div id="answer_content">

            </div>
            <button class="btn btn-default btn-sm btn-block" id="answer_button"
                    onclick="profileGetMoreAnswer('{{ $user->url_name }}')">More</button>
        </div>
    </div>

    {{--user updates--}}
    <div class="userHome_layoutDiv">
        <div class="userHome_layoutDivHead">
            Updates
        </div>
        <div class="userHome_layoutDivContent profile_bottom_content">
            <div id="updates_content">

            </div>
            <button class="btn btn-default btn-sm btn-block" id="updates_button"
                    onclick="getMoreUpdates('{{ $user->url_name }}')">More</button>
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
            hoverShowArrow('specialization', 'profile_right_arrow')

        });
    </script>
@endsection