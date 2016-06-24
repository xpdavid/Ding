/**
 * function: showComment
 *
 * @param event
 * @param type
 * @param item_id
 * @param base_id
 *
 * description: send post request to get all the replies of the current object
 */
function showComment(event, type, item_id, base_id) {
    if (event) {
        event.preventDefault();
    }
    var $link = $('#' + base_id + '_trigger')
    var clicked = $link.data('clicked');

    if (!clicked) {
        // display comment at the first click
        showCommentPage(base_id, type, item_id, 1);
        $link.data('clicked', 'true');
    }

    $('#' + base_id).toggle();

}

/**
 * when hover comment box, show operation div
 *
 * @param base_id
 */
function replyBindHoverShowOperation(base_id) {
    $('#' + base_id).find('.comment_item').each(function() {
        var element = $(this);
        element.hover(function() {
            element.find('.reply_op').show();
        }, function() {
            element.find('.reply_op').hide();
        })
    })
}

/**
 * Click page nav trigger update comment
 *
 * @param base_id
 * @param type
 * @param item_id
 * @param page
 * @param callback
 */
function showCommentPage(base_id, type, item_id, page, callback) {
    $.post('/reply/reply-list', {
        type : type,
        item_id : item_id,
        page : page,
    }, function(results) {
        if (results.data.length != 0) {
            var processResults = {
                replies : results.data
            };

            // compile template
            var template = Handlebars.templates['_reply_item.html'];
            // send data
            $('#' + base_id + '_content').html(template(processResults));

            // update nav bar
            $('#' + base_id + '_nav').html(compileCommentPageNav(page, results.pages, base_id, type, item_id));

            // check callback
            if(callback && typeof callback == "function"){
                callback();
            }

            // bind hover event
            replyBindHoverShowOperation(base_id);
        }

    });
}

/**
 * Compile page nav
 *
 * @param currPage
 * @param numPages
 * @param base_id
 * @param type
 * @param item_id
 * @returns {*}
 */
function compileCommentPageNav(currPage, numPages, base_id, type, item_id) {
    // generate prev link
    var prev = {};
    if (currPage > 1) {
        var prevPage = currPage - 1;
        prev.onclick = "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + prevPage + ")";
    } else {
        prev.class = "disabled";
    }

    // generate next link
    var next = {};
    if (currPage < numPages) {
        var nextPage = currPage + 1;
        next.onclick = "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + nextPage + ")";
    } else {
        next.class = "disabled";
    }

    // generate overall page
    var pages= [];
    var className, i;
    if (numPages <= 7) {
        // the total pages is less than 7, than output all.
        for(i = 1; i <= numPages; i++) {
            className = (i == currPage) ? 'active' : '';
            pages.push({
                onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + i + ")",
                name : i,
                class : className
            });
        }
    } else {
        if (currPage <= 5) {
            // the current page is from 1 to 5
            for(i = 1; i <= 5; i++) {
                className = (i == currPage) ? 'active' : '';
                pages.push({
                    onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + i + ")",
                    name : i,
                    class : className
                });
            }
            // push '...' elemement
            pages.push({
                name : '...',
                class : 'disabled'
            });
            // push last page
            pages.push({
                onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + numPages + ")",
                name : numPages
            });
        } else if (numPages - currPage <=5) {
            // the current page is within last 5 page
            // push first page
            pages.push({
                onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + 1 + ")",
                name : 1
            });
            // push '...' element
            pages.push({
                name : '...',
                class : 'disabled'
            });
            // push last 5 element
            for(i = numPages - 4; i <= numPages; i++) {
                className = (i == currPage) ? 'active' : '';
                pages.push({
                    onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + i + ")",
                    name : i,
                    class : className
                });
            }
        } else {
            // push the first page
            pages.push({
                onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + 1 + ")",
                name : 1
            });
            // push '...' element
            pages.push({
                name : '...',
                class : 'disabled'
            });

            // push middle pages
            for(i = currPage - 1; i <= currPage + 1; i++) {
                className = (i == currPage) ? 'active' : '';
                pages.push({
                    onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + i + ")",
                    name : i,
                    class : className
                });
            }

            // push '...' element
            pages.push({
                name : '...',
                class : 'disabled'
            });
            // push last page
            pages.push({
                onclick : "showCommentPage('" + base_id + "','" + type + "'," + item_id + "," + numPages + ")",
                name : numPages
            });

        }
    }

    // compile template
    var template = Handlebars.templates['_page_nav.html'];
    var data = {
        pages : pages,
        next : next,
        prev: prev,
    };
    return template(data);
}

/**
 * trigger reply form
 *
 * @param event
 * @param comment_id
 */
function reply_comment(event, comment_id) {
    event.preventDefault();

    $('#reply_comment_' + comment_id).toggle();
}

/**
 * show form for either question answer reply
 *
 * @param event
 * @param element_id
 */
function show_form(event, element_id) {
    event.preventDefault();
    $('#' + element_id).show();
}

/**
 * cancel reply form for either question answer reply
 *
 * @param event
 * @param element_id
 */
function cancel_from(event, element_id) {
    event.preventDefault();
    $('#' + element_id).hide();
}


/**
 * AJAX request to get more answers
 *
 * @param base_id
 * @param question_id
 * @param page
 * @param itemInPage
 * @param sorted
 * @param button_id
 */
function getMoreAnswers(base_id, question_id, page, itemInPage, sorted, button_id) {
    $.post('/question/answers', {
        question_id : question_id,
        page : page,
        itemInPage : itemInPage,
        sorted : sorted,
    }, function(results) {
        if (results.length == 0) {
            if (button_id) {
                $('#' + button_id).html("No More Already");
                $('#' + button_id).prop('disabled', true);
            }
        } else {
            // compile template
            var template = Handlebars.templates['_answer_item.html'];
            var data = {
                answers : results
            };
            // append more answers to the box
            $('#' + base_id).append(template(data));
            if (button_id) {
                $('#' + button_id).prop('disabled', false);
            }
        }
    });
}

var curreAnswerPage = 1;
function getMore(base_id, question_id, sorted, button_id) {
    if (button_id) {
        $('#' + button_id).prop('disabled', true);
    }
    getMoreAnswers(base_id, question_id, curreAnswerPage, null, sorted, button_id);
    curreAnswerPage++;
}


/**
 * Send ajax request to sever to save answer
 *
 * @param base_id
 * @param question_id
 * @returns {boolean}
 */
function saveAnswer(base_id, question_id) {
    $.post('/question/' + question_id + '/answer', {
        user_answer : $('#' + base_id + '_input').val()
    }, function(results) {
        if (results.status) {
            var template = Handlebars.templates['_answer_item.html'];
            var data = {
                answers : [results]
            }
            $('#' + base_id).append(template(data));
            $('#' + base_id + '_input').val("");
        } else {
            swal("Error", "Sever post a question :(", "error");
        }
    })

    return false;
}

/**
 * send ajax request to sever to store current question comment
 *
 * @param base_id
 * @param item_id
 * @param type
 * @param reply_id (optional)
 */
function saveComment(base_id, item_id, type, reply_id) {
    // get input text
    var inputField;
    if (reply_id) {
        // if reply_id is not null, then must be a action to reply a comment
        inputField = $('#' + base_id + '_reply_' + reply_id);
    } else {
        inputField = $('#' + base_id + '_input');
    }
    // validation
    if (inputField.val() == "") {
        // the user doesn't write any thing
        inputField.focus();
    } else {
        // validate pass, keep on
        $.post('/reply/' + item_id + '/reply', {
            text : inputField.val(),
            type : type,
            reply_to_reply_id : reply_id,
        }, function(results) {
            if (results.status) {
                // clear the submit content
                $('#' + base_id + '_input').val("");
                // hide the buttons
                $('#' + base_id + '_buttons').toggle(false);
                // refresh to show last comment page
                showCommentPage(base_id, type, item_id, results.numPages);
                // update comment count
                $('#' + base_id + '_replies_count').html(results.numReplies);
            } else {
                swal("Error", "Sever post a question :(", "error");
            }
        });
    }
}

/**
 * vote answer (up, down, cancel)
 *
 * @param answer_id
 * @param op
 */
function vote_answer(answer_id, op) {
    if (op == 'up') {
        // cancel the down vote button class
        $('#vote_answer_' + answer_id + '_down').toggleClass('active', false);
        var element = $('#vote_answer_' + answer_id + '_up');
        // toggle class
        element.toggleClass('active');

        // determine vote or cancel
        if(element.hasClass('active')) {
            vote_answer_helper(answer_id, 'up');
        } else {
            vote_answer_helper(answer_id, 'cancel');
        }

    } else if (op == 'down') {
        // cancel the up vote button class
        $('#vote_answer_' + answer_id + '_up').toggleClass('active', false);
        var element = $('#vote_answer_' + answer_id + '_down');
        // toggle class
        element.toggleClass('active');

        // determine vote or cancel
        if(element.hasClass('active')) {
            vote_answer_helper(answer_id, 'down');
        } else {
            vote_answer_helper(answer_id, 'cancel');
        }
    }
}

/**
 * vote answer helper (send ajax request only)
 *
 * @param answer_id
 * @param op
 */
function vote_answer_helper(answer_id, op) {
    $.post('/answer/' + answer_id + '/vote', {
        op : op
    }, function(result) {
        if (!result.status) {
            swal("Error", "Sever post a question :(", "error");
            return null;
        }
        // update the count
        $('.vote_answer_' + answer_id + '_count').each(function() {
            $(this).html(result.netVotes);
        });
    });
}

/**
 * Send ajax request to vote for the reply
 *
 * @param event
 * @param reply_id
 * @param op
 */
function vote_reply(event, reply_id) {
    // prevent anchor
    event.preventDefault();

    var element = $('#vote_reply_' + reply_id + '_up');
    element.toggleClass('active');

    // determine the operation
    if (element.hasClass('active')) {
        vote_reply_helper(reply_id, 'up');
    } else {
        vote_reply_helper(reply_id, 'cancel');
    }
}

/**
 * vote reply helper function to send ajax request
 *
 * @param reply_id
 * @param op
 */
function vote_reply_helper(reply_id, op) {
    $.post('/reply/' + reply_id + '/vote', {
        op : op
    }, function(result) {
        if (!result.status) {
            swal("Error", "Sever post a question :(", "error");
            return null;
        }
        // update the count
        $('#vote_reply_' + reply_id + '_count').html(result.numVotes);
    });
}

/**
 * Show conversation for comment
 *
 * @param event
 * @param initial_reply_id
 */
function showConversation(event, initial_reply_id) {
    // prevent anchor event
    event.preventDefault();

    $.post('/reply/conversation', {
        initial_reply_id : initial_reply_id
    }, function(results) {
        if (!results.status) {
            swal("Error", "Sever post a question :(", "error");
            return null;
        }
        // show conversation model
        // compile template
        var template = Handlebars.templates['_comment_conversation_item.html'];
        // send data
        var processResults = {
            replies : results.data
        }
        $('#comment_conversation_content').html(template(processResults));

        // bind hover event
        replyBindHoverShowOperation('comment_conversation_content');

        // show modal
        $('#comment_conversation').modal('show');
    })
}

/**
 * Question detail page subscribe button
 *
 * @param clickObject
 * @param question_id
 */
function show_question_subscribe(clickObject, question_id) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeQuestion(question_id, null, function(results) {
            $button.html('Unsubscribe');
            $button.removeClass('btn-success');
            $button.addClass('btn-warning');

            $('#question_subscriber').html(results.numSubscriber);
        });
    } else {
        // has subscribed
        subscribeQuestion(question_id, 'unsubscribe', function(results) {
            $button.html('Subscribe');
            $button.removeClass('btn-warning');
            $button.addClass('btn-success');

            $('#question_subscriber').html(results.numSubscriber);
        });
    }
}

//showCommentPage(base_id, type, item_id, page, callback)
//showComment(event, type, item_id, base_id)
function show_reply(reply_id, base_id, type, item_id, page) {
    if (type == 'question') {
        showComment(null, type, item_id, page);
        highlight('comment');
    }
}



