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
 * ajax request to send message
 */
function sendMessage() {
    if ($('#receive_message_users').val() == null) {
        showError('receive_message_users', true);
        return ;
    }
    var text = $(tinyMCE.get('message_content').getContent()).text();
    text = text.replace(' ', '');
    if (text.length == 0) {
        showError('message_content', true);
        return ;
    }
    $.post('/inbox', {
        users : $('#receive_message_users').val(),
        content : tinyMCE.get('message_content').getContent(),
        can_reply : $('#message_can_reply').is(':checked') ? 1 : 0
    }, function(results) {
        if (results.error) {
            $('#receive_message_users_error').html(results.error);
        } else {
            window.location.replace(results.location);
        }
    });
}

/**
 * Save user message to conversation
 *
 * @param id
 */
function sendReply(id) {
    var text = $(tinyMCE.get('message_content').getContent()).text();
    text = text.replace(' ', '');
    if (text.length == 0) {
        showError('message_content', true);
        return ;
    }
    $.post('/inbox/' + id, {
        _method : 'PATCH',
        reply : tinyMCE.get('message_content').getContent(),
    }, function(results) {
        window.location.replace(results.location);
    });
}


// init message content
$(function() {
    tinymce.init({
        menubar : false,
        selector: '#message_content',
        paste_as_text: true,
        browser_spellcheck: true,
        plugins: 'advlist autolink link fullscreen paste',
        toolbar: ['undo redo | bold italic underline | bullist numlist | link | fullscreen',],
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        elementpath : false,
        setup: function (editor) {
            editor.on('FullscreenStateChanged', function(e) {
                if (e.state) {
                    // hide nav bar when have fullscreen mode
                    $('.navbar').fadeOut();
                } else {
                    $('.navbar').show();
                }
            });
        },
        // increase the font-size
        content_css : '/js/tinymce/content.css',
    });

    // autocomplete for receive message users
    user_name_autocomplete('receive_message_users');
});

