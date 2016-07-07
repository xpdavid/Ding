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
<div class="modal fade" id="_question_detail" tabindex="-1" role="dialog" aria-labelledby="_question_detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" data-parent="_question_detail" data-ask="Please Provide Question Detail" data-edit="Edit Question"></h4>
            </div>
            {{ Form::open(['url'=> '', 'method' => 'POST',
            'data-toggle' => "validator", 'data-parent' => '_question_detail',
            'data-ask' => url('/question/ask'),
            'data-edit' => url('/question/update'), 'data-change' => 'action']) }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="search_question"><strong>Title</strong></label>
                        <input type="text" class="form-control"
                               id="_question_detail_input"
                               name="question_title"
                               data-error="You must provide question title"
                               required
                               placeholder="Type your keywords">
                        <div class="help-block with-errors"></div>
                    </div>
                    <table class="table" id="_question_detail_search_table"
                           data-parent="_question_detail">
                        <tbody>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label for="question_detail">Question Detail (optional)</label>
                        <textarea class="form-control"
                                  rows="3" id="question_detail" name="question_detail"></textarea>

                    </div>
                    <div class="form-group">
                        <label for="question_topic">Topics</label>
                        <div class="help-block with-errors"></div>
                        <select class="form-control"
                                multiple="multiple"
                                tabindex="-1"
                                aria-hidden="true"
                                name="question_topics[]"
                                id="question_topics"
                                required
                        >
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span data-parent="_question_detail" data-ask="Ask" data-edit="Edit"></span>
                    </button>
                    <span data-parent="_question_detail" data-ask=""
                          data-edit="Please note that your edit will be recorded">
                    </span>
                </div>
                <input type="hidden" name="edit_question_id" value="" id="_question_detail_question_id" >
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->