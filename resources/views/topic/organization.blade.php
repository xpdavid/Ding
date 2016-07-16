@extends('layouts.topic')

@section('left')
    <div class="media margin-top topic_item">
        <div class="media-left">
            <a href="#">
                <img class="media-object topic_avatar" src="{{ DImage($topic->avatar_img_id, 40, 40) }}" alt="{{ $topic->name }}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{{ $topic->name }}</h4>
            <ul class="topic_category clearfix">
                <li><a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a></li>
                <li>Organization Tree</li>
                @if(Auth::user()->operation(12))
                    <li><a href="/topic/{{ $topic->id }}/edit">Edit</a></li>
                @endif
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
    @include('partials._topic_show_side', ['topic' => $topic, 'parent_topics' => $parent_topics, 'subtopics' => $subtopics])
@endsection