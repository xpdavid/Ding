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
            <a href="#" onclick="showComment(event, this, 'question', '{{ $question->id }}', 'question_comment', 'question_comment_content');"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Comment ({{ $question->replies->count() }})</a>
            <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
            <a href="#"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>Invite Friends</a>
        </div>

        <div class="comment_box" id="question_comment">
            <div class="comment_spike">
                <div class="comment_spike_arrow"></div>
            </div>
            <div class="comment_list">
                <div class="comment_content" id="question_comment_content">

                </div>

                <div class="text-center" id="nav_question_comment_content">

                </div>

                <div class="comment_form clearfix">
                    <div class="form-group">
                        <input type="email" class="form-control" id="reply_content" placeholder="Write Your Comment Here" onfocus="show_form(event, 'question_comment_button')">
                    </div>
                    <div class="float-right form-group" id="question_comment_button">
                        <a href="#" role="button" class="space-right-big" onclick="cancel_from(event, 'question_comment_button')">Cancel</a>
                        <button class="btn btn-primary" type="submit">Submit</button>
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

        <div id="question_answers">
            @foreach($answers->take(8) as $answer)
                <div class="answer_overall">
                    <div class="answer_voting">
                        <button type="button" class="btn btn-primary">
                            <div>
                                <span class="glyphicon glyphicon-triangle-top clear_margin"></span>
                            </div>
                            {{ $answer->netVotes }}
                        </button>
                        <button type="button" class="btn btn-primary">
                            <span class="glyphicon glyphicon-triangle-bottom clear_margin"></span>
                        </button>
                    </div>


                    <div class="answer_userInfo clearfix">
                        <div class="float-left"><strong>{{ $answer->owner->name }}</strong>, {{ $answer->owner->bio }}</div>
                        <img class="answer_userImg" src="image/sample_icon.png" alt="">
                    </div>

                    <div>
                        <span class="answer_vote">{{ $answer->netVotes }} Vote(s)</span>
                    </div>

                    <div class="answer_content font-black">
                        {{ $answer->answer }}
                    </div>

                    <div class="horizontal_item">
                        <a href="#">Posted on {{ $answer->createdAtHumanReadable }}</a>
                        <a href="#" onclick="showComment(event, this, 'answer', '{{ $answer->id }}', 'answer_comment_{{ $answer->id }}', 'question_comment_content_{{ $answer->id }}');"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Comment ({{ $answer->replies->count() }})</a>
                        <a href="#"><span class="glyphicon glyphicon-heart" aira-hidden="true"></span>Like</a>
                        <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
                        <a href="#"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>Not helpful</a>
                        <a href="#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>Report</a>
                    </div>


                    <div class="comment_box" id="answer_comment_{{ $answer->id }}">
                        <div class="comment_spike"></div>
                        <div class="comment_list">
                            <div class="comment_content" id="question_comment_content_{{ $answer->id }}">

                            </div>

                            <div class="text-center" id="nav_question_comment_content_{{ $answer->id }}">

                            </div>

                            <div class="comment_form clearfix">
                                <div class="form-group">
                                    <input type="email" class="form-control" id="reply_content" placeholder="Write Your Comment Here" onfocus="show_form(event, 'answer_comment_button_{{ $answer->id }}')">
                                </div>
                                <div class="float-right form-group answer_comment_button" id="answer_comment_button_{{ $answer->id }}">
                                    <a href="#" role="button" class="space-right-big" onclick="cancel_from(event, 'answer_comment_button_{{ $answer->id }}')">Cancel</a>
                                    <button class="btn btn-primary" type="submit">Submit</button>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <hr>
            @endforeach
        </div>


        <div class="answer_more">
            <button type="button" class="btn btn-default" onclick="getMore(event, 'question_answers', '{{ $question->id }}', '{{ $sorted }}')">More</button>
        </div>

        <div class="clearfix question_answer" id="question_answer_form">
            <hr>
            <div class="write_answer_userInfo clearfix">
                <strong>{{ Auth::user()->name }}</strong>, the embodiment of ultimate randomness
                <img class="answer_userImg" src="image/sample_icon.png" alt="">
            </div>
            <form class="clearfix">
                <div class="form-group">
                    <textarea class="form-control" id="question_answerSubmission" placeholder="Write your answer here." rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-warning">Submit</button>
            </form>
        </div>
    </div>
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