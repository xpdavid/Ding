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
            textCompare(base_id, results.data);

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
 * using jsdiff to compare two text in array
 *
 */
function textCompare(base_id, results) {
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

        var template = Handlebars.templates['_log_compare.html'];
        item.compare = $compare.html();
        item.operation = 'edit answer';
        $('#' + base_id + '_content').append(template(item));
    });
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