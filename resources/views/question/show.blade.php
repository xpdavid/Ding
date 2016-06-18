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
            <a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>Answer</a>
            <a href="#"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Comment</a>
            <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
            <a href="#"><span class="glyphicon glyphicon-send" aria-hidden="true"></span>Invite Friends</a>
        </div>

        <div class="comment_box">
            <div class="comment_spike">
                <div class="comment_spike_arrow"></div>
            </div>
            <div class="comment_list">
                <div class="comment_content">
                    @foreach($question->replies as $reply)
                        <div class="media comment_item">
                            <div class="media-left">
                                <a href="#">
                                    <img class="media-object" src="..." alt="...">
                                </a>
                            </div>
                            <div class="media-body">
                                <div class="clearfix font-black">
                                    <div class="media-heading float-left"><a href=""><strong>asd</strong></a></div>
                                    <div class="float-right invisible">
                                        <a href="#"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Show Conversation</a>
                                    </div>
                                    <div class="comment_item_content">
                                        {{ $reply->reply }}
                                    </div>
                                </div>
                                <div class="float-left horizontal_item">
                                    <a href="#">{{ $reply->createdAtHumanReadable }}</a>
                                    <a href="#"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Reply</a>
                                    <a href="#"><span class="glyphicon glyphicon-heart" aira-hidden="true"></span>Like</a>
                                    <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Report</a>
                                </div>
                                <div class="float-right">{{ $reply->vote_up_users->count() }} vote(s)</div>
                                <span class="clearfix"></span>
                                <div class="comment_for_user clearfix">
                                    <div class="form-group">
                                        <input type="email" class="form-control" id="reply_content" placeholder="Write Your Comment Here">
                                    </div>
                                    <div class="float-right form-group">
                                        <a href="#" role="button" class="space-right-big">Cancel</a>
                                        <button class="btn btn-primary" type="submit">Comment</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr class="small_hrLight">
                    @endforeach
                </div>

                <div class="text-center">
                    <nav>
                        <ul class="pagination">
                            <li>
                                <a href="#" aria-label="Previous">
                                    <span aria-hidden="true">Prev Page</span>
                                </a>
                            </li>
                            <li><a href="#">1</a></li>
                            <li><a href="#">2</a></li>
                            <li><a href="#">3</a></li>
                            <li><a href="#">4</a></li>
                            <li><a href="#">5</a></li>
                            <li>
                                <a href="#" aria-label="Next">
                                    <span aria-hidden="true">Next Page</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="comment_form clearfix">
                    <div class="form-group">
                        <input type="email" class="form-control" id="reply_content" placeholder="Write Your Comment Here">
                    </div>
                    <div class="float-right form-group">
                        <button class="btn btn-primary space-right-big" type="submit">Submit</button>
                        <a href="#" role="button">Cancel</a>
                    </div>
                </div>

            </div>
        </div>

        <hr class="question_layout_bottom_hr">
    </div>

    <div class="col-md-12 answer_layout">
        <div class="answer_num clearfix">
            <p>{{ $question->answers->count() }} answer(s)</p>
            <div class="question_sortby">
                <a href="">Sort by Rate</a> / <a href="">Sort by Date</a>
            </div>
        </div>

        <hr class="small_hrLight">

        @foreach($question->answers as $answer)
            <div class="answer_overall">
                <div class="answer_voting">
                    <button type="button" class="btn btn-primary">
                        <div>
                            <span class="glyphicon glyphicon-triangle-top clear_margin"></span>
                        </div>
                        {{ $answer->vote_up_users->count() }}
                    </button>
                    <button type="button" class="btn btn-primary">
                        <span class="glyphicon glyphicon-triangle-bottom clear_margin"></span>
                    </button>
                </div>


                <div class="answer_userInfo clearfix">
                    <div class="float-left"><strong>{{ $answer->owner->name }}</strong>, the embodiment of ultimate randomness</div>
                    <img class="answer_userImg" src="image/sample_icon.png" alt="">
                </div>

                <div>
                    <span class="answer_vote">{{ $answer->vote_up_users->count() }} Vote(s)</span>
                </div>

                <div class="answer_content font-black">
                    {{ $answer->answer }}
                </div>

                <div class="horizontal_item">
                    <a href="#">Posted on {{ $answer->createdAtHumanReadable }}</a>
                    <a href="#"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Comment</a>
                    <a href="#"><span class="glyphicon glyphicon-heart" aira-hidden="true"></span>Like</a>
                    <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
                    <a href="#"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>Not helpful</a>
                    <a href="#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>Report</a>
                </div>


                <div class="comment_box">
                    <div class="comment_spike"></div>
                    <div class="comment_list">
                        <div class="comment_content">
                            @foreach($answer->replies as $reply)
                                <div class="media comment_item">
                                    <div class="media-left">
                                        <a href="#">
                                            <img class="media-object" src="..." alt="...">
                                        </a>
                                    </div>
                                    <div class="media-body">
                                        <div class="clearfix font-black">
                                            <div class="media-heading float-left"><a href=""><strong>asd</strong></a></div>
                                            <div class="float-right invisible">
                                                <a href="#"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Show Conversation</a>
                                            </div>
                                            <div class="comment_item_content">
                                                {{ $reply->reply }}
                                            </div>
                                        </div>
                                        <div class="float-left horizontal_item">
                                            <a href="#">{{ $reply->createdAtHumanReadable }}</a>
                                            <a href="#"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span>Reply</a>
                                            <a href="#"><span class="glyphicon glyphicon-heart" aira-hidden="true"></span>Like</a>
                                            <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Report</a>
                                        </div>
                                        <div class="float-right">{{ $reply->vote_up_users->count() }} vote(s)</div>
                                    </div>
                                </div>
                                <hr class="small_hrLight">
                            @endforeach
                        </div>

                        <div class="text-center">
                            <nav>
                                <ul class="pagination">
                                    <li>
                                        <a href="#" aria-label="Previous">
                                            <span aria-hidden="true">Prev Page</span>
                                        </a>
                                    </li>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">4</a></li>
                                    <li><a href="#">5</a></li>
                                    <li>
                                        <a href="#" aria-label="Next">
                                            <span aria-hidden="true">Next Page</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>

                        <div class="comment_form clearfix">
                            <div class="form-group">
                                <input type="email" class="form-control" id="reply_content" placeholder="Write Your Comment Here">
                            </div>
                            <div class="float-right form-group">
                                <a href="#" role="button" class="space-right-big">Cancel</a>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

            <hr>
        @endforeach

        <div class="answer_more">
            <button type="button" class="btn btn-default">More</button>
        </div>

        <div class="clearfix question_answer">
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