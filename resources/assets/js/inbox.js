/**
 * Created by XP on 24/5/16.
 */
function hideConversation($id) {
    swal({
        title: "Are you sure to hide the conversation?",
        text: "You will not receive any update from this conversation unless you are added again by someone else!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Hide it!",
        closeOnConfirm: false },
        function(){
            $.post('/inbox/' + $id, {
                '_method' : 'DELETE'
            }, function (result) {
                try {
                    if (result.status == 1) {
                        swal({
                            title: 'Success',
                            text: 'You will never receive any messages from this conversation',
                            type: 'success'
                        }, function() {
                            location.reload();
                        });
                    } else {
                        throw "Error";
                    }
                } catch(e) {
                    swal("Error", "Sever has a question :(", "error");
                }
            }).fail(function () {
                swal("Error", "Sever has a question :(", "error");
            });
        });
}