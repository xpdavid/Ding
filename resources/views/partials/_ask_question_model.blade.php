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
                    <input type="text" class="form-control" id="search_question" placeholder="Type your keywords">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="navbar_question_detail()">I don't find my desired question</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

{{--// ask qeustion model--}}
<div class="modal fade" id="ask_question_detail" tabindex="-1" role="dialog" aria-labelledby="ask_question_detail">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Please Provide Question Detail</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="search_question"><strong>Your Question may already have answers.</strong></label>
                    <input type="text" class="form-control" id="search_question" placeholder="Type your keywords">
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <td>Is quick sort a stable sorting algorithm? - 2 answers</td>
                        </tr>
                        <tr>
                            <td>How to use two stack to implements Queue? - 4 answers</td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <label for="question_detail">Question Detail (optional)</label>
                    <textarea class="form-control" rows="3" id="question_detail" name="question_detail"></textarea>
                </div>
                <div class="form-group">
                    <label for="question_topic">Topics</label>
                    <select class="form-control" multiple="multiple" tabindex="-1" aria-hidden="true" name="question_topics" id="question_topics">

                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Ask</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->