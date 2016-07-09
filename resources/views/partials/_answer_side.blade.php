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
            <h4>Relevant Question</h4>
            @foreach($question->alsoInterestQuestions as $also_interest_question)
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
            <h4>Answer Status</h4>
            <div class="font-greyLight">Created at {{ $answer->created_at }}</div>
            <div class="font-greyLight">Updated at {{ $answer->updated_at }}</div>
            <div class="font-greyLight"><a href="/answer/{{ $answer->id }}/log">Show Answer Log</a></div>
            <div class="font-greyLight">Belonging Question has being view <strong>{{ $answer->hit->total }}</strong> times</div>
        </div>
    </div>
</div>