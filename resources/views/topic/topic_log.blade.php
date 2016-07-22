@extends('layouts.topic')

@section('left')
    <div class="media margin-top topic_item">
        <div class="media-left">
            <a href="#">
                <img class="media-object topic_avatar" src="{{ DImage($topic->avatar_img_id, 40, 40) }}" alt="{{ $topic->name }}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{{ $topic->name }}
                @if($topic->isClosed())
                    <span class="label label-warning">Closed</span>
                @endif
            </h4>
            <ul class="topic_category clearfix">
                <li><a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a></li>
                <li><a href="/topic/{{ $topic->id }}/organization">Organization Tree</a></li>
                <li><a href="/topic/{{ $topic->id }}/edit">Edit</a></li>
            </ul>
        </div>
    </div>

    <hr>
    <div class="clearfix">
        <div class="float-left">
            <a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a> editing logs.
        </div>
        <div class="float-right">
            <a href="/topic/{{ $topic->id }}">&lt;&lt; Back to the topic</a>
        </div>
    </div>
    <hr>

    {{--current answer log here--}}
    <div id="topic_log_content">
        <p class="text-center font-greyLight">This topic has no editing history.</p>
    </div>

    <div id="topic_log_nav" class="text-center">

    </div>
@endsection



@section('right')
    @include('partials._topic_show_side', ['topic' => $topic, 'parent_topics' => $parent_topics, 'subtopics' => $subtopics])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showTopicLogPage('topic_log', null, '{{ $topic->id }}', 1, null);
        });
    </script>

    {{--// js diff support--}}
    {!! Html::script('js/jsdiff/diff.min.js') !!}
    {!! Html::script('js/history.js') !!}
@endsection