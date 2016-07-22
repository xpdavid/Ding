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
            <button type="button" id='question_answers_button' class="btn btn-default" onclick="getMore('question_answers', '{{ $question->id }}', '{{ $sorted }}', 'get_more_answers')">More</button>
        </div>

        @if($question->closedAnswers()->count() > 0)
            <hr>
            {{--question has closed answer--}}
            <div>
                <div class="font-greyLight">
                    <a href="#" data-action="closed_answers" data-id="{{ $question->id }}">
                        Click
                    </a> to show {{ $question->closedAnswers()->count() }} closed answers.
                </div>
            </div>
            <div id="closed_answers_box" class="noneDisplay margin-top">
                <div id="closed_answers">

                </div>
                <div class="text-center" id="closed_answers_nav">

                </div>
            </div>
        @endif

        @if ($question->isClosed())
            {{--The question is closed--}}
            <div class="text-center">
                <hr>
                <h5 class="font-greyLight">
                    The question is closed, you may send ticket to re-open the question
                    <a href="#">
                        Ticket
                    </a>
                </h5>
            </div>
            <br>
        @else

            @if ($question->hasPublishedAnswerBy(Auth::user()->id))
                {{--user has answer the question--}}
                <div class="text-center">
                    <hr>
                    <h5 class="font-greyLight">
                        You can only answer a question once. You may edit
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
                        <a name="answer"></a>
                    <textarea
                            name="user_answer"
                            class="form-control"
                            id="question_answers_input"
                            placeholder="Write your answer here."
                            rows="5",
                            data-autosave="true"
                            data-draft_url="/question/{{ $question->id }}/draft"
                    ></textarea>
                        <div class="margin-top clearfix">
                            <p class="text-danger float-left noneDisplay" id="question_answers_error">Bruh, I think answers must be more than 1 character.</p>
                            <button type="submit" class="btn btn-warning float-right"
                                    onclick="saveAnswer('question_answers', '{{ $question->id }}')">Submit</button>
                        </div>

                    </div>
                </div>
            @endif

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
    @include('partials._question_side', ['question' => $question])
@endsection

@section('javascript')
    <script type="text/javascript">
        // onload event then load answers
        $(function() {
            getMore('question_answers', '{{ $question->id }}', '{{ $sorted }}', function() {
                highlight_reply('{{ $highlight['reply_id'] }}',
                        '{{ $highlight['base_id'] }}',
                        '{{ $highlight['type'] }}',
                        '{{ $highlight['item_id'] }}',
                        '{{ $highlight['page'] }}');
            });

            invite_search_box('{{ $question->id }}');

            // open tooltip option
            $('[data-toggle="tooltip"]').tooltip({container: 'body'});

            tinyMCEeditor('question_answers_input', function(editor) {
                // check has draft before
                @if($question->answerDraftBy(Auth::user()->id))
                        getAnswerDraft('{{ $question->answerDraftBy(Auth::user()->id)->id }}' ,'question_answers_input');
                @endif
            });

            bindGetClosedAnswers();

        });
    </script>
@endsection