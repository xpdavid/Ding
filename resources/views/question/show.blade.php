@extends('layouts.question')

@section('left')
    @include('partials._question_show_head', ['question' => $question, 'answerOption'=> true])

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

        @if ($question->answers()->where('user_id', Auth::user()->id)->exists())
            {{--user has answer the question--}}
            <div class="text-center">
                <hr>
                <h5 class="font-greyLight">
                    You can only answer a question once, but you can edit
                    <a href="/answer/{{ $question->answers()->where('user_id', Auth::user()->id)->first()->id }}">
                        current answer
                    </a>
                </h5>
            </div>
        @else
            <div class="clearfix question_answer" id="question_answer_form">
                <hr>
                <div class="write_answer_userInfo clearfix">
                    <div class="float-left"><strong>{{ Auth::user()->name }}</strong>, {{ Auth::user()->bio }}</div>
                    <img class="float-right" src="{{ DImage(Auth::user()->settings->profile_pic_id, 25, 25) }}" alt="{{ Auth::user()->name }}">
                </div>
                <div class="form-group margin-top">
                <textarea
                        name="user_answer"
                        class="form-control"
                        id="question_answers_input"
                        placeholder="Write your answer here."
                        rows="5"
                ></textarea>
                    <div class="margin-top clearfix">
                        <p class="text-danger float-left noneDisplay" id="question_answers_error">Bruh, I think answers must be more than 1 characters.</p>
                        <button type="submit" class="btn btn-warning float-right"
                                onclick="saveAnswer('question_answers', '{{ $question->id }}')">Submit</button>
                    </div>

                </div>
            </div>
        @endif



    </div>

    @include('partials._crop_image_model', [
        'url' => '/user/upload',
        'image' => '/static/images/default.png',
        'id' => 'question_answers_input',
        'type' => '',
    ])

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
                @foreach($question->subscribers as $subscriber)
                    <img src="{{ DImage($subscriber->owner->settings->profile_pic_id, 25, 25) }}" alt="{{$subscriber->owner->name}}" class="img-rounded _portrait">
                @endforeach
            </div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <h4>You might also be interested in:</h4>
            @foreach($also_interest as $interest_question)
                <p><a href="{{ action('QuestionController@show', $interest_question->id) }}">{{ $interest_question->title }}</a> {{ $interest_question->answers()->count() }} answers</p>
            @endforeach
        </div>

    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <span class="font-greyLight">
                This question has been viewed <strong>{{ $question->hit->total }}</strong> times.
            </span>
        </div>
    </div>
@endsection

@section('javascript')
    <script type="text/javascript">
        // onload event then load answers
        $(function() {
            getMore('question_answers', '{{ $question->id }}', '{{ $sorted }}', 'get_more_answers', function() {
                highlight_reply('{{ $highlight['reply_id'] }}',
                        '{{ $highlight['base_id'] }}',
                        '{{ $highlight['type'] }}',
                        '{{ $highlight['item_id'] }}',
                        '{{ $highlight['page'] }}');
            });

            invite_search_box('{{ $question->id }}');

            // open tooltipc option
            $('[data-toggle="tooltip"]').tooltip({container: 'body'});

            tinyMCEeditor('question_answers_input');
        });
    </script>
@endsection