@extends('layouts.question')

@section('left')
    <div class="col-md-12 question_layout">
        <div class="row question_tag">
            <div class="col-md-12">
                @foreach($question->topics as $topic)
                    <a class="btn btn-primary" href="#" role="button">{{ $topic->name }}</a>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2 class="question_title"> {{ $question->title }} </h2>
            </div>
        </div>

        <div class="row question_content">
            <div class="col-md-12 font-black">
                {{ $question->content }}
            </div>
        </div>

        <div class="horizontal_item">
            <a href="#" onclick="scroll_to(event, 'question_answer_form')"><span class="glyphicon glyphicon-pencil" aria-hidden="true" ></span>Answer</a>
            <a href="#" onclick="showComment(event, this, 'question', '{{ $question->id }}', 'question_comment_{{ $question->id }}');">
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

    <div class="col-md-12 answer_layout">
        <div class="answer_num clearfix">
            <p>{{ $question->answers->count() }} answer(s)</p>
            <div class="question_sortby">
                @if($sorted == 'created')
                    <a href="{{ action('QuestionController@show', $question->id) }}">Sort by Rate</a> / Sort by Date
                @else
                    Sort by Rate / <a href="{{ action('QuestionController@show', [$question->id, 'sorted' => 'created']) }}">Sort by Date</a>
                @endif
            </div>
        </div>

        <hr class="small_hrLight">

        <div id="question_answers"></div>

        <div class="answer_more">
            <button type="button" class="btn btn-default" onclick="getMore('question_answers', '{{ $question->id }}', '{{ $sorted }}')">More</button>
        </div>

        <div class="clearfix question_answer" id="question_answer_form">
            <hr>
            <div class="write_answer_userInfo clearfix">
                <strong>{{ Auth::user()->name }}</strong>, {{ Auth::user()->bio }}
                <img class="answer_userImg" src="image/sample_icon.png" alt="">
            </div>
            <form action="/question/{{ $question->id }}/answer"
                  class="clearfix" method="POST"
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
                    <button type="submit" class="btn btn-warning">Submit</button>
                </div>
            </form>
        </div>
    </div>

    @include('partials._show_comment_conversation')
@endsection


@section('right')
    <div class="question_subscribe">
        <div>
            <button type="button" class="btn btn-success">Subscribe</button>
        </div>
        <div>
            <a href="#">270</a> people have subscribed to this question.
        </div>
        <div>
            <img src="image/sample_icon.png" alt="..." class="img-rounded">
            <img src="image/sample_icon.png" alt="..." class="img-rounded">
            <img src="image/sample_icon.png" alt="..." class="img-rounded">
            <img src="image/sample_icon.png" alt="..." class="img-rounded">
            <img src="image/sample_icon.png" alt="..." class="img-rounded">
        </div>
    </div>

    <hr>

    <div class="question_related">
        <h4>You might also be interested in:</h4>
        @foreach($also_interest as $question)
            <p><a href="{{ action('QuestionController@show', $question->id) }}">{{ $question->title }}</a> {{ $question->answers->count() }} answers</p>
        @endforeach
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        // onload event then load answers
        $(function() {
            getMore('question_answers', '{{ $question->id }}');
        })
    </script>
@endsection