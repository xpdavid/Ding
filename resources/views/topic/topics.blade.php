@extends('layouts.topic')


@section('left')
    <div class="clearfix">
        <a href="#" class="float-left font"><span class="glyphicon glyphicon glyphicon-th-large" aria-hidden="true"></span>Topics</a>
        <div class="float-right font-grey">7 topics subscribed</div>
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
            <div class="media topics_item">
                <div class="media-left">
                    <a href="#">
                        <img class="media-object topics_item_image" data-src="..." alt="Generic placeholder image" src="image/sample_icon.png">
                    </a>
                </div>
                <div class="media-body topics_more">
                    <div class="clearfix">
                        <div class="float-left topics_name"><a href="#">test</a></div>
                        <a class="float-right topics_subscribe" href="#"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Subscribe</a>
                    </div>
                    <p class="font-black"></p>
                    <p class="font-black">33323 subscribe</p>
                </div>
                <div class="topics_item_hots">
                    <a href="#">Looking to Start Your CS Career in the Bay Area? </a>
                    <a href="#">Looking to Start Your CS Career in the Bay Area? </a>
                    <a href="#">Looking to Start Your CS Career in the Bay Area? </a>
                </div>
            </div>
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