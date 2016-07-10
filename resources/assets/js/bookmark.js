/**
 * Show specific bookmark page of questions
 *
 * @param base_id
 * @param type (question) unused
 * @param bookmark_id
 * @param page
 * @param callback
 */
function showBookmarkQuestionPage(base_id, type, bookmark_id, page, callback) {
    $.post('/bookmark/' + bookmark_id, {
        page : page,
        itemInPage : 10,
        type : 'question'
    }, function(results) {
        if (!results.data.length) {
            return ;
        }
        var template = Handlebars.templates['_subscribed_question.html'];
        // show questions
        $('#' + base_id + '_content').html(template({
            questions : results.data
        }));
        // show navbar pagination
        $('#' + base_id + '_nav').html(
            compilePageNav(page, results.pages, base_id, type, bookmark_id, 'showBookmarkQuestionPage')
        );

        // check callback
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Show specific bookmark page of answers
 *
 * @param base_id
 * @param type (question) unused
 * @param bookmark_id
 * @param page
 * @param callback
 */
function showBookmarkAnswerPage(base_id, type, bookmark_id, page, callback) {
    $.post('/bookmark/' + bookmark_id, {
        page : page,
        itemInPage : 5,
        type : 'answer'
    }, function(results) {
        if (!results.data.length) {
            return ;
        }
        // clear content first
        $('#' + base_id + '_content').html('');
        $.each(results.data, function(index, item) {
            var template_question = Handlebars.templates['_topic_question_item.html'];
            // compile question first
            $('#' + base_id + '_content').append(template_question({
                questions: [item.question]
            }));
            // compile answers
            var template_answer = Handlebars.templates['_answer_item.html'];
            $('#topic_question_' + item.question.id + '_content').html(template_answer({
                answers : item.answers
            }));
        });

        // show navbar pagination
        $('#' + base_id + '_nav').html(
            compilePageNav(page, results.pages, base_id, type, bookmark_id, 'showBookmarkAnswerPage')
        );

        // check callback
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

var hotBookmarkPage = 1;
/**
 * get hot bookmark
 */
function getHotBookmark() {
    $.post('/bookmark/hot', {
        page : hotBookmarkPage,
        itemInPage : 5,
        maxItem : 20,
    }, function(results) {
        if (results.data.length) {
            var template = Handlebars.templates['_bookmark_item.html'];
            $('#hot_bookmark').html(template({
                bookmarks : results.data
            }));
        }
        hotBookmarkPage = Math.ceil(Math.random() * results.pages);
    })
}

/**
 * react of subscribe button when being clicked
 * @param clickObject
 * @param bookmark_id
 */
function bookmark_subscribe_click(clickObject, bookmark_id) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeBookmark(bookmark_id, null, function(results) {
            if (results.status) {
                $button.html('Unsubscribe');
                $button.removeClass('btn-success');
                $button.addClass('btn-warning');

                $('#bookmark_numSubscriber').html(results.numSubscriber);
            }
        });
    } else {
        // has subscribed
        subscribeBookmark(bookmark_id, 'unsubscribe', function(results) {
            if (results.status) {
                $button.html('Subscribe');
                $button.removeClass('btn-warning');
                $button.addClass('btn-success');

                $('#bookmark_numSubscriber').html(results.numSubscriber);
            }
        });
    }
}

/**
 * Show the bookmark_edit form
 */
function bookmark_edit(event) {
    if (event) {
        event.preventDefault();
    }
    $('#bookmark_edit').toggle();
    $('#bookmark_show').toggle();
}

/**
 * Send AJAX to save bookmark update
 * @param bookmark_id
 */
function bookmark_save_change(bookmark_id) {
    $.post('/bookmark/' + bookmark_id + '/update', {
        name : $('#bookmark_name').val(),
        description : $('#bookmark_description').val(),
        is_public : $('input[name=bookmark_is_public]:checked').val()
    }, function(results) {
        if (!results.status) {
            swal("Update Fail", "There are subscribers of your bookmark!", "error");
        } else {
            $('#bookmark_show_name').val(results.name);
            $('#bookmark_show_description').val(results.description);
            if (results.is_public == "true") {
                $('#bookmark_private_label').hide();
                $('#bookmark_public').prop('checked', true);
            } else {
                $('#bookmark_private_label').show();
                $('#bookmark_private').prop('checked', true);
            }
            bookmark_edit();
        }

    });
}

/**
 * Send ajax request to delete bookmark
 * @param bookmark_id
 * @param event
 */
function bookmark_delete(bookmark_id, event) {
    if (event) {
        event.preventDefault();
    }

    swal({
        title: "Are you sure?",
        text: "You will cannot recover the bookmark!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        closeOnConfirm: false
    }, function(){
        $.post('/bookmark/' + bookmark_id + '/delete', {

        }, function(results) {
            if (results.status) {
                swal({
                    title: "Deleted",
                    text: "Your bookmark has been deleted",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "OK!",
                    closeOnConfirm: false
                }, function(){
                    window.location = results.redirect;
                });
            } else {
                swal("Delete Fail", "There are subscribers of your bookmark!", "error");
            }
        });
    });

}