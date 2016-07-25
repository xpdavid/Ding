@extends('layouts.question')


@section('left')
    @include('partials._question_show_head', ['question' => $question, 'answerOption' => false])

    <div class="col-md-12">
        <div class="clearfix">
            <div class="float-left">
                {{ $answer->owner->name }}'s answer log.
            </div>
            <div class="float-right">
                <a href="/answer/{{ $answer->id }}">&lt;&lt; Back to the answer</a>
            </div>
        </div>

        <hr>

        {{--current answer log here--}}
        <div id="answer_log_content">
            <p class="text-center font-greyLight">This answer has no editing history.</p>
        </div>

        <div id="answer_log_nav" class="text-center">

        </div>
    </div>

@endsection


@section('right')
    @include('partials._answer_side', ['answer' => $answer, 'question' => $question])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showAnswerLogPage('answer_log', null, '{{ $answer->id }}', 1, null);

            invite_search_box('{{ $question->id }}');
        });
    </script>

    {{--// js diff support--}}
    {!! Html::script('js/jsdiff/diff.min.js') !!}
    {!! Html::script('js/history.js') !!}
@endsection
