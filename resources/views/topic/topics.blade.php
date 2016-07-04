@extends('layouts.topic')


@section('left')
    <div class="clearfix">
        <a href="#" class="float-left font"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span><span>Topics</span></a>
        <div class="float-right font-grey">{{ Auth::user()->subscribe->topics()->count() }} topic(s) subscribed</div>
    </div>
    <hr class="small_hr">
    <div class="topics_topicTag" id="top_parent_topics">
        @foreach($top_parent_topics as $key => $top_topic)
            @if($key == 0)
                <button class="btn btn-primary active" onclick="getFirstLevelChildTopic({{ $top_topic->id }}, 1, this)">{{ $top_topic->name }}</button>
            @else
                <button class="btn btn-primary" onclick="getFirstLevelChildTopic({{ $top_topic->id }}, 1, this)">{{ $top_topic->name }}</button>
            @endif
        @endforeach
    </div>
    <hr class="small_hrLight">

    <div id="sub_topics">

    </div>


    <div>
        <button type="button" class="btn btn-default" id="topics_more_button" onclick="getMoreTopics()">More</button>
    </div>


@endsection



@section('right')
        <div class="sideBar_section noborder">
            <div class="sideBar_item">
                <div class="">
                    <h4>Popular Topics</h4>
                </div>
                @foreach($popular_topics as $popular_topic)
                    <div class="media topics_item">
                        <div class="media-left">
                            <a href="#">
                                <img class="media-object topics_item_image img-rounded" src="{{ DImage($popular_topic->avatar_img_id, 40, 40) }}" alt="{{ $popular_topic->name }}">
                            </a>
                        </div>
                        <div class="media-body topics_more">
                            <div class="clearfix">
                                <div class="float-left topics_name">
                                    <a href="/topic/{{ $popular_topic->id }}">{{ $popular_topic->name }}</a>
                                </div>
                                @if(Auth::user()->subscribe->checkHasSubscribed($popular_topic->id, 'topic'))
                                    <a class="float-right topics_subscribe active"
                                       href="#"
                                       onclick="topics_subscribe(event, this, '{{ $popular_topic->id }}')">
                                        <span class="glyphicon glyphicon-plus noneDisplay" aria-hidden="true"></span>
                                        <span>Unsubscribe</span>
                                    </a>
                                @else
                                    <a class="float-right topics_subscribe"
                                       href="#"
                                       onclick="topics_subscribe(event, this, '{{ $popular_topic->id }}')">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        <span>Subscribe</span>
                                    </a>
                                @endif
                            </div>
                            <p class="font-black">{{ $popular_topic->subscribers()->count() }} subscribe</p>
                        </div>
                        <div class="topics_item_hots">
                            @foreach($popular_topic->questions->take(1) as $question)
                                <a href="/question/{{ $question->id }}">{{ $question->title }}</a>
                            @endforeach
                        </div>
                    </div>
                @endforeach

            </div>
        </div>


@endsection


@section('javascript')
<script type="text/javascript">
    @if(count($top_parent_topics) > 0)
        $(function() {
            getFirstLevelChildTopic({{ $top_parent_topics[0]->id }}, 1);
        })
    @endif
</script>
@endsection