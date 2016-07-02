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
 * Send ajax call to subscribe a question
 *
 * @param event
 * @param clickObject
 * @param topic_id
 */
function topics_subscribe(event, clickObject, topic_id) {
    event.preventDefault();
    var $click = $(clickObject);
    if ($click.hasClass('active')) {
        // is already subscribed
        subscribeTopic(topic_id, 'unsubscribe', function() {
            // remove class
            $click.removeClass('active');
            // show subscribe text
            $click.find('span:nth-child(1)').show();
            $click.find('span:nth-child(2)').text('');
            $click.find('span:nth-child(2)').text('Subscribe')
        });
    } else {
        // current operation is subscribe the topic
        subscribeTopic(topic_id, null, function() {
            // add active class
            $click.addClass('active');
            // show unsubscribe text
            $click.find('span:nth-child(1)').hide();
            $click.find('span:nth-child(2)').text('');
            $click.find('span:nth-child(2)').text('Unsubscribe')
        });
    }
}

/**
 * trigger subscribe button in specific topic page
 *
 * @param clickObject
 * @param topic_id
 */
function topic_show_subscribe(clickObject, topic_id) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeQuestion(topic_id, null, function() {
            $button.html('Unsubscribe');
            $button.removeClass('btn-success');
            $button.addClass('btn-warning');
        });
    } else {
        // has subscribed
        subscribeQuestion(topic_id, 'unsubscribe', function() {
            $button.html('Subscribe');
            $button.removeClass('btn-warning');
            $button.addClass('btn-success');
        });
    }
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
 * Subscribe user by click button
 * @param clickObject
 * @param user_id
 */
function user_button_subscribe(clickObject, user_id, textID) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeUser(user_id, null, function(results) {
            $button.html('Unsubscribe');
            $button.removeClass('btn-success');
            $button.addClass('btn-default');
            if (textID) {
                $('#' + textID).html(results.numSubscriber);
            }
        });
    } else {
        // has subscribed
        subscribeUser(user_id, 'unsubscribe', function(results) {
            $button.html('Subscribe');
            $button.removeClass('btn-default');
            $button.addClass('btn-success');
            if (textID) {
                $('#' + textID).html(results.numSubscriber);
            }
        });
    }
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

/**
 * Highlight the keyword when the search is done
 *
 * @param text
 * @param keyword
 */
function highlight_keyword(text, keyword) {
    var reg = new RegExp(keyword, 'gi');
    return text.replace(reg, function(str) {return '<em>'+str+'</em>'});
}


/**
 * Auto complete for select user
 */
function user_name_autocomplete(id) {
    $('#' + id).select2({
        width: '100%',
        dropdownAutoWidth : true,
        placeholder: 'select peoples',
        minimumInputLength : 1,
        ajax: {
            url: "/api/autocomplete",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    queries: [{
                        type : 'people',
                        term : params.term, // search term
                        max_match: 10,
                        use_similar: 0,
                    }]
                };
            },
            processResults: function(data, params) {
                var process_data = [];
                $.each(data, function(index, item) {
                    process_data.push({
                        id : item.id,
                        text : item.name
                    });
                });
                return {
                    results : process_data
                }
            }
        },
    });
}
//# sourceMappingURL=main.js.map
