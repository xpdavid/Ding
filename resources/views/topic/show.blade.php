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
                @if($type == 'recommend')
                    <li><a href="{{ action('TopicController@show', [ $topic->id, 'type' => 'highlight']) }}">Highlight</a></li>
                    <li>Recommend</li>
                    <li><a href="{{ action('TopicController@show', [ $topic->id, 'type' => 'wait_for_answer']) }}">Wait For Answers</a></li>
                @endif

                @if($type == 'wait_for_answer')
                    <li><a href="{{ action('TopicController@show', [ $topic->id, 'type' => 'highlight']) }}">Highlight</a></li>
                    <li><a href="{{ action('TopicController@show', [ $topic->id, 'type' => 'recommend']) }}">Recommend</a></li>
                    <li>Wait For Answers</li>
                @endif

                @if($type == 'highlight')
                    <li>Highlight</li>
                    <li><a href="{{ action('TopicController@show', [ $topic->id, 'type' => 'recommend']) }}">Recommend</a></li>
                    <li><a href="{{ action('TopicController@show', [ $topic->id, 'type' => 'wait_for_answer']) }}">Wait For Answers</a></li>
                @endif
                </ul>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="clearfix">
        <div class="float-right">
            @if( $sorted == 'created')
                <a href="{{ action('TopicController@show', [ $topic->id, 'type' => $type, 'sorted' => 'default']) }}">Sort by Default</a> / Sort by Date
            @else
                Sort by Default / <a href="{{ action('TopicController@show', [ $topic->id, 'type' => $type, 'sorted' => 'created']) }}">Sort by Date</a>
            @endif
        </div>
    </div>

    <hr class="small_hrLight">

    <div id="topic_questions">

    </div>


    <div>
        <button type="button" class="btn btn-default" id="topics_more_button" onclick="getMoreTopicQuestion()">More</button>
    </div>

    @include('partials._show_comment_conversation')
@endsection



@section('right')
    @include('partials._topic_show_side', ['topic' => $topic, 'parent_topics' => $parent_topics, 'subtopics' => $subtopics])
@endsection

@section('javascript')
<script type="text/javascript">
    getTopicQuestions('{{ $topic->id }}','{{ $type }}', 1, 3, '{{ $sorted }}')
</script>
@endsection