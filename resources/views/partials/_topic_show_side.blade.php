@if($topic->isClosed())
    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="well">
                The topic is closed for some reason:<br>
                <strong>{{ $topic->closeReason() }}</strong>
            </div>
        </div>
    </div>
@endif
<div class="sideBar_section">
    <div class="sideBar_sectionItem">
        <div class="clearfix">
                @if(Auth::user()->subscribe->checkHasSubscribed($topic->id, 'topic'))
                    <button type="button" class="btn btn-warning"
                            onclick="topic_show_subscribe(this, '{{ $topic->id }}')">
                        Unsubscribe</button>
                @else
                    <button type="button" class="btn btn-success"
                            onclick="topic_show_subscribe(this, '{{ $topic->id }}')"
                    {{ $topic->isClosed() ? 'disabled' : ''}}>
                        Subscribe</button>
                @endif
            <div class="float-right margin-top">{{ $topic->subscribers()->count() }} <span class="font-black">People Subscribe</span> </div>
        </div>

        <div class="margin-top">
            <a href="/topic/{{ $topic->id }}/organization">Topic Organization Tree</a>
            <span>•</span>
            @if(Auth::user()->operation(12))
                <a href="/topic/{{ $topic->id }}/edit">Edit Topic</a>
                <span>•</span>
            @endif
            <a href="/topic/{{ $topic->id }}/log" class="font-greyLight">Show topic histories</a>
        </div>
    </div>
</div>

<div class="sideBar_section">
    <div class="sideBar_sectionItem">
        <div class="sideBar_sectionItem">
            <h4>Description</h4>
            <p>{!! nl2br($topic->description) !!}</p>
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

