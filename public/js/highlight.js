
var recommendQuestionPage = 1;
var recommendQuestionItemInPage = 5;
/**
 * AJAX get recommend question
 * @param before
 * @param callback
 * @param process
 */
function getRecommendQuestion(before, callback, process) {
    if (before && typeof callback == 'function') {
        before();
    }
    $.post('/highlight/recommend', {
        page : recommendQuestionPage,
        itemInPage : recommendQuestionItemInPage
    }, function(results) {
        if (process && typeof process == 'function') {
            process(results);
        }
        if (callback && typeof callback == 'function') {
            callback(results);
        }
    })
}

/**
 * ajax call to get editor recommendations in recommendations.blade.php
 */
function getEditorRecommendations() {
    recommendQuestionItemInPage = 10;
    getRecommendQuestion(function() {
        $('#editorRecommendations_button').prop('disabled', true);
    }, function(results) {
        if (!results.length) {
            $('#editorRecommendations_button').html('No More Already');
        } else {
            $('#editorRecommendations_button').prop('disabled', false);
            recommendQuestionPage++;
        }
    }, processRecommendNormal);
}

/**
 * process the results from ajax data with normal option
 * @param results
 */
function processRecommendNormal(results) {
    var template_normal = Handlebars.templates['_userCenter_home_item.html'];
    $('#highlight_recommend').append(template_normal({
        questions: results
    }));
}

var hotWeekQuestionPage = 1;
var hotWeekItemInPage = 10;
var hotMonthQuestionPage = 1;
var hotMonthItemInPage = 10;
/**
 * AJAX get hot question
 * @param before
 * @param callback
 * @param process
 * @param type
 */
function getHotQuestion(before, callback, process, type) {
    if (before && typeof callback == 'function') {
        before();
    }
    var param = {};
    if (type == 'week') {
        param = {
            page : hotWeekQuestionPage,
            itemInPage : hotWeekItemInPage,
            hot : 'week'
        }
    } else if (type == 'month') {
        param = {
            page : hotMonthQuestionPage,
            itemInPage : hotMonthItemInPage,
            hot : 'month'
        }
    }
    $.post('/highlight/hot', param, function(results) {
        if (process && typeof process == 'function') {
            process(results);
        }
        if (callback && typeof callback == 'function') {
            callback(results);
        }
    })
}

/**
 * Get more hot week question
 */
function getHotWeekQuestion() {
    getHotQuestion(function() {
        $('#highlight_week_more').prop('disabled', true);
    }, function(results) {
        if (!results.length) {
            $('#highlight_week_more').html('No More Already');
        } else {
            $('#highlight_week_more').prop('disabled', false);
            hotWeekQuestionPage++;
        }
    }, function(results) {
        var template = Handlebars.templates['_userCenter_home_item.html'];
        $('#highlight_week').append(template({
            questions: results
        }));

        rerenderMath('highlight_week');
    }, 'week');
}

/**
 * Get more hot month question
 */
function getHotMonthQuestion() {
    getHotQuestion(function() {
        $('#highlight_month_more').prop('disabled', true);
    }, function(results) {
        if (!results.length) {
            $('#highlight_month_more').html('No More Already');
        } else {
            $('#highlight_month_more').prop('disabled', false);
            hotMonthQuestionPage++;
        }
    }, function(results) {
        var template = Handlebars.templates['_userCenter_home_item.html'];
        $('#highlight_month').append(template({
            questions: results
        }));

        rerenderMath('highlight_month');
    }, 'month');
}

/**
 * process the results from ajax by given detail info to first two element
 * @param results
 */
function processRecommendShort(results) {
    var template_short = Handlebars.templates['_highlight_question_short.html'];
    var template_normal = Handlebars.templates['_userCenter_home_item.html'];

    $('#highlight_recommend').append(template_normal({
        questions: results.slice(0, 2)
    }));

    $('#highlight_recommend').append(template_short({
        questions: results.slice(2, 5)
    }));

    rerenderMath('highlight_recommend');

}

/**
 * process the results from ajax data with normal option
 * @param results
 */
function processRecommendNormal(results) {
    var template_normal = Handlebars.templates['_userCenter_home_item.html'];
    $('#highlight_recommend').append(template_normal({
        questions: results
    }));

    rerenderMath('highlight_recommend');
}