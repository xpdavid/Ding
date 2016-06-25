/**
 * send ajax request to get notification item
 */
function getOneDayNotification() {
    $('#notification_more').prop('disabled', true);
    getOneDayNotificationHelper(function(results) {
        if (!results.date) {
            $('#notification_more').html('No More Already');
        } else {
            $('#notification_more').prop('disabled', false);
        }
        notificationDay++;
    });
}
/**
 * Helper function for send ajax request to get norification
 */
var notificationDay = 1;
function getOneDayNotificationHelper(callback) {
    $.post('/notification', {
        day :  notificationDay
    }, function(results) {
        if (results.date) {
            var template = Handlebars.templates['_notification_day.html'];
            $('#notification').append(template(results));
        }
        if (callback && typeof callback == "function") {
            callback(results);
        }
    });
}

/**
 * Ajax to get home question
 */
function getHomeQuestion() {
    // call ajax method
    getHomeQuestionHelper(function() {
        $('#home_question_more').prop('disabled', true);
    }, function(results) {
        if (results.length == 0) {
            $('#home_question_more').html('No More Already');
        } else {
            $('#home_question_more').prop('disabled', false);
        }
        homeQuestionPage++;
    });
}

/**
 * Ajax helper to get home question
 * @param callback
 */
var homeQuestionPage = 1;
function getHomeQuestionHelper(before, callback) {
    if (before && typeof before == "function") {
        before();
    }
    $.post('/home', {
        page : homeQuestionPage
    }, function(results) {
        if (results.length) {
            var template = Handlebars.templates['_userCenter_home_item.html'];
            $('#user_home_question').append(template({
                questions : results
            }));
        }
        if (callback && typeof callback == "function") {
            callback(results);
        }
    });
}

/**
 * Subscribe question in user homepage
 * @param event
 */
function userHome_subscribe_question(event, textID, question_id) {
    if (event) {
        event.preventDefault();
    }
    var $link = $('#' + textID);
    if ($link.hasClass('subscribed')) {
        subscribeQuestion(question_id, 'unsubscribe', function() {
            $link.html('Subscribe Question');
            $link.removeClass('subscribed');
        });
    } else {
        subscribeQuestion(question_id, 'subscribe', function() {
            $link.html('Unsubscribe');
            $link.addClass('subscribed');
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