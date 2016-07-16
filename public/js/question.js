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
        $link.data('clicked', true);
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
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, type, item_id, 'showCommentPage'));

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
 * @param funcName (base_id, type, item_id, page, callback)
 * @returns {*}
 */
function compilePageNav(currPage, numPages, base_id, type, item_id, funcName) {
    // generate prev link
    var prev = {};
    if (currPage > 1) {
        var prevPage = currPage - 1;
        prev.onclick = funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + prevPage + ")";
    } else {
        prev.class = "disabled";
    }

    // generate next link
    var next = {};
    if (currPage < numPages) {
        var nextPage = currPage + 1;
        next.onclick = funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + nextPage + ")";
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
                onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + i + ")",
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
                    onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + i + ")",
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
                onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + numPages + ")",
                name : numPages
            });
        } else if (numPages - currPage <=5) {
            // the current page is within last 5 page
            // push first page
            pages.push({
                onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + 1 + ")",
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
                    onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + i + ")",
                    name : i,
                    class : className
                });
            }
        } else {
            // push the first page
            pages.push({
                onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + 1 + ")",
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
                    onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + i + ")",
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
                onclick : funcName + "('" + base_id + "','" + type + "','" + item_id + "'," + numPages + ")",
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
    if (event) {
        event.preventDefault();
    }
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
 * @param callback
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

            // add responsive class to image
            imgResponsiveIn(base_id);

            // rerender math symbols
            rerenderMath(base_id);

            if (button_id) {
                $('#' + button_id).prop('disabled', false);
            }
        }

    });
}

var currentAnswerPage = 1;
function getMore(base_id, question_id, sorted, button_id, callback) {
    if (button_id) {
        $('#' + button_id).prop('disabled', true);
    }
    getMoreAnswers(base_id, question_id, currentAnswerPage, null, sorted, button_id);
    currentAnswerPage++;

    // check callback
    if(callback && typeof callback == "function"){
        callback();
    }
}

/**
 * Send ajax call to get specific answer
 *
 * @param ids
 * @param appendID
 * @param isAppend
 * @param callback
 */
function showAnswers(ids, appendID, isAppend, callback) {
    $.post('/question/answers', {
        ids : ids,
        page : 1,
        itemInPage : 10000
    }, function(results) {
        // compile template
        var template = Handlebars.templates['_answer_item.html'];
        var data = {
            answers : results
        };

        if (isAppend) {
            // append more answers to the box
            $('#' + appendID).append(template(data));
        } else {
            // override content
            $('#' + appendID).html(template(data));
        }
        // math
        rerenderMath(appendID);
        // responsive img
        imgResponsiveIn(appendID);

        // check callback
        if(callback && typeof callback == "function"){
            callback(results);
        }

    });
}

/**
 * Ajax method to get full draft for user
 */
function getAnswerDraft(draft_id, editor) {
    var $message = $('#autosave_' + editor);
    $.post('/answer/' + draft_id + '/fulldraft', {
        id : draft_id,
    }, function(results) {
        if (results.status) {
            tinymce.get(editor).setContent(changeTexToImage(results.draft));
            $message.html('Draft created ' + results.time);
        }
    });
}


/**
 * Send ajax request to sever to save answer
 *
 * @param base_id
 * @param question_id
 * @returns {boolean}
 */
function saveAnswer(base_id, question_id) {
    var $editor = tinyMCE.activeEditor;
    // form validation
    var $div = $('<div>' + $editor.getContent() + '</div>');
    if ($div.text().replace(" ", "").length <= 5 && $div.find('img').length == 0) {
        showError(base_id, true);
        return ;
    }

    // stop autosaving
    $('#' + base_id + '_input').data('autosave', false);

    // form post request
    var request = {};
    $('[data-type="' + base_id + '_input_draft"]').each(function() {
        request[$(this).data('key')] = $(this).data('value');
    });
    request['user_answer'] = changeImageToTex($editor.getContent({format : 'raw'}));

    // post to server
    $.post('/question/' + question_id + '/answer', request, function(results) {
        if (results.status) {
            var template = Handlebars.templates['_answer_item.html'];
            var data = {
                answers : [results]
            };
            $('#' + base_id).append(template(data));
            // clear content
            tinymce.activeEditor.setContent('');
            // render the formulat (if any)
            MathJax.Hub.Queue(["Typeset",MathJax.Hub,"answer_" + results.id]);
            imgResponsiveIn('question_answers');
            
            // disasbled the from
            // you can only answer an question once
            $('#question_answer_form').hide();
            $('#question_answer_forbidden_current').attr('href', '/answer/' + results.id);
            $('#question_answer_forbidden').show();

        } else {
            swal("Error", "Sever post a question :(", "error");
            // keep on saving
            $('#' + base_id + '_input').data('autosave', true);
        }
    });

    return false;
}

/**
 * The formula is show as image in the editor. We change it to tex language
 */
function changeImageToTex(content) {
    var $div = $('<div>' + content + '</div>');
    $div.find('img[data-type=tex]').each(function() {
        var $span = $('<span></span>');
        var $tex = decodeURIComponent($(this).data('value'));
        $span.html('\\(' + $tex + '\\)');
        $(this).replaceWith($span);
    });

    return $div.html();
}

/**
 * The formula is show as latex language in html, we change it to image
 */
function changeTexToImage(content) {
    var regex = /\\\((.*?)\\\)/g;
    if (content.match(regex)) {
        content = content.replace(regex, function() {
            return '<img src="https://latex.codecogs.com/gif.latex?' +
                encodeURIComponent(arguments[1])
                + '" data-type="tex" data-value="' + encodeURIComponent(arguments[1]) + '">';
        });
    }
    return content;
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

/**
 * highlight specific reply
 *
 * @param reply_id
 * @param base_id
 * @param type
 * @param item_id
 * @param page
 */
function highlight_reply(reply_id, base_id, type, item_id, page) {
    // all the parameters should not be empty
    if (reply_id == '') {
        return ;
    }
    var $link = $('#' + base_id + '_trigger');
    showCommentPage(base_id, type, item_id, page, function() {
        $('#' + base_id).toggle();
        $link.data('clicked', true);
        scroll_to('reply_' + reply_id);
        highlight('reply_' + reply_id, true);
    });
}

/**
 * Bookmark a item
 *
 * @param type
 * @param item_id
 * @param event
 */
var bookmark_to_type = null;
var bookmark_to_id = null;
function bookmark(type, item_id, event) {
    if (event) {
        event.preventDefault();
    }
    bookmark_to_type = type;
    bookmark_to_id = item_id;
    $('#bookmark_modal').modal('show');
    bookmark_helper();
}

/**
 * Send ajax call to show all bookmarks in the modal
 */
function bookmark_helper(callback) {
    $.post('/bookmark', {
        itemInPage : 1000,
        current_type : bookmark_to_type,
        current_id : bookmark_to_id
    }, function(results) {
        if (results.data.length) {
            // compile template
            var template = Handlebars.templates['_bookmark_modal_item.html'];
            var processResults = {
                bookmarks : results.data
            };
            // send data
            $('#bookmark_modal_list').html(template(processResults));

            // show modal
            $('#bookmark_modal').modal('show');
        } else {
            var $bookmarkModalListEmpty = $('#bookmark_modal_list_empty');
            $bookmarkModalListEmpty.click(function() {
                $('a[href="#bookmark_create"]').tab('show');
            });
            $bookmarkModalListEmpty.show();
        }
        if(callback && typeof callback == "function") {
            callback();
        }
    })
}

/**
 * Create new bookmark
 */
function bookmark_create() {
    var $bookmarkNewName = $('#bookmark_new_name');
    if ($bookmarkNewName.val() == "") {
        $bookmarkNewName.focus();
        return ;
    }
    $.post('/bookmark/create', {
        name : $bookmarkNewName.val(),
        description : $('#bookmark_new_description').val(),
        isPublic : $('input[name=bookmark_new_public]:checked').val()
    }, function(results) {
        bookmark_helper(function() {
            $('a[href="#bookmark_add"]').tab('show');
        });
        // clear content
        $bookmarkNewName.val('');
        $('#bookmark_new_description').val('');
        $('input[name=bookmark_new_public]:checked').val('');
    });
}

/**
 * Bookmark/debookmark the item according to the bookmark_to_type bookmark_to_id
 * @param bookmark_id
 * @param callback
 */
function bookmark_op(bookmark_id, callback) {
    /**
     * Process server response
     * @param results
     */
    function processResult(results) {
        $('#bookmark_modal_' + bookmark_id + '_numSubscriber').html(results.numSubscriber);
        $('#bookmark_modal_' + bookmark_id + '_numAnswer').html(results.numAnswer);
        $('#bookmark_modal_' + bookmark_id + '_numQuestion').html(results.numQuestion);
        $('#bookmark_modal_' + bookmark_id + '_isIn').toggle(results.isIn);
    }
    var request = {
        id : bookmark_id,
        item_type : bookmark_to_type,
        item_id : bookmark_to_id
    };
    if ($('#bookmark_modal_' + bookmark_id + '_isIn:visible').length == 0) {
        // add the item
        request.op = 'add';
    } else {
        // remove the item
        request.op = 'remove';
    }
    $.post('/bookmark/operation', request, function(results) {
        processResult(results);
        if (callback && typeof callback == "function") {
            callback(results);
        }
    });
}


/**
 * Trigger invite panel
 *
 * @param event
 * @param id
 */
function invite_panel(event, id) {
    if (event) {
        event.preventDefault();
    }

    if (!$('#invite_box').data('click')) {
        invite_panel_recommend_invitee(id);
        $('#invite_box').data('click', true);
    }

    $('#invite_box').toggle();
}

/**
 * Get recommended invitee
 *
 * @param id
 */
function invite_panel_recommend_invitee(id) {
    $.post('/question/' + id + '/invite_panel', {}, function(results) {
        var template = Handlebars.templates['_invite_item.html'];
        $('#question_invite_content').html(template({
            users : results
        }));
    })
}

/**
 * User search invitee
 *
 */
function search_invitee(term, id) {
    $.post('/question/' + id + '/invite_panel', {
        user : term
    }, function(results) {
        var template = Handlebars.templates['_invite_item.html'];
        $.each(results, function(index, item) {
            item.name = highlight_keyword(item.name, term);
        });

        $('#question_invite_content').html(template({
            users : results
        }));
    })
}

/**
 * Bind change keyup paste event to the search box
 */
var invite_search_box_timer = null;
function invite_search_box(question_id) {
    $('#invite_user_search').bind('change keyup paste', function() {
        clearInterval(invite_search_box_timer);
        invite_search_box_timer = setTimeout(function() {
            if ($('#invite_user_search').val() == "") {
                invite_panel_recommend_invitee(question_id);
            } else {
                search_invitee($('#invite_user_search').val(), question_id);
            }
        }, 500);
    });
}

/**
 * Send ajax request to invite a user
 *
 * @param button
 * @param user_id
 * @param question_id
 */
function invite_user(button, user_id, question_id) {
    $button = $(button);
    $.post('/question/' + question_id + '/invite', {
        user_id : user_id,
        question_id : question_id
    }, function(data) {
        if (data.status) {
            $button.html('Invited');
            $button.removeClass('btn-success');
            $button.addClass('btn-default');
            $button.prop('disabled', true);
        } else {
            swal('Invitation Fail', data.error, 'error');
        }
    })
}

/**
 * Bind expend all button
 */
function bindExpendAll() {
    $('body').on('click', '[data-toggle="expand_all"]', function(e) {
        if (e) {
            e.preventDefault();
        }
        $object = $(this);
        if ($object.data('type') == "answer") {
            $.post('/answer/' + $object.data('id'), {}, function(results) {
                $('#answer_summary_' + $object.data('id')).hide();
                $('#answer_full_content_' + $object.data('id')).html(results);
                rerenderMath('answer_full_' + $object.data('id'));
                $('#answer_full_' + $object.data('id')).show();
                imgResponsiveIn('answer_full_' + $object.data('id'));
                // set has expanded
                $('#answer_full_' + $object.data('id')).data('expand', true);
            });
        } else if ($object.data('type') == "question") {
            $.post('/question/' + $object.data('id'), {}, function(results) {
                $('#question_summary_' + $object.data('id')).hide();
                $('#question_full_' + $object.data('id')).html(results.content);
                rerenderMath('question_full_' + $object.data('id'));
                $('#question_full_' + $object.data('id')).show();
                imgResponsiveIn('question_full_' + $object.data('id'));
                // set has expanded
                $('#question_full_' + $object.data('id')).data('expand', true);
            });
        }
    })
}


/**
 * Trigger edit question
 */
function editQuestion(event, question_id) {
    if (event) {
        event.preventDefault();
    }

    // hide search table

    _question_modal_UISwitch('edit');

    // send ajax call to get question content
    $.post('/question/' + question_id, {}, function(results) {
        // copy question title
        $('#_question_title').val(results.title);
        // copy question content
        tinyMCE.get('_question_detail').focus();
        tinyMCE.activeEditor.setContent(changeTexToImage(results.content));
    });

    // set question id
    $('#_question_edit_id').val(question_id);

    // set topics
    var topics = $('[data-type="question_topics"]').data('content');
    var ids = [];

    var $topics = $('#_question_topics');
    $topics.empty(); // empty select

    $.each(topics, function(id, name) {
        $topics.append($("<option/>") //add option tag in select
                .val(id) //set value for option to post it
                .text(name)); //set a text for show in select
        ids.push(id);
    });
    $topics.val(ids).trigger("change"); //apply to select2

    // show modal
    $('#_question').modal('show');
}

/**
 * Edit answer
 *
 * @param event
 * @param answer_id
 */
function editAnswer(event, answer_id) {
    if (event) {
        event.preventDefault();
    }

    // add crop image modal for editor
    var crop_image_modal = Handlebars.templates['_crop_image.html'];
    $('body').append(
        crop_image_modal({
            id : 'answer_editor_' + answer_id,
            url : '/user/upload',
            image : '/static/images/default.png'
        })
    );

    // inital editor
    if (!tinyMCE.get('answer_editor_' + answer_id)) {
        tinyMCEeditor('answer_editor_' + answer_id);
    }

    // get answer content
    $.post('/answer/' + answer_id, {} , function (results) {
        tinyMCE.get('answer_editor_' + answer_id).setContent(
            changeTexToImage(results)
        );

        $('#answer_summary_' + answer_id).hide();
        $('#answer_full_' + answer_id).hide();
        $('#answer_editor_' + answer_id + '_wrapper').show();

        // scroll to the editor
        scroll_to('answer_editor_' + answer_id + '_wrapper');
    });
}

/**
 * Send ajax call to update answer
 *
 * @param answer_id
 */
function updateAnswer(answer_id) {
    $('#answer_editor_' + answer_id + '_wrapper').hide();
    $.post('/answer/' + answer_id + '/update', {
        answer : changeImageToTex(tinyMCE.get('answer_editor_' + answer_id).getContent())
    }, function(results) {
        var $summary = $('#answer_summary_content_' + answer_id);
        if (results.status) {
            $summary.html(results.answer);
            $summary.show();
        } else {

        }

        $('#answer_summary_' + answer_id).show();
        rerenderMath('answer_summary_' + answer_id);
    });
}

/**
 * Bind close button with event
 */
function bindCloseEvent() {
    $('body').on('click', '[data-action="close"]', function() {
        $object = $(this);

        var alert_param = {
            title: "Warning, Close Item",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Reason",
            confirmButtonColor: "#DD6B55",
        };
        switch ($object.data('type')) {
            case 'question' :
                alert_param.text = "User cannot subscribe, answer the question anymore";
                break;
            case 'answer':
                alert_param.text = "Other user will not see these answer first";
                break;
            case 'topic':
                alert_param.text = "User cannot subscribe the topic, post question under this topic";
                break;
        }
        swal(alert_param, function(inputValue) {
            if (inputValue === false) return false;

            $.post('/'
                + $object.data('type') + '/'
                + $object.data('id') + '/close',
                {
                    reason : inputValue
                }, function(results) {
                    if (results.status) {
                        swal({
                            title : 'Success',
                            text : 'We have closed the ' + $object.data('type'),
                            type : 'success'
                        }, function() {
                            window.location.reload();
                        });
                    } else {
                        swal('Error', 'Server post a question', 'error');
                    }
                });
        });
    });
}


/**
 * Bind close button with event
 */
function bindOpenEvent() {
    $('body').on('click', '[data-action="open"]', function() {
        $object = $(this);

        var alert_param = {
            title: "Warning, Open Item",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Reason",
            confirmButtonColor: "#DD6B55",
        };
        switch ($object.data('type')) {
            case 'question' :
                alert_param.text = "User can continue to subscribe, answer the question";
                break;
            case 'answer':
                alert_param.text = "Other user will see these answer by default";
                break;
            case 'topic':
                alert_param.text = "User can continue to subscribe the topic, post question under this topic";
                break;
        }
        swal(alert_param, function(inputValue) {
            if (inputValue === false) return false;

            $.post('/'
                + $object.data('type') + '/'
                + $object.data('id') + '/open',
                {
                    reason : inputValue
                }, function(results) {
                    if (results.status) {
                        swal({
                            title : 'Success',
                            text : 'We have opened the ' + $object.data('type'),
                            type : 'success'
                        }, function() {
                            window.location.reload();
                        });
                    } else {
                        swal('Error', 'Server post a question', 'error');
                    }
                });
        });
    });
}

/**
 * Bind event
 */
$(function() {
    bindExpendAll();

    // bind close event
    bindCloseEvent();

    // bind open event
    bindOpenEvent();
});



