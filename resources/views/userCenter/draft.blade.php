@extends('layouts.userCenter')


@section('content')

    <h4 class="font-black">Draft ({{ $draft_question_count + $draft_answer_count }})</h4>
    <hr class="small_hr">


    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#draft_question_tab" aria-controls="draft_question_ta" role="tab" data-toggle="tab">
                    Question <span class="badge">{{ $draft_question_count }}</span></a></li>
            <li role="presentation">
                <a href="#draft_answer_tab" aria-controls="draft_answer_tab" role="tab" data-toggle="tab">
                    Answer <span class="badge">{{ $draft_answer_count }}</span></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="draft_question_tab">
                <div id="draft_question" class="margin-top">

                </div>
                <button class="btn btn-default btn-block" type="button" id="draft_question_more" onclick="genericGet('draft_question');">More..</button>

            </div>
            <div role="tabpanel" class="tab-pane" id="draft_answer_tab">
                <div id="draft_answer" class="margin-top">

                </div>
                <button class="btn btn-default btn-block" type="button" id="draft_answer_more" onclick="genericGet('draft_answer');">More..</button>
            </div>
        </div>
    </div>

    <div id="draft">

    </div>

    <br>
@endsection

@section('javascript')
    <script type="text/javascript">
        $(function() {
            genericGet('draft_question');
            genericGet('draft_answer');
            deleteDraft_process();
            publishDraft_process();
        });
    </script>
@endsection