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
        <div id="answer">

        </div>

        <div class="answer_moreAnswer">
            <div><span>More Answers</span></div>
        </div>

        {{--more answer here--}}
        <div id="answer_more">

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

        showAnswers([{{ $answer->id }}], 'answer', false, function() {
            // expand all
            $('[data-toggle="expand_all"][data-type="answer"][data-id="{{ $answer->id }}"]').click();
        });

        @foreach($question->answers()->take(3)->get()->shuffle() as $other_answer)
            @if($other_answer->id != $answer->id)
                showAnswers([{{ $other_answer->id }}], 'answer_more', true);
            @endif
        @endforeach

    })
</script>
@endsection