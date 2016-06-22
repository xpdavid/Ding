@extends('layouts.topic')

@section('left')
    <div class="media margin-top topic_item">
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
    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="clearfix">
                <button type="button" class="btn btn-success float-left">Subscribe</button>
                <div class="float-right margin-top">32424 <span class="font-black">People Subscribe</span> </div>
            </div>

            <div class="margin-top">
                <a href="#">Organization</a>
                <span>•</span>
                <a href="#">Edit</a>
            </div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="sideBar_sectionItem">
                <h4>Description</h4>
                <p>{{ $topic->description }}</p>
            </div>
        </div>
    </div>

    @if(count($parent_topics) > 0)
        <div class="sideBar_section">
            <div class="sideBar_sectionItem">
                <div class="sideBar_sectionItem">
                    <h4>Parent Topic</h4>
                    <div class="topics_topicTag">
                        @foreach($parent_topics as $parent_topic)
                            <a class="btn btn-primary" href="/topic/{{ $parent_topic->id }}">{{ $parent_topic->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(count($subtopics) > 0)
        <div class="sideBar_section">
            <div class="sideBar_sectionItem">
                <div class="sideBar_sectionItem">
                    <h4>SubTopic</h4>
                    <div class="topics_topicTag">
                        @foreach($subtopics as $subtopic)
                            <a class="btn btn-primary" href="/topic/{{ $subtopic->id }}">{{ $subtopic->name }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('partials._show_comment_conversation')

@endsection

@section('javascript')
<script type="text/javascript">
    getTopicQuestions('{{ $topic->id }}','highlight', 1, 3, '{{ $sorted }}')
</script>
@endsection