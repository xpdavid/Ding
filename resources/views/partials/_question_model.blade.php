{{--// ask qeustion model--}}
<div class="modal fade" id="ask_question" tabindex="-1" role="dialog" aria-labelledby="ask_question">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Ask A Question</h4>
            </div>
            <div class="modal-body">
                <p>Ding is a friendly and helpful community. We hope that with the help of this forum, everyone can find answers to their question. To craft a good question, please make your question title clear and question description concise. </p>
                <p>For more details on how to craft a good question, please refer to the post on our blog <a href="http://blog.nusding.info/index.php/2016/07/12/how-to-post-a-good-question/">Here</a>. </p>
                <p>Of course, a good reward will always make your question more attractive and responses more prompt. So now let's proceed to our first step!</p>
                <div class="form-group">
                    <label for="search_question"><strong>Do a search before asking your question</strong></label>
                    <input type="text" class="form-control" id="ask_question_input" placeholder="Type your keywords" autocomplete="off">
                </div>
            </div>
            <div class="modal-footer">
                <div class="pull-left" id="_question_draft_status">

                </div>
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
                               placeholder="Type your keywords"
                               data-type="_question_detail_draft"
                               data-key="question_title"
                               autocomplete="off"
                        >
                        <p class="text-danger float-left noneDisplay" id="_question_title_error">Bruh, You must provide question title (At least 5 characters).</p>
                    </div>
                    <table class="table" id="_question_title_search_table"
                           data-parent="_question">
                        <tbody>
                        </tbody>
                    </table>
                    <div class="form-group">
                        <label for="_question_detail">Question Detail (optional)</label>
                        <textarea class="form-control"
                                  rows="3" id="_question_detail" name="question_detail"
                                  data-autosave="false"
                                  data-parent="_question"
                                  data-edit="false"
                                  data-data_change="autosave"
                                  data-ask="true"
                                  data-draft_url="/question/draft"
                        ></textarea>
                        <input type="hidden" name="question_draft_id" data-type="_question_detail_draft" data-key="id" data-value=""
                            id="_question_detail_draft_id"
                        >
                    </div>
                    <div class="form-group">
                        <label for="_question_topic">Topics</label>
                        <select class="form-control"
                                multiple="multiple"
                                tabindex="-1"
                                aria-hidden="true"
                                name="question_topics[]"
                                id="_question_topics"
                                data-type="_question_detail_draft"
                                data-key="question_topics"
                        >
                        </select>
                        <p class="text-danger float-left noneDisplay" id="_question_topics_error">Please select some topics for your question</p>
                    </div>

                    <div class="form-group" id="_question_reward_wrapper">
                        <label for="_question_reward"><strong>Reward (Optional)</strong></label>
                        <div class="input-group">
                            <input type="text" class="form-control"
                                   id="_question_reward"
                                   name="question_reward"
                                   placeholder="Reward (Integer Only)"
                                   data-type="_question_detail_draft"
                                   data-key="question_reward"
                                   autocomplete="off"
                            >
                            <span class="input-group-addon">Exp</span>
                        </div>
                        <p class="help-block">Question with higher reward may receive quicker responses.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div id="_question_new_question">
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <span data-parent="_question" data-ask="Ask(Publish)" data-edit="Edit"></span>
                    </button>
                    <div class="margin-top text-warning" data-parent="_question" data-ask=""
                          data-edit="Please note that your edit will be recorded">
                    </div>
                </div>
                <input type="hidden" name="question_edit_id" value="" id="_question_edit_id" >
            {{ Form::close() }}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->