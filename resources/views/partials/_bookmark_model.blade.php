<!-- Modal -->
<div class="modal fade" id="bookmark_modal" tabindex="-1" role="dialog" aria-labelledby="bookmark_modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Bookmark</h4>
            </div>
            <div class="modal-body clearfix">
                <div>

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#bookmark_add" aria-controls="bookmark_add" role="tab" data-toggle="tab">Add to</a></li>
                        <li role="presentation"><a href="#bookmark_create" aria-controls="bookmark_create" role="tab" data-toggle="tab">Create Bookmark</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="bookmark_add">
                            <div class="bookmark_modal_list" id="bookmark_modal_list">
                                <div class="bookmark_modal_item text-center noneDisplay" id="bookmark_modal_list_empty">
                                    No Bookmark, Click To Add
                                </div>
                            </div>
                            <div class="float-right">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="bookmark_create">
                            <br>
                            <div class="form-group">
                                <label for="bookmark_new_name">Name</label>
                                <input type="email" name="bookmark_new_name" class="form-control" id="bookmark_new_name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="bookmark_new_description">Description (Optional)</label>
                                <textarea type="password" name="bookmark_new_description"
                                       class="form-control" id="bookmark_new_description" placeholder="Description"></textarea>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" value="true" name="bookmark_new_public" checked="checked"> Pulbic
                                    <span class="font-greyLight">
                                        You can change bookmark status to private only if there are no  subscribers to this bookmark
                                    </span>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" value="false" name="bookmark_new_public" > Private
                                    <span class="font-greyLight">
                                        You can only view the bookmark by yourself
                                    </span>
                                </label>
                            </div>
                            <div class="float-right">
                                <a href="#" onclick="$('a[href=\'#bookmark_add\']').tab('show')"
                                class="space-right">Cancel</a>
                                <button type="button" class="btn btn-primary"
                                onclick="bookmark_create()">Save</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>