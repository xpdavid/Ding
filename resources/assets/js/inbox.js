/**
 * Created by XP on 24/5/16.
 */
function hideConversation($id) {
    swal({
        title: "Are you sure to hide the conversation?",
        text: "You will not receive any update for this conversation unless you are added again by someone else!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Hide it!",
        closeOnConfirm: false },
        function(){
            swal("Deleted!", "Your imaginary file has been deleted.", "success");
        });
}