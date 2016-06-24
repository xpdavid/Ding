@extends('layouts.question')

@section('left')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12">
                @foreach($question->topics as $topic)
                    <a class="btn btn-primary question_topic_tag" href="/topic/{{ $topic->id }}" role="button">{{ $topic->name }}</a>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h3> {{ $question->title }} </h3>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 font-black">
                {{ $question->content }}
            </div>
        </div>

        <div class="horizontal_item">
            <a href="#" onclick="scroll_to('question_answer_form', event)"><span class="glyphicon glyphicon-pencil" aria-hidden="true" ></span>Answer</a>
            <a href="#"
               id="question_comment_{{ $question->id }}_trigger"
               onclick="showComment(event, 'question', '{{ $question->id }}', 'question_comment_{{ $question->id }}');">
                <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                Comment (<span id="question_comment_{{ $question->id }}_replies_count">{{ $question->replies->count() }}</span>)
            </a>
            <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
            <a href="#"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>Invite Friends</a>
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

        <hr>
    </div>

    <div class="col-md-12">
        <div class="clearfix">
            <div class="float-left">{{ $question->answers->count() }} answer(s)</div>
            <div class="float-right">
                @if($sorted == 'created')
                    <a href="{{ action('QuestionController@show', $question->id) }}">Sort by Vote</a> / Sort by Date
                @else
                    Sort by Vote / <a href="{{ action('QuestionController@show', [$question->id, 'sorted' => 'created']) }}">Sort by Date</a>
                @endif
            </div>
        </div>

        <hr>

        <div id="question_answers"></div>

        <div class="answer_more">
            <button type="button" id='get_more_answers' class="btn btn-default" onclick="getMore('question_answers', '{{ $question->id }}', '{{ $sorted }}', 'get_more_answers')">More</button>
        </div>

        <div class="clearfix question_answer" id="question_answer_form">
            <hr>
            <div class="write_answer_userInfo clearfix">
                <div class="float-left"><strong>{{ Auth::user()->name }}</strong>, {{ Auth::user()->bio }}</div>
                <img class="float-right" src="image/sample_icon.png" alt="">
            </div>
            <form action="/question/{{ $question->id }}/answer"
                  class="clearfix margin-top" method="POST"
                  role="form" data-toggle="validator"
                  onsubmit="return saveAnswer('question_answers', '{{ $question->id }}')">
                {{ csrf_field() }}
                <div class="form-group">
                    <textarea
                            name="user_answer"
                            class="form-control"
                            id="question_answers_input"
                            placeholder="Write your answer here."
                            rows="5"
                            data-minlength="10"
                            required
                            data-error="Bruh, I think answers must be more than 10 characters."
                    ></textarea>
                    <div class="help-block with-errors"></div>
                    <button type="submit" class="btn btn-warning float-right">Submit</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials._show_comment_conversation')
@endsection


@section('right')
    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div>
                @if(Auth::user()->subscribe->checkHasSubscribed($question->id, 'question'))
                    <button type="button" class="btn btn-warning"
                            onclick="show_question_subscribe(this, '{{ $question->id }}')">
                        Unsubscribe</button>
                @else
                    <button type="button" class="btn btn-success"
                            onclick="show_question_subscribe(this, '{{ $question->id }}')">
                        Subscribe</button>
                @endif
            </div>
            <div class="margin-top">
                <span id="question_subscriber">{{ $question->subscribers()->count() }}</span> people have subscribed to this question.
            </div>
            <div class="margin-top">
                <img src="image/sample_icon.png" alt="..." class="img-rounded avatar-img">
                <img src="image/sample_icon.png" alt="..." class="img-rounded avatar-img">
                <img src="image/sample_icon.png" alt="..." class="img-rounded avatar-img">
                <img src="image/sample_icon.png" alt="..." class="img-rounded avatar-img">
                <img src="image/sample_icon.png" alt="..." class="img-rounded avatar-img">
            </div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <h4>You might also be interested in:</h4>
            @foreach($also_interest as $interest_question)
                <p><a href="{{ action('QuestionController@show', $interest_question->id) }}">{{ $interest_question->title }}</a> {{ $interest_question->answers->count() }} answers</p>
            @endforeach
        </div>

    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        // onload event then load answers
        $(function() {
            getMore('question_answers', '{{ $question->id }}', '{{ $sorted }}', 'get_more_answers', function() {
                //show_reply(reply_id, base_id, type, item_id, page)
                highlight_reply('{{ $highlight['reply_id'] }}',
                        '{{ $highlight['base_id'] }}',
                        '{{ $highlight['type'] }}',
                        '{{ $highlight['item_id'] }}',
                        '{{ $highlight['page'] }}');
            });
        })
    </script>
@endsection