<div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            @foreach($question->topics as $topic)
                <a class="btn btn-primary question_topic_tag" href="/topic/{{ $topic->id }}" role="button">{{ $topic->name }}</a>
            @endforeach
        </div>
        <span class="noneDisplay" data-type="question_topics"
              data-content='{!! json_encode($question->topics->lists('name', 'id')->all()) !!}'></span>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h3> <a href="/question/{{ $question->id }}">{{ $question->title }}</a>
                {{--question reward--}}
                <span class="badge">{{ $question->reward }}</span>
                @if($question->isClosed()) <span class="label label-warning">Closed</span> @endif
                @if (Auth::user()->operation(9) || $question->owner->id == Auth::user()->id)
                    <a href="#" class="userSetting_EditA"
                       onclick="editQuestion(event, '{{ $question->id }}',
                               '{{ $question->owner->id == Auth::user()->id ? 'true' : 'false'}}')">
                        <span class="glyphicon glyphicon-pencil"></span>Edit</a>
                @endif
            </h3>
        </div>
        <div class="col-md-12 font-black">
            <div id="question_summary_{{ $question->id }}" class="_summary">
                {!! $question->summary !!} <a href="#" class="_show_all" data-toggle="expand_all" data-type="question" data-id="{{ $question->id }}">Show all</a>
            </div>
            <div id="question_full_{{ $question->id }}">
                <span id="question_full_content_{{$question->id}}_viewport_top"></span>
                <div id="question_full_content_{{ $question->id }}">

                </div>
                <a href="#" class="_show_all"
                   data-toggle="hide" data-hide="question_full_{{ $question->id }}" data-show="question_summary_{{ $question->id }}">Close</a>
                <span id="question_full_content_{{$question->id}}_viewport_bottom"></span>
            </div>
            @if ($question->isClosed())
                <div class="well margin-top">
                    The question is closed for some reason:<br>
                    <strong>{{ $question->closeReason() }}</strong>
                </div>
            @endif
        </div>
    </div>

    <div class="horizontal_item">
        @if ($answerOption)
            <a href="#" onclick="scroll_to('question_answer_form', event)"><span class="glyphicon glyphicon-pencil" aria-hidden="true" ></span>Answer</a>
        @else
            <a href="/question/{{ $question->id }}"><span class="glyphicon glyphicon-pencil" aria-hidden="true" ></span>Answer</a>
        @endif
        <a href="#"
           id="question_comment_{{ $question->id }}_trigger"
           onclick="showComment(event, 'question', '{{ $question->id }}', 'question_comment_{{ $question->id }}');">
            <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
            Comment (<span id="question_comment_{{ $question->id }}_replies_count">{{ $question->replies->count() }}</span>)
        </a>
        <a href="#" onclick="bookmark('question', '{{ $question->id }}', event)"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
        <a href="#" onclick="invite_panel(event, '{{ $question->id }}')"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>Invite Friends</a>
    </div>

    <div class="comment_box" id="question_comment_{{ $question->id }}">
        <div class="comment_spike">
            <div class="comment_spike_arrow"></div>
        </div>
        <div class="comment_list">
            <div class="comment_content" id="question_comment_{{ $question->id }}_content">

            </div>

            <div class="text-center" id="question_comment_{{ $question->id }}_nav">

            </div>

            <div class="comment_form clearfix">
                <div class="form-group">
                    <input type="text"
                           class="form-control"
                           id="question_comment_{{ $question->id }}_input"
                           placeholder="Write Your Comment Here"
                           onfocus="show_form(event, 'question_comment_{{ $question->id }}_buttons')"
                    >
                </div>
                <div class="float-right form-group noneDisplay" id="question_comment_{{ $question->id }}_buttons">
                    <a href="#" role="button" class="space-right-big" onclick="cancel_from(event, 'question_comment_{{ $question->id }}_buttons')">Cancel</a>
                    <button class="btn btn-primary" type="submit"
                            onclick="saveComment('question_comment_{{ $question->id }}', '{{ $question->id }}', 'question')">Submit</button>
                </div>
            </div>

        </div>
    </div>

    <div class="invite_box" id="invite_box">
        <div class="invite_spike">
            <div class="invite_spike_arrow"></div>
        </div>

        <div class="row invite_list_item">
            <div class="form-group col-md-5">
                <input type="text" class="form-control invite_search_box" id="invite_user_search">
                <span class="glyphicon glyphicon-search invite_search_box_icon font-greyLight"></span>
            </div>
            <div class="col-md-7">
                <span class="font-greyLight">You may invite other users whom you think could answer this question.</span>
            </div>
        </div>
        <hr class="small_hrLight">

        <div class="invite_list">
            <div class="invite_box_search" id="question_invite_content">

            </div>

        </div>
    </div>

    <hr>
</div>