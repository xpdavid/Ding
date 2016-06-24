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
function scroll_to(event, element_id) {
    event.preventDefault();

    $('html,body').animate({scrollTop: $('#' + element_id).offset().top}, 1000);
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

//# sourceMappingURL=main.js.map
