@extends('layouts.topic')

@section('left')
    @include('partials._crop_image_model', [
        'url' => '/topic/upload',
        'image' => DImage($topic->avatar_img_id),
        'id' => $topic->id,
        'type' => ''
    ])

    <div class="media margin-top topic_item">
        <div class="media-left">
            <a href="#">
                <img class="media-object topic_avatar img-rounded" src="{{ DImage($topic->avatar_img_id, 50, 50) }}" alt="{{ $topic->name }}">
            </a>
        </div>
        <div class="media-body">
            <h4 class="media-heading">{{ $topic->name }}</h4>
            <ul class="topic_category clearfix">
                <li><a href="/topic/{{ $topic->id }}">{{ $topic->name }}</a></li>
                <li><a href="/topic/{{ $topic->id }}/organization">Organization Tree</a></li>
                <li>Edit</li>
            </ul>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="topic_manage_item clearfix">
        <div class="topic_manage_title">
            <h5>Parent Topics</h5>
        </div>
        <div class="topic_manage_content">
            @if(count($parent_topics) > 0)
                <div class="topics_topicTag">
                    @foreach($parent_topics as $parent_topic)
                        <a class="btn btn-primary" href="/topic/{{ $parent_topic->id }}">{{ $parent_topic->name }}</a>
                    @endforeach
                </div>
            @endif
            <div id="add_parent_topic_trigger" class="add_topic_relation_button margin-top">
                <a href="#" onclick="toggle('add_parent_topic', event)"><span class="glyphicon glyphicon-pencil space-right"></span>Add parent topics</a>
            </div>
            <div id="add_parent_topic" class="noneDisplay">
                <form action="/topic/{{ $topic->id }}/update" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <select class="form-control topic_manage_merge_input" multiple="multiple" id="add_parent_topics" name="add_parent_topics[]">
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary space-right-big">Add</button>
                    <a href="#" onclick="cancel_from(event, 'add_parent_topic');">Cancel</a>
                </form>
            </div>
            <div id="delete_parent_topic_trigger margin-top" class="margin-top">
                <a href="#" class="text-danger" onclick="toggle('delete_parent_topic', event)"><span class="glyphicon glyphicon-remove space-right"></span>Delete subtopics</a>
            </div>
            <div id="delete_parent_topic" class="noneDisplay">
                <form action="/topic/{{ $topic->id }}/update" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <select class="form-control topic_manage_merge_input" multiple="multiple" id="delete_parent_topics" name="delete_parent_topics[]">
                            @if(count($parent_topics) > 0)
                                @foreach($parent_topics as $parent_topic)
                                    <option value="{{ $parent_topic->id }}">{{ $parent_topic->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger space-right-big">Delete</button>
                    <a href="#" onclick="cancel_from(event, 'delete_parent_topic');">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="topic_manage_item clearfix">
        <div class="topic_manage_title">
            <h5>SubTopics</h5>
        </div>
        <div class="topic_manage_content">
            @if(count($subtopics) > 0)
                <div class="topics_topicTag">
                    @foreach($subtopics as $subtopic)
                        <a class="btn btn-primary" href="/topic/{{ $subtopic->id }}">{{ $subtopic->name }}</a>
                    @endforeach
                </div>
            @endif
            <div id="add_subtopic_trigger" class="margin-top">
                <a href="#" onclick="toggle('add_subtopic', event)"><span class="glyphicon glyphicon-pencil space-right"></span>Add subtopics</a>
            </div>
            <div id="add_subtopic" class="noneDisplay">
                <form action="/topic/{{ $topic->id }}/update" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <select class="form-control topic_manage_merge_input" multiple="multiple" id="add_subtopics" name="add_subtopics[]">
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary space-right-big">Add</button>
                    <a href="#" onclick="cancel_from(event, 'add_subtopic');">Cancel</a>
                </form>
            </div>
            <div id="delete_subtopic_trigger margin-top" class="margin-top">
                <a href="#" class="text-danger" onclick="toggle('delete_subtopic', event)"><span class="glyphicon glyphicon-remove space-right"></span>Delete subtopics</a>
            </div>
            <div id="delete_subtopic" class="noneDisplay">
                <form action="/topic/{{ $topic->id }}/update" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <select class="form-control topic_manage_merge_input" multiple="multiple" id="delete_subtopics" name="delete_subtopics[]">
                            @if(count($subtopics) > 0)
                                @foreach($subtopics as $subtopic)
                                    <option value="{{ $subtopic->id }}">{{ $subtopic->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <button type="submit" class="btn btn-danger space-right-big">Delete</button>
                    <a href="#" onclick="cancel_from(event, 'delete_subtopic');">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="topic_manage_item clearfix">
        <div class="topic_manage_title">
            <h5>Topic Merge</h5>
        </div>
        <div class="topic_manage_content">
            <div id="merge_topics_trigger" class="margin-top">
                <a href="#" onclick="show_form(event, 'merge_topics'); cancel_from(event, 'merge_topics_trigger')"><span class="glyphicon glyphicon-pencil space-right"></span>Merge Topics</a>
            </div>
            <div id="merge_topics" class="noneDisplay margin-top">
                <form action="/topic/{{ $topic->id }}/update" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div><span class="glyphicon glyphicon-arrow-down"></span>Merge current topic : {{ $topic->name }} with</div>
                        <select class="form-control topic_manage_merge_input" multiple="multiple" id="main_topic" name="main_topic[]">
                        </select>
                        <div class="margin-top">
                            <button type="submit" class="btn btn-warning float-left space-right-big">Merge</button>
                            <div class="margin-top float-left">
                                <a href="#" onclick="cancel_from(event, 'merge_topics'); show_form(event, 'merge_topics_trigger');">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <hr class="small_hrLight">

    <div class="topic_manage_item clearfix">
        <div class="topic_manage_title">
            <h5>Topic Image</h5>
        </div>
        <div class="topic_manage_content">
            <div id="img_topics_trigger" class="margin-top">
                <img class="media-object topic_avatar img-rounded" src="{{ DImage($topic->avatar_img_id, 50, 50) }}" alt="{{ $topic->name }}">
                <button type="button" class="btn btn-default btn-xs margin-top" data-toggle="modal" data-target="#crop_image">
                    Upload New Image
                </button>
            </div>
        </div>
    </div>

@endsection



@section('right')
    @include('partials._topic_show_side', ['topic' => $topic, 'parent_topics' => $parent_topics, 'subtopics' => $subtopics])
@endsection


@section('javascript')
<script type="text/javascript">
    $(function() {
        topicAutocomplete('add_parent_topics', Infinity);
        topicAutocomplete('add_subtopics', Infinity);
        topicAutocomplete('main_topic', 1);
        topicAutocomplete('second_topic', 1);

        $("#delete_parent_topics").select2({
            width : '300px'
        });
        $("#delete_subtopics").select2({
            width : '300px'
        });

        // open tooltipc option
        $('[data-toggle="tooltip"]').tooltip({container: 'body'});

        cropImage('crop_img_{{ $topic->id }}', 1 / 1);
    });
</script>
@endsection