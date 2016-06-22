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
                <li><a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a></li>
                <li>Organization Tree</li>
                <li><a href="/topic/{{ $topic->id }}/edit">Edit</a></li>
            </ul>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="topic_manage_item clearfix">
        <div class="topic_manage_title">
            <h5>Parent Topics</h5>
        </div>
        <div class="topic_manage_content margin-top">
            {!! $parent_tree !!}
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="topic_manage_item clearfix">
        <div class="topic_manage_title">
            <h5>SubTopics</h5>
        </div>
        <div class="topic_manage_content">
            <ul class="topic_organization_list">
                <li>
                    <a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a>
                    <ul id="subtopics_{{ $topic->id }}">
                        @foreach($topic->subtopics as $subtopic)
                            <li><a href="/topic/{{ $subtopic->id }}">{{ $subtopic->name }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
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
                <a href="/topic/{{ $topic->id }}/organization">Topic Organization Tree</a>
                <span>•</span>
                <a href="/topic/{{ $topic->id }}/edit">Edit Topic</a>
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

@endsection