@extends('layouts.question')

@section('left')
    <div class="clearfix">
        <a href="#" class="header_left"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>Editor Recommendation</a>
        <a class="header_right">More &gt;&gt;</a>
    </div>
    <hr class="small_hr">

    @foreach($recommends as $key => $question)
        @if($key < 2)
            <div class="questions_questionLayout">
                <div class="questions_side">
                    <img src="image/sample_icon.png" alt="" class="img-rounded">
                    <button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> <br>2</button>
                </div>

                <div class="font-bold">
                    <a href="{{ action('QuestionController@show', $question->id) }}"> {{ $question->title }} </a>
                </div>

                <div class="questions_questionContent">
                    {{ $question->excerpt }}
                    <a href="#"> show all </a>
                </div>

                <div class="horizontal_item">
                    <a href="#"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>{{ $question->answers->count() }} Answer(s)</a>
                    <a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>{{ $question->replies->count() }} Comment(s)</a>
                    <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Substricbe</a>
                </div>
            </div>

            <hr class="small_hrLight">
        @else
            <div class="font-bold clearfix">
                <a href="#" class="float-left">{{ $question->title }}</a>
                <div class="font-grey float-right space-right">From Topic:
                    @if($question->topics->count() <= 3)
                        @foreach($question->topics as $topic)
                            <a href="{{ action('QuestionController@show', $question->id) }}">{{ $topic->name }}</a>
                        @endforeach
                    @else
                        @foreach($question->topics->take(3) as $topic)
                            <a href="{{ action('QuestionController@show', $question->id) }}">{{ $topic->name }}</a>
                        @endforeach
                        etc..
                    @endif
                </div>
            </div>

            <hr class="small_hrLight">
        @endif
    @endforeach

    <br>
    <!-- Nav tabs for hot topics -->
    <div>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#hotInWeek" aria-controls="hotInWeek" role="tab" data-toggle="tab">Hot in Week</a></li>
            <li role="presentation"><a href="#hotInMonth" aria-controls="hotInMonth" role="tab" data-toggle="tab">Hot in Month</a></li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="hotInWeek">
                @foreach($current_day_hot as $key => $question)
                    <div class="questions_questionLayout">
                        <div class="questions_side">
                            <img src="image/sample_icon.png" alt="" class="img-rounded">
                            <button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> <br>2</button>
                        </div>

                        <div class="font-bold">
                            <a href="{{ action('QuestionController@show', $question->id) }}"> {{ $question->title }} </a>
                        </div>

                        <div class="questions_questionContent">
                            {{ $question->excerpt }}
                            <a href="#"> show all </a>
                        </div>

                        <div class="horizontal_item">
                            <a href="#"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>{{ $question->answers->count() }} Answer(s)</a>
                            <a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>{{ $question->replies->count() }} Comment(s)</a>
                            <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Substricbe</a>
                        </div>
                    </div>

                    <hr class="small_hrLight">
                @endforeach
            </div>
            <div role="tabpanel" class="tab-pane" id="hotInMonth">
                @foreach($current_month_hot as $key => $question)
                    <div class="questions_questionLayout">
                        <div class="questions_side">
                            <img src="image/sample_icon.png" alt="" class="img-rounded">
                            <button type="button" class="btn btn-warning"><span class="glyphicon glyphicon-lock" aria-hidden="true"></span> <br>2</button>
                        </div>

                        <div class="font-bold">
                            <a href="#"> {{ $question->title }} </a>
                        </div>

                        <div class="questions_questionContent">
                            {{ $question->excerpt }}
                            <a href="#"> show all </a>
                        </div>

                        <div class="horizontal_item">
                            <a href="#"><span class="glyphicon glyphicon-check" aria-hidden="true"></span>{{ $question->answers->count() }} Answer(s)</a>
                            <a href="#"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>{{ $question->replies->count() }} Comment(s)</a>
                            <a href="#"><span class="glyphicon glyphicon-star-empty" aria-hidden="true"></span>Substricbe</a>
                        </div>
                    </div>

                    <hr class="small_hrLight">
                @endforeach

            </div>
        </div>

    </div>
@endsection


@section('right')
    <div class="sideBar_section">
        <div class="clearfix">
            <div class="float-left">Hot Topics</div>
            <div class="float-right font-grey"><a href="#">More &gt;&gt;</a></div>
        </div>
    </div>

    <div class="sideBar_section">
        <div class="clearfix sideBar_sectionItem">
            <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
            <a href="#">Food Science</a>
            <div class="font-grey">3842 substribe</div>
        </div>
        <div class="clearfix sideBar_sectionItem noborder">
            <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
            <a href="#">Food Science</a>
            <div class="font-grey">3842 substribe</div>
        </div>
    </div>

    <br>
    <div class="sideBar_section">
        <div class="clearfix">
            <div class="float-left">Hot Bookmarks</div>
            <div class="float-right font-grey"><a href="#">Try Again? &gt;&gt;</a></div>
        </div>
    </div>

    <div class="sideBar_section noborder">
        <div class="clearfix sideBar_sectionItem">
            <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
            <a href="#">Food Science</a>
            <div class="font-grey">3842 substribe</div>
        </div>
        <div><a href="#">Does't alcohal harm?</a></div>
        <div class="clearfix sideBar_sectionItem">
            <img class="img-rounded float-left space-right avatar-img" src="topics.png" alt="...">
            <a href="#">Food Science</a>
            <div class="font-grey">3842 substribe</div>
        </div>
    </div>
@endsection