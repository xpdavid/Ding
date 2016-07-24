<div class="sideBar_section">
    <div class="sideBar_sectionItem">
        <div>
            @if(Auth::user()->subscribe->checkHasSubscribed($question->id, 'question'))
                <button type="button" class="btn btn-warning"
                        onclick="show_question_subscribe(this, '{{ $question->id }}')">
                    Unsubscribe</button>
            @else
                <button type="button" class="btn btn-success"
                        onclick="show_question_subscribe(this, '{{ $question->id }}')"
                    {{ $question->isClosed() ? 'disabled' : '' }}
                >
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
        <div class="margin-top">
            <h5>Share this Question</h5>
            <span class='st_facebook_large' displayText='Facebook'></span>
            <span class='st_twitter_large' displayText='Tweet'></span>
            <span class='st_linkedin_large' displayText='LinkedIn'></span>
            <span class='st_pinterest_large' displayText='Pinterest'></span>
            <span class='st_email_large' displayText='Email'></span>
        </div>
    </div>
</div>

<div class="sideBar_section">
    <div class="sideBar_sectionItem">
        <h4>You might also be interested in:</h4>
        @foreach($question->alsoInterestQuestions as $interest_question)
            <p><a href="{{ action('QuestionController@show', $interest_question->id) }}">{{ $interest_question->title }}</a> {{ $interest_question->answers()->count() }} answers</p>
        @endforeach
    </div>

</div>

<div class="sideBar_section">
    <div class="sideBar_sectionItem">
            <span class="font-greyLight">
                This question has been viewed <strong>{{ $question->hit->total }}</strong> times.
            </span>
            <div>
                <a href="/question/{{ $question->id }}/log" class="font-greyLight">Show Question Edit History</a>
            </div>
    </div>
</div>

@if(Auth::user()->operation(14))
    @if ($question->isClosed())
        <div class="sideBar_section">
            <div class="sideBar_sectionItem">
                <button class="btn btn-warning"
                        data-action="open"
                        data-type="question"
                        data-id="{{ $question->id }}"
                >Re-open Question</button>
            </div>
        </div>
    @else
        <div class="sideBar_section">
            <div class="sideBar_sectionItem">
                <button class="btn btn-danger"
                        data-action="close"
                        data-type="question"
                        data-id="{{ $question->id }}"
                >Close Question</button>
            </div>
        </div>
    @endif
@endif
