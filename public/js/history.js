/**
 * Show topic log history page
 */
function showTopicLogPage(base_id, type, answer_id, page, callback) {
    $.post('/topic/' + answer_id + '/log', {
        page : page,
    }, function(results) {
        // only the current answer itself
        if (results.data.names || results.data.descriptions || results.data.topics) {
            // clear content
            $('#' + base_id + '_content').html('');

            var all = [];

            // process topics
            $.each(results.data.topics, function(index, item) {
                var $a_tag = $('<a></a>');
                $a_tag.html(item.topic.name);
                $a_tag.attr('href', item.topic.url);
                if (item.type == 3) {
                    $a_tag.addClass('log_add');
                    item.operation = 'add parent topic';
                } else if (item.type == 4) {
                    $a_tag.addClass('log_remove');
                    item.operation = 'remove parent topic';
                } else if (item.type == 5) {
                    $a_tag.addClass('log_add');
                    item.operation = 'add subtopic';
                } else if (item.type == 6) {
                    $a_tag.addClass('log_remove');
                    item.operation = 'remove subtopic';
                }
                item.compare = $a_tag[0]['outerHTML'];
            });

            all = $.merge(all, results.data.topics);

            all = $.merge(all, textCompare(results.data.names, 'edit name'));

            all = $.merge(all, textCompare(results.data.descriptions, 'edit description'));

            all.sort(function(a, b) {
                return b.timestamp - a.timestamp;
            });

            var template = Handlebars.templates['_log_compare.html'];
            $('#' + base_id + '_content').html(template({
                logs : all
            }));

            // rerender math equation
            rerenderMath(base_id + '_content');

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, type, answer_id, 'showTopicLogPage'));

            // check callback
            if(callback && typeof callback == "function"){
                callback();
            }
        }
    });
}


/**
 * Show answer log history page
 */
function showAnswerLogPage(base_id, type, answer_id, page, callback) {
    $.post('/answer/' + answer_id + '/log', {
        page : page,
    }, function(results) {
        // only the current answer itself
        if (results.data.length > 1) {
            // clear content
            $('#' + base_id + '_content').html('');

            // append caparison
            var template = Handlebars.templates['_log_compare.html'];
            var data = {
                logs : textCompare(results.data, 'edit answer')
            };
            $('#' + base_id + '_content').html(template(data));

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, type, answer_id, 'showAnswerLogPage'));

            // check callback
            if(callback && typeof callback == "function"){
                callback();
            }
        }
    });
}

/**
 * Show question log history page
 */
function showQuestionLogPage(base_id, type, answer_id, page, callback) {
    $.post('/question/' + answer_id + '/log', {
        page : page,
    }, function(results) {
        // only the current answer itself
        if (results.data.titles || results.data.contents || results.data.topics) {
            // clear content
            $('#' + base_id + '_content').html('');

            var all = [];

            // process topics
            $.each(results.data.topics, function(index, item) {
                var $a_tag = $('<a></a>');
                $a_tag.html(item.topic.name);
                $a_tag.attr('href', item.topic.url);
                if (item.type == 3) {
                    $a_tag.addClass('log_add');
                    item.compare = $a_tag[0]['outerHTML'];
                    item.operation = 'add topic';
                } else if (item.type == 4) {
                    $a_tag.addClass('log_remove');
                    item.compare = $a_tag[0]['outerHTML'];
                    item.operation = 'remove topic';
                }
            });
            all = $.merge(all, results.data.topics);

            all = $.merge(all, textCompare(results.data.titles, 'edit title'));

            all = $.merge(all, textCompare(results.data.contents, 'edit quesiton detail'));

            all.sort(function(a, b) {
                return b.timestamp - a.timestamp;
            });

            var template = Handlebars.templates['_log_compare.html'];
            $('#' + base_id + '_content').html(template({
                logs : all
            }));

            // rerender math equation
            rerenderMath(base_id + '_content');

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, type, answer_id, 'showQuestionLogPage'));

            // check callback
            if(callback && typeof callback == "function"){
                callback();
            }
        }
    });
}

/**
 * using jsdiff to compare two text in array
 * return compile results for handle bars
 */
function textCompare(results, operation) {
    var process_results = [];
    $.each(results, function(index, item) {
        // ignore the first one, as the first one is the current answer
        if (index == 0) return ;

        var newOne = results[index - 1]['text'];
        var oldOne  = item['text'];

        var diff = JsDiff.diffWords(oldOne, newOne);
        var $compare = $('<div></div>');

        diff.forEach(function(part){
            // green for additions, red for deletions
            // grey for common parts
            var $span = $('<span></sapn>');
            if (part.added) {
                $span.addClass('log_add');
            } else if (part.removed) {
                $span.addClass('log_remove');
            }
            $span.append(part.value);

            $compare.append($span[0]);
        });

        item.compare = $compare.html();
        item.operation = operation;

        process_results.push(item);
    });

    return process_results;
}

/**
 * Rollback an history
 *
 * @param $id
 */
function historyRollback($id, event) {
    if (event) {
        event.preventDefault();
    }
    // confirm rollback
    swal({
        title: "Rollback operation?",
        text: "The corresponding content will be changed to the content in history",
        type: "warning",
        showCancelButton: true,
        closeOnConfirm: false
    }, function(){
        $.post('/history/' + $id + '/rollback', {}, function(results) {
            swal({
                title : "Rollback Success",
                text : "Be careful to edit it next time.",
                type : "success"
            }, function() {
                window.location.reload();
            });
        });
    });
}