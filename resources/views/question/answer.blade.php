@extends('layouts.question')



@section('left')
    @include('partials._question_show_head', ['question' => $question, 'answerOption' => false])


    <div class="col-md-12">
        <div class="clearfix">
            <div class="float-left"><a href="/question/{{ $question->id }}">Show all {{ $question->answers->count() }} answer(s)</a></div>
            <div class="float-right">
                @if ($answer->isClosed())
                    <h5 class="clear_margin">
                        <span class="label label-warning">Closed Answer</span>
                    </h5>
                @endif
            </div>
        </div>

        <hr>

        {{--current answer here--}}
        <div id="answer">

        </div>

        @if ($answer->isClosed())
            <div class="well margin-top">
                The question is closed for some reason:<br>
                <strong>{{ $answer->closeReason() }}</strong>
            </div>
        @endif

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
    @include('partials._answer_side', ['answer' => $answer, 'question' => $question])
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