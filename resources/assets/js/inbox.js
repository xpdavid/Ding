/**
 * Send ajax request to hide the conversation
 * 
 * @param $id
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

/**
 * Auto complete for select user
 */
function receive_message_users_autocomplete() {
    $('#receive_message_users').select2({
        width: '100%',
        dropdownAutoWidth : true,
        placeholder: 'select peoples',
        minimumInputLength : 1,
        ajax: {
            url: "/people/autocomplete",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    max_match: 10,
                    use_similar: 0
                };
            },
            processResults: function(data, params) {
                var process_data = [];
                $.each(data, function(index, value) {
                    process_data.push({
                        id : index,
                        text : value
                    });
                });
                return {
                    results : process_data
                }
            }
        },
    });
}

$(function() {
    receive_message_users_autocomplete();
})

