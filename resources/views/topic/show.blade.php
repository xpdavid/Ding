@extends('layouts.topic')

@section('left')
    <div class="media">
        <div class="media-left">
            <a href="#">
                <img class="media-object topic_avatar" src="..." alt="...">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{{ $topic->name }}</h4>
            <ul class="topic_category clearfix">
                <li><a href="#">Highlight</a></li>
                <li><a href="#">Recommend</a></li>
                <li><a href="#">Wait For Answers</a></li>
            </ul>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="clearfix">
        <div class="float-right">
            @if( $sorted == 'created')
                <a href="/topic/{{ $topic->id }}">Sort by Rate</a> / Sort by Date
            @else
                Sort by Rate / <a href="/topic/{{ $topic->id }}?sorted=created">Sort by Date</a>
            @endif
        </div>
    </div>

    <hr class="small_hrLight">

    <div id="topic_questions">

    </div>


    <div>
        <button type="button" class="btn btn-default" id="topics_more_button" onclick="getMoreTopicQuestion()">More</button>
    </div>

@endsection



@section('right')


@endsection

@section('javascript')
<script type="text/javascript">
    getTopicQuestions('{{ $topic->id }}','highlight', 1, 3, '{{ $sorted }}')
</script>
@endsection