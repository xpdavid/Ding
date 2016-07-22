@extends('layouts.topic')



@section('left')
    <div class="clearfix">
        <a href="#" class="float-left font"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span><span>Subscribed Topic(s)</span></a>
        <div class="float-right font-grey">{{ Auth::user()->subscribe->topics()->count() }} topic(s) subscribed</div>
    </div>
    <hr class="small_hr">
    <div class="topics_topicTag" id="subscribe_topics">
        @foreach($subscribe_topics as $key => $subscribe_topic)
                <button class="btn
                 {{ $key == 0 ? 'active' : '' }}
                {{ $subscribe_topic->isClosed() ? 'btn-warning topics_topicTag_closed' : 'btn-primary' }}"
                        onclick="topic_show_get_questions(this, '{{ $subscribe_topic->id }}', 'created')"
                @if($subscribe_topic->isClosed())
                        data-toggle="tooltip" data-placement="top" title="Closed Topic"
                @endif
                >{{ $subscribe_topic->name }}</button>
        @endforeach
    </div>

    <hr class="small_hrLight">

    <div class="clearfix">
        <div class="float-right">
            <a href="#" onclick="topic_show_sort(this, 'default')">Sort by Default</a> /
            <a href="#" class="font-black" onclick="topic_show_sort(this, 'created')">Sort by Date</a>
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
            <div class="jumbotron text-center">
                <p>Feeling Lucky?</p>
                <p><a class="btn btn-primary" href="/topics" role="button">Try more topics here</a></p>
            </div>
        </div>
    </div>

    <div class="sideBar_section noborder">
        <div class="sideBar_sectionItem">
            <h5>Other people have also subscribed:
            </h5>
            <div class="topic_home_topic_list">
                @foreach($other_topics as $other_topic)
                    <div class="topic_home_topic_list_item clearfix">
                        <img class="topics_item_image float-left" src="{{ DImage($other_topic->avatar_img_id, 40, 40) }}" alt="{{ $other_topic->name }}">
                        <div class="topic_home_topic_list_item_text clearfix">
                            <div class="float-left">
                                <strong><a href="/topic/{{ $other_topic->id }}">{{ $other_topic->name }}</a></strong>
                            </div>
                            <div class="float-right">
                                @if(Auth::user()->subscribe->checkHasSubscribed($other_topic->id, 'topic'))
                                    <a class="float-right topics_subscribe active"
                                       href="#"
                                       onclick="topics_subscribe(event, this, '{{ $other_topic->id }}')">
                                        <span class="glyphicon glyphicon-plus noneDisplay" aria-hidden="true"></span>
                                        <span>Unsubscribe</span>
                                    </a>
                                @else
                                    <a class="float-right topics_subscribe"
                                       href="#"
                                       onclick="topics_subscribe(event, this, '{{ $other_topic->id }}')">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        <span>Subscribe</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection


@section('javascript')
<script type="text/javascript">
    @if(count($subscribe_topics) > 0)
        $(function() {
            getTopicQuestions('{{ $subscribe_topics[0]->id }}','highlight', 1, 10, 'created')
            // open tooltip option
            $('[data-toggle="tooltip"]').tooltip({container: 'body'});
        });
    @endif
</script>
@endsection