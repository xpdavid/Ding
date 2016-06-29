/**
 * Set up ajax request header for laravel
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


/**
 * scroll to the element
 *
 * @param event
 * @param element_id
 */
function scroll_to(element_id, event) {
    if (event) {
        event.preventDefault();
    }

    $('html,body').animate({scrollTop: $('#' + element_id).offset().top - 200}, 1000);
}

/**
 * toggle display of the element with id
 *
 * @param id
 */
function toggle(id, event) {
    if (event) {
        event.preventDefault();
    }
    $('#' + id).toggle();
}

/**
 * Send AJAX request to subscribe a topic
 *
 * @param topic_id
 */
function subscribeTopic(topic_id, op, callback) {
    $.post('/subscribe/topic/' + topic_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Send ajax request to subscribe a question
 *
 * @param question_id
 * @param op
 * @param callback
 */
function subscribeQuestion(question_id, op, callback) {
    $.post('/subscribe/question/' + question_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Send ajax request to subscribe a user
 *
 * @param user_id
 * @param op
 * @param callback
 */
function subscribeUser(user_id, op, callback) {
    $.post('/subscribe/user/' + user_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Send ajax request to subscribe a bookmark
 *
 * @param bookmark_id
 * @param op
 * @param callback
 */
function subscribeBookmark(bookmark_id, op, callback) {
    $.post('/subscribe/bookmark/' + bookmark_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}


/**
 * AJAX send operation to server
 *
 * @param notification_id
 * @param op
 * @param callback
 */
function notificationOperation(notification_id, op, callback) {
    $.post('/notification/operation', {
        op : op,
        id : notification_id
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Highlight a element, if rollback is true, after two second, it will auto 'dehighlight'.
 *
 * @param elementID
 * @param rollback(optional)
 */
function highlight(elementID, rollback) {
    $('#' + elementID).addClass('highlight');
    // hightlight 2 second
    if (rollback) {
        setTimeout(function(){
            $('#' + elementID).removeClass('highlight');
        }, 2000);
    }

}
