
var notificationDay = 1;
var homeQuestionPage = 1;
var subscribedQuestionPage = 1;
var subscribedQuestionItemInPage = 15;
var invitationQuestionPage = 1;
var invitationQuestionItemInPage = 15;
var draftQuestionPage = 1;
var draftQuestionItemInPage = 10;
var draftAnswerPage = 1;
var draftAnswerItemInPage = 10;
/**
 * Send ajax request to get items
 * @param type
 */
function genericGet(type) {
    var request = {};
    var increment = null;
    var template = null;
    var judge = null;
    // diabled the more button first
    $('#' + type + '_more').prop('disabled', true);
    switch (type) {
        case 'notification' :
            request = {
                day :  notificationDay
            };
            increment = function() { notificationDay++ };
            template = Handlebars.templates['_notification_day.html'];
            break;
        case 'home' :
            request = {
                page : homeQuestionPage
            };
            increment = function() { homeQuestionPage++ };
            template = Handlebars.templates['_userCenter_home_item.html'];
            break;
        case 'subscribed' :
            request = {
                page : subscribedQuestionPage,
                itemInPage : subscribedQuestionItemInPage
            };
            increment = function() { subscribedQuestionPage++ };
            template = Handlebars.templates['_subscribed_question.html'];
            break;
        case 'invitation' :
            request = {
                page : invitationQuestionPage,
                itemInPage : invitationQuestionItemInPage
            };
            increment = function() { invitationQuestionPage++ };
            template = Handlebars.templates['_invitation_question.html'];
            break;
        case 'draft_question' :
            request = {
                page : draftQuestionPage,
                itemInPage : draftQuestionItemInPage,
                type : 'question'
            };
            increment = function() { draftQuestionPage++ };
            template = Handlebars.templates['_draft_question.html'];
            break;
        case 'draft_answer' :
            request = {
                page : draftAnswerPage,
                itemInPage : draftAnswerItemInPage,
                type : 'answer'
            };
            increment = function() { draftQuestionPage++ };
            template = Handlebars.templates['_draft_answer.html'];
            break;
        default :
            return ;
    }
    // send ajax request to server
    $.post('/' + type, request, function(results) {
        if (results.status) {
            $('#' + type).append(template(results));
            $('#' + type + '_more').prop('disabled', false);
            // rerender math symbols
            rerenderMath(type);
            // execute incremet function
            if (increment && typeof increment == "function") {
                increment();
            }
        } else {
            $('#' + type + '_more').html('No More Already');
        }
    });
}



/**
 * Subscribe question in user homepage
 * @param event
 */
function userHome_subscribe_question(event, textID, question_id, countID) {
    if (event) {
        event.preventDefault();
    }
    var $link = $('#' + textID);
    if ($link.hasClass('subscribed')) {
        subscribeQuestion(question_id, 'unsubscribe', function(results) {
            $link.html('Subscribe Question');
            $link.removeClass('subscribed');
            if (countID) {
                $('#' + countID).html(results.numSubscriber);
            }
        });
    } else {
        subscribeQuestion(question_id, 'subscribe', function(results) {
            $link.html('Unsubscribe');
            $link.addClass('subscribed');
            if (countID) {
                $('#' + countID).html(results.numSubscriber);
            }
        });
    }
}

/**
 * Send ajax request to ignore invitation
 * @param invitation_id
 */
function ignoreInvitation(invitation_id) {
    swal({
        title: "Are you sure?",
        text: "We will mark this invitation as read. <br>Don't worry, you can still find it in notification page",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, ignore it!",
        closeOnConfirm: true,
        html: true
    }, function(){
        notificationOperation(invitation_id, 'read', null);
        return true;
    });
}

/**
 * Bind delete draft button
 */
function deleteDraft_process() {
    $('body').on('click', '[data-action="delete_draft"]', function(e) {
        if (e) {
            e.preventDefault();
        }
        var $object = $(this);
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this draft! Please note that history function are only for published answer/question",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        }, function(){
            $.post('/draft/' + $object.data('id') + '/delete', {
                type : $object.data('type')
            }, function() {
                // refresh page
                swal({
                    title : "Deleted!",
                    text : "Your draft has been deleted.",
                    type : "success"
                }, function() {
                    window.location.reload();
                });
            });
        });
    })
}

/**
 * bind publish button to ajax
 */
function publishDraft_process() {
    $('body').on('click', '[data-action="publish_draft"]', function(e) {
        if (e) {
            e.preventDefault();
        }
        var $object = $(this);

        $.post('/draft/' + $object.data('id') + '/publish', {
            type: $object.data('type')
        }, function (results) {
            // refresh page
            swal({
                title: "Published!",
                text: "Click to view.",
                type: "success"
            }, function () {
                if (results.status) {
                    window.location.replace(results.location);
                }
            });
        });
    });
}

