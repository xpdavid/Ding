@extends('layouts.question')



@section('left')
    @include('partials._question_show_head', ['question' => $question, 'answerOption' => false])


    <div class="col-md-12">
        <div class="clearfix">
            <div class="float-left"><a href="/question/{{ $question->id }}">Show all {{ $question->answers->count() }} answer(s)</a></div>
            <div class="float-right">

            </div>
        </div>

        <hr>

        {{--current answer here--}}
        <div>
            <div class="answer_overall" id="answer_{{ $answer->id }}">
                <div class="answer_voting">
                    <button type="button" id="vote_answer_{{ $answer->id }}_up" class="btn btn-primary
                        @if($answer->vote_up_users->contains(Auth::user()->id))
                            active
                        @endif
                            " onclick="vote_answer('{{ $answer->id }}', 'up')">
                        <div>
                            <span class="glyphicon glyphicon-triangle-top clear_margin"></span>
                        </div>
                        <span class="vote_answer_{{ $answer->id }}_count">{{ $answer->netVotes }}</span>
                    </button>
                    <button type="button" id="vote_answer_{{ $answer->id }}_down" class="btn btn-primary
                        @if($answer->vote_down_users->contains(Auth::user()->id))
                            active
                        @endif
                            " onclick="vote_answer('{{ $answer->id }}', 'down')">
                        <span class="glyphicon glyphicon-triangle-bottom clear_margin"></span>
                    </button>
                </div>


                <div class="clearfix">
                    <div class="float-left"><strong>{{ $answer->owner->name }}</strong>, <span class="font-black">{{ $answer->owner->bio }}</span> </div>
                    <img class="float-right" src="{{ DImage($answer->owner->settings->profile_pic_id, 25, 25) }}" alt="{{ $answer->owner->name }}">
                </div>

                <div>
                    <span class="answer_vote "><span class="vote_answer_{{ $answer->id }}_count">{{ $answer->netVotes }}</span> Vote(s)</span>
                </div>

                <div class="answer_content font-black clearfix">
                    {!! $answer->answer !!}
                </div>

                <div class="horizontal_item">
                    <a href="#">Posted on {{ $answer->createdAtHumanReadable }}</a>
                    <a href="#"
                       id="answer_comment_{{ $question->id }}_trigger"
                       onclick="showComment(event, 'answer', '{{ $answer->id }}', 'answer_comment_{{ $answer->id }}');">
                        <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                        Comment (<span id="answer_comment_{{ $answer->id }}_replies_count">{{ $answer->replies()->count() }}</span>)
                    </a>
                    <a href="#" onclick="bookmark('answer', '{{$answer->id}}', event)"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
                    <a href="#"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>Not helpful</a>
                    <a href="#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>Report</a>
                </div>


                <div class="comment_box" id="answer_comment_{{ $answer->id }}">
                    <div class="comment_spike"></div>
                    <div class="comment_list">
                        <div class="comment_content" id="answer_comment_{{ $answer->id }}_content">

                        </div>

                        <div class="text-center" id="answer_comment_{{ $answer->id }}_nav">

                        </div>

                        <div class="comment_form clearfix">
                            <div class="form-group">
                                <input type="email"
                                       class="form-control" id="answer_comment_{{ $answer->id }}_input"
                                       placeholder="Write Your Comment Here",
                                       id="answer_comment_{{ $answer->id }}_input",
                                       onfocus="show_form(event, 'answer_comment_{{ $answer->id }}_buttons')">
                            </div>
                            <div class="float-right form-group noneDisplay" id="answer_comment_{{ $answer->id }}_buttons">
                                <a href="#" role="button" class="space-right-big"
                                   onclick="cancel_from(event, 'answer_comment_{{ $answer->id }}_buttons')">Cancel</a>
                                <button class="btn btn-primary" type="submit" onclick="saveComment('answer_comment_{{ $answer->id }}', '{{ $answer->id }}', 'answer')">Submit</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="answer_moreAnswer">
            <div><span>More Answers</span></div>
        </div>

        {{--more answer here--}}
        <div>
            @foreach($question->answers()->take(3)->get()->shuffle() as $other_answer)
                @if($other_answer->id != $answer->id)
                    <div class="answer_overall">
                        <div class="answer_voting">
                            <button type="button" id="vote_answer_{{ $other_answer->id }}_up" class="btn btn-primary
                                @if($other_answer->vote_up_users->contains(Auth::user()->id))
                                    active
                                @endif
                            " onclick="vote_answer('{{ $other_answer->id }}', 'up')">
                                <div>
                                    <span class="glyphicon glyphicon-triangle-top clear_margin"></span>
                                </div>
                                <span class="vote_answer_{{ $other_answer->id }}_count">{{ $other_answer->netVotes }}</span>
                            </button>
                            <button type="button" id="vote_answer_{{ $other_answer->id }}_down" class="btn btn-primary
                                @if($other_answer->vote_down_users->contains(Auth::user()->id))
                                    active
                                @endif
                                    " onclick="vote_answer('{{ $other_answer->id }}', 'down')">
                                <span class="glyphicon glyphicon-triangle-bottom clear_margin"></span>
                            </button>
                        </div>


                        <div class="clearfix">
                            <div class="float-left"><strong>{{ $other_answer->owner->name }}</strong>, <span class="font-black">{{ $other_answer->owner->bio }}</span> </div>
                            <img class="float-right" src="{{ DImage($other_answer->owner->settings->profile_pic_id, 25, 25) }}" alt="{{ $other_answer->owner->name }}">
                        </div>

                        <div>
                            <span class="answer_vote "><span class="vote_answer_{{ $other_answer->id }}_count">{{ $other_answer->netVotes }}</span> Vote(s)</span>
                        </div>

                        <div class="answer_content font-black clearfix">
                            <div id="answer_summary_{{ $other_answer->id }}" class="answer_summary">
                                {!! $other_answer->summary !!} <a href="#" class="answer_show_all" data-toggle="expand_all" data-type="answer" data-id="{{ $other_answer->id}}">Show all</a>
                            </div>
                            <div id="answer_full_{{ $other_answer->id }}">

                            </div>
                        </div>

                        <div class="horizontal_item">
                            <a href="#">Posted on {{ $other_answer->createdAtHumanReadable }}</a>
                            <a href="#" onclick="showComment(event, this, 'answer', '{{ $other_answer->id }}', 'answer_comment_{{ $other_answer->id }}');">
                                <span class="glyphicon glyphicon-comment" aria-hidden="true"></span>
                                Comment (<span id="answer_comment_{{ $other_answer->id }}_replies_count">{{ $other_answer->replies()->count() }}</span>)
                            </a>
                            <a href="#" onclick="bookmark('answer', '{{$other_answer->id}}', event)"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Bookmark</a>
                            <a href="#"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span>Not helpful</a>
                            <a href="#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span>Report</a>
                        </div>


                        <div class="comment_box" id="answer_comment_{{ $other_answer->id }}">
                            <div class="comment_spike"></div>
                            <div class="comment_list">
                                <div class="comment_content" id="answer_comment_{{ $other_answer->id }}_content">

                                </div>

                                <div class="text-center" id="answer_comment_{{ $other_answer->id }}_nav">

                                </div>

                                <div class="comment_form clearfix">
                                    <div class="form-group">
                                        <input type="email"
                                               class="form-control" id="answer_comment_{{ $other_answer->id }}_input"
                                               placeholder="Write Your Comment Here",
                                               id="answer_comment_{{ $other_answer->id }}_input",
                                               onfocus="show_form(event, 'answer_comment_{{ $other_answer->id }}_buttons')">
                                    </div>
                                    <div class="float-right form-group noneDisplay" id="answer_comment_{{ $other_answer->id }}_buttons">
                                        <a href="#" role="button" class="space-right-big"
                                           onclick="cancel_from(event, 'answer_comment_{{ $other_answer->id }}_buttons')">Cancel</a>
                                        <button class="btn btn-primary" type="submit" onclick="saveComment('answer_comment_{{ $other_answer->id }}', '{{ $other_answer->id }}', 'answer')">Submit</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <hr>
                @endif
            @endforeach
        </div>

        <div class="text-center"><a href="/question/{{ $question->id }}">Show all {{ $question->answers->count() }} answer(s)</a></div>

        <hr>

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
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="sideBar_sectionItem">
                <h4>About Author</h4>
                <div class="media">
                    <div class="media-left">
                        <a href="#">
                            <img class="media-object avatar-img" src="{{ DImage($answer->owner->settings->profile_pic_id,50, 50) }}"
                                 alt="{{ $answer->owner->name }}">
                        </a>
                    </div>
                    <div class="media-body">
                        <button class="btn btn-success btn-xs float-right" onclick="user_button_subscribe(this, '{{ $answer->owner->id }}', null);">Subscribe</button>
                        <h4 class="media-heading">{{ $answer->owner->name }}</h4>
                        {{ $answer->owner->bio }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($answer->bookmarks()->count() != 0)
        <div class="sideBar_section">
            <div class="sideBar_sectionItem">
                <div class="sideBar_sectionItem">
                    <h4>Being Bookmarked {{ $answer->bookmarks()->count() }} times</h4>
                    @foreach($answer->bookmarks()->orderBy('updated_at')->take(5)->get() as $bookmark)
                        <div>
                            <strong>
                                <a href="/bookmark/{{ $bookmark->id }}">{{ $bookmark->name }}</a>
                            </strong>
                            <div>
                                <a href="/people/{{ $bookmark->owner->url_name }}">
                                    {{ $bookmark->owner->name }}</a> Created |
                                <span>{{ $bookmark->subscribers()->count() }}</span> Subscribe
                            </div>
                            <hr class="small_hrLight">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="sideBar_sectionItem">
                <h5>Relevant Question</h5>
                @foreach($also_interest as $also_interest_question)
                    <div>
                        <a href="/question/{{ $also_interest_question->id }}">
                            {{ $also_interest_question->title }}
                        </a>
                        <span>- {{ $also_interest_question->answers()->count() }} answer</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="sideBar_sectionItem">
            <div class="sideBar_sectionItem">
                <h5>Answer Status</h5>
                <p class="font-greyLight">Created at {{ $answer->updated_at }}</p>
                <p class="font-greyLight">Belonging Question has being view <strong>{{ $answer->hit->total }}</strong> times</p>
            </div>
        </div>
    </div>


@endsection


@section('javascript')
<script type="text/javascript">
    // highlight current answer
    $(function() {
        // determine show/highlight reply
        @if($highlight)
            //show_reply(reply_id, base_id, type, item_id, page)
            highlight_reply('{{ $highlight['reply_id'] }}',
                    '{{ $highlight['base_id'] }}',
                    '{{ $highlight['type'] }}',
                    '{{ $highlight['item_id'] }}',
                    '{{ $highlight['page'] }}');
        @else
            // we don't want to highlight reply, we highlight answer
            highlight('answer_{{ $answer->id }}', true);
        @endif

        imgResponsiveIn('answer_{{ $answer->id }}');
    })
</script>
@endsection