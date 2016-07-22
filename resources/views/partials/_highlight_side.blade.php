
<div class="clearfix">
    <div class="float-left">Hot Topics</div>
    <div class="float-right font-grey"><a href="#">More &gt;&gt;</a></div>
</div>

<hr class="small_hrLight">

<div class="sideBar_section">
    @foreach(App\Topic::getHotTopics()->take(8) as $topic)
        <div class="media topics_item">
            <div class="media-left">
                <a href="#">
                    <img class="media-object topics_item_image img-rounded" src="{{ DImage($topic->avatar_img_id, 40, 40) }}" alt="{{ $topic->name }}">
                </a>
            </div>
            <div class="media-body topics_more">
                <div class="clearfix">
                    <div class="float-left topics_name">
                        <a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a>
                    </div>
                    @if (Auth::user())
                        @if(Auth::user()->subscribe->checkHasSubscribed($topic->id, 'topic'))
                            <a class="float-right topics_subscribe active"
                               href="#"
                               onclick="topics_subscribe(event, this, '{{ $topic->id }}')">
                                <span class="glyphicon glyphicon-plus noneDisplay" aria-hidden="true"></span>
                                <span>Unsubscribe</span>
                            </a>
                        @else
                            <a class="float-right topics_subscribe"
                               href="#"
                               onclick="topics_subscribe(event, this, '{{ $topic->id }}')">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                <span>Subscribe</span>
                            </a>
                        @endif
                    @endif
                    <p class="font-black">{{ $topic->subscribers()->count() }} subscribe</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

{{--<div class="sideBar_section">--}}
    {{--<div class="clearfix">--}}
        {{--<div class="float-left">Hot Bookmarks</div>--}}
        {{--<div class="float-right font-grey"><a href="#">Try Again? &gt;&gt;</a></div>--}}
    {{--</div>--}}
{{--</div>--}}

{{--<div class="sideBar_section noborder">--}}
    {{--<div class="clearfix sideBar_sectionItem">--}}
        {{--<img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">--}}
        {{--<a href="#">Food Science</a>--}}
        {{--<div class="font-grey">3842 substribe</div>--}}
    {{--</div>--}}
    {{--<div><a href="#">Does't alcohal harm?</a></div>--}}
    {{--<div class="clearfix sideBar_sectionItem">--}}
        {{--<img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">--}}
        {{--<a href="#">Food Science</a>--}}
        {{--<div class="font-grey">3842 substribe</div>--}}
    {{--</div>--}}
{{--</div>--}}