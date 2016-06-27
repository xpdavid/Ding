
var notificationDay = 1;
var homeQuestionPage = 1;
var subscribedQuestionPage = 1;
var subscribedQuestionItemInPage = 15;
var invitationQuestionPage = 1;
var invitationQuestionItemInPage = 15;
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
        default :
            return ;
    }
    // send ajax request to server
    $.post('/' + type, request, function(results) {
        if (results.status) {
            $('#' + type).append(template(results));
            $('#' + type + '_more').prop('disabled', false);
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
 * Once click of the vote button than show the vote up and vote down button
 */
function userHome_vote_button_trigger(clickObject, show_id) {
    $(clickObject).hide();
    $('#' + show_id).show();
}


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

