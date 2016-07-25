@extends('layouts.question')


@section('left')
    @include('partials._question_show_head', ['question' => $question, 'answerOption' => false])

    <div class="col-md-12">
        <div class="clearfix">
            <div class="float-left">
                Question Edit History
            </div>
            <div class="float-right">
                <a href="/question/{{ $question->id }}">&lt;&lt; Back to the question</a>
            </div>
        </div>

        <hr>

        {{--current answer log here--}}
        <div id="question_log_content">
            <p class="text-center font-greyLight">This question has no editing history.</p>
        </div>

        <div id="question_log_nav" class="text-center">

        </div>
    </div>

@endsection


@section('right')
    @include('partials._question_side', ['question' => $question])
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            showQuestionLogPage('question_log', null, '{{ $question->id }}', 1, null);

            invite_search_box('{{ $question->id }}');
        });
    </script>

    {{--// js diff support--}}
    {!! Html::script('js/jsdiff/diff.min.js') !!}
    {!! Html::script('js/history.js') !!}
@endsection
