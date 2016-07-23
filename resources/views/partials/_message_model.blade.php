<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="sendModalLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Send Message</h4>
            </div>
            <div class="modal-body">
                <!-- User Form Input-->
                <div class="form-group">
                    {!! Form::label('users', 'User:') !!}
                    {!! Form::select('users[]',
                    isset($receiver_select) ? $receiver_select : []
                    , isset($receiver_select) ? array_keys($receiver_select) : null
                    , ['class' => 'form-control', 'id' => 'receive_message_users', 'multiple']) !!}
                    <div id="receive_message_users_error" class="text-danger noneDisplay">You must put receivers</div>
                </div>

                <!-- Content Form Input-->
                <div class="form-group">
                    {!! Form::label('content', 'Content:') !!}
                    {!! Form::textarea('content', null, ['class' => 'form-control', 'id' => 'message_content', 'rows' => 4]) !!}
                    <div id="message_content_error" class="text-danger noneDisplay">You must input message content</div>
                </div>


                <div class="checkbox {{ Auth::user()->operation(18) ? '' : 'noneDisplay' }}">
                    <label>
                        <input type="checkbox" id="message_can_reply" checked="checked"> Can Reply
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" onclick="sendMessage()" class="btn btn-primary">Send</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->