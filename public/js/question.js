/**
 * function: showComment
 *
 * @param event
 * @param clickObject
 * @param type
 * @param item_id
 * @param toggle_id
 * @param append_id
 *
 * description: send post request to get all the replies of the current object
 */
function showComment(event, clickObject, type, item_id, toggle_id, append_id) {
    event.preventDefault();

    $(clickObject).prop('onclick', null);
    $(clickObject).click(function(e) {
        event.preventDefault();
        // display the comment box
        $('#' + toggle_id).toggle();
    });

    // display comment at the first page
    showCommentPage(append_id, type, item_id, 1);
    $('#' + toggle_id).toggle();

}

/**
 * Click page nav trigger update comment
 *
 * @param append_id
 * @param type
 * @param item_id
 * @param page
 */
function showCommentPage(append_id, type, item_id, page) {
    $.post('/reply/reply-list', {
        type : type,
        item_id : item_id,
        page : page,
    }, function(results) {
        var processResults = {
            replies : results.data
        };

        // complie template
        var template = Handlebars.templates['_reply_item.html'];
        // send data
        $('#' + append_id).html(template(processResults));

        // update nav bar
        $('#nav_' + append_id).html(compileCommentPageNav(page, results.pages, append_id, type, item_id));
    });
}

/**
 * Compile page nav
 *
 * @param currPage
 * @param numPages
 * @param append_id
 * @param type
 * @param item_id
 * @returns {*}
 */
function compileCommentPageNav(currPage, numPages, append_id, type, item_id) {
    // generate prev link
    var prev = {};
    if (currPage > 1) {
        var prevPage = currPage - 1;
        prev.onclick = "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + prevPage + ")";
    } else {
        prev.class = "disabled";
    }

    // generate next link
    var next = {};
    if (currPage < numPages) {
        var nextPage = currPage + 1;
        next.onclick = "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + nextPage + ")";
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
                onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + i + ")",
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
                    onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + i + ")",
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
                onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + numPages + ")",
                name : numPages
            });
        } else if (numPages - currPage <=5) {
            // the current page is within last 5 page
            // push first page
            pages.push({
                onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + 1 + ")",
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
                    onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + i + ")",
                    name : i,
                    class : className
                });
            }
        } else {
            // push the first page
            pages.push({
                onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + 1 + ")",
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
                    onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + i + ")",
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
                onclick : "showCommentPage('" + append_id + "','" + type + "'," + item_id + "," + numPages + ")",
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

    $('#comment_' + comment_id).toggle();
}

/**
 * show form for either question answer reply
 *
 * @param event
 * @param element_id
 */
function show_form(event, element_id) {
    $('#' + element_id).show();
    scroll_to(event, element_id);
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
 * @param append_id
 * @param question_id
 * @param page
 */
function getMoreAnswers(append_id, question_id, page) {
    $.post('/question/answers', {
        question_id : question_id,
        page : page
    }, function(results) {
        if (results.length == 0) {
            $('.answer_more>button').html("No More Already");
            $('.answer_more>button').prop('disabled', true);
        } else {
            // compile template
            var template = Handlebars.templates['_answer_item.html'];
            var data = {
                answers : results
            };
            // append more answers to the box
            $('#' + append_id).append(template(data));
        }
    });
}

var curreAnswerPage = 2;
function getMore(event, append_id, question_id) {
    event.preventDefault();
    getMoreAnswers(append_id, question_id, curreAnswerPage);
    curreAnswerPage++;
}


