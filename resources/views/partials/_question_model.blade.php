{{--// ask qeustion model--}}
<div class="modal fade" id="ask_question" tabindex="-1" role="dialog" aria-labelledby="ask_question">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ask Your Question</h4>
            </div>
            <div class="modal-body">
                <p>Ding is a helpful community. We hope everyone get their desired answers.</p>
                <p>Good question will get quick response. Please refer to the question 'How to ask a good question'</p>
                <p>Please make your question clear and specific... bla bla</p>
                <div class="form-group">
                    <label for="search_question"><strong>Please search before asking</strong></label>
                    <input type="text" class="form-control" id="ask_question_input" placeholder="Type your keywords" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{--// qeustion model--}}
<div class="modal fade" id="_question" tabindex="-1" role="dialog" aria-labelledby="_question">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" data-parent="_question" data-ask="Please Provide Question Detail" data-edit="Edit Question"></h4>
            </div>
            {{ Form::open(['url'=> '', 'method' => 'POST', 'data-parent' => '_question',
            'data-ask' => url('/question/ask'),
            'data-edit' => url('/question/update'), 'data-change' => 'action', 'id' => '_question_form']) }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="search_question"><strong>Title</strong></label>
                        <input type="text" class="form-control"
                               id="_question_title"
                               name="question_title"
                               placeholder="Type your keywords">
                        <p class="text-danger float-left noneDisplay" id="_question_title_error">Bruh, You must provide question title (5 more characters).</p>
                    </div>
                    <table class="table" id="_question_title_search_table"
                           data-parent="_question">
                        <tbody>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label for="_question_detail">Question Detail (optional)</label>
                        <textarea class="form-control"
                                  rows="3" id="_question_detail" name="question_detail"></textarea>

                    </div>
                    <div class="form-group">
                        <label for="_question_topic">Topics</label>
                        <select class="form-control"
                                multiple="multiple"
                                tabindex="-1"
                                aria-hidden="true"
                                name="question_topics[]"
                                id="_question_topics"
                        >
                        </select>
                        <p class="text-danger float-left noneDisplay" id="_question_topics_error">Please select some topics for your question</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span data-parent="_question" data-ask="Ask" data-edit="Edit"></span>
                    </button>
                    <span data-parent="_question" data-ask=""
                          data-edit="Please note that your edit will be recorded">
                    </span>
                </div>
                <input type="hidden" name="question_id" value="" id="_question_id" >
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->