<!-- Modal -->
<div class="modal fade" id="_add_topic" tabindex="-1" role="dialog" aria-labelledby="_add_topic">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Add Topic</h4>
            </div>
            <form action="/topic/create" method="POST" id="_add_topic_form">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="_topic_name">Topic Name</label>
                            <input type="text" class="form-control" id="_topic_name" name="topic_name" placeholder="Topic Name" autocomplete="off">
                            <div id="_topic_name_error" class="text-danger"></div>
                        </div>
                        <div class="form-group">
                            <label for="_topic_description">Topic Description</label>
                            <textarea rows="3" class="form-control" id="_topic_description" name="topic_description" placeholder="Topic description"></textarea>
                            <p class="help-block">Please upload topic avatar after create the topic</p>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
</div>