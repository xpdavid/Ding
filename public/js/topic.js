/**
 * AJAX to get child topics
 *
 * @param parent_topic_id
 */
function getFirstLevelChildTopic(parent_topic_id, page, object) {
    if (object) {
        // clear all active item
        $('#top_parent_topics').find('.active').toggleClass('active', false);
        // set current as active
        $(object).toggleClass('active', true);
    }
    // clear content
    $('#sub_topics').html('');

    moreParentTopicId = parent_topic_id;
    moreParentTopicPage = 1;

    getMoreTopics();
}

/**
 * function: getMoreTopics
 *
 * send ajax request to get more topic for different page number
 */
var moreParentTopicId = null;
var moreParentTopicPage = 1;
function getMoreTopics() {

    // trigger more button
    var $button = $('#topics_more_button');
    $button.prop('disabled', false);
    $button.html('More');

    // send ajax request to get child topic
    $.post('/topic/' + moreParentTopicId + '/sub_topics',
        {
            page : moreParentTopicPage
        },
        function(results) {
            var processResults = [];

            // no result
            if (results.length == 0) {
                var $button = $('#topics_more_button');
                $button.prop('disabled', true);
                $button.html('No More Already');
                return ;
            }

            $.each(results, function(index, topic) {
                if (index % 2 == 0) {
                    var left = results[index] ? results[index] : {};
                    var right = results[index + 1] ? results[index + 1] : {};
                    processResults.push({
                        left : left,
                        right : right
                    });
                } else {
                    // do nothing
                }
            });

            // compile template
            var template = Handlebars.templates['_topics_item.html'];
            // send data
            $('#sub_topics').append(template({
                group2Topics : processResults
            }));
        });

    // next time when being clicked, the current page number will be increased by one
    moreParentTopicPage++;
}

/**
 * AJAX request to get relevant question topic
 *
 * @param topic_id
 * @param type
 * @param page
 * @param itemInPage
 * @param sorted
 */
function getTopicQuestions(topic_id, type, page, itemInPage, sorted) {
    // keep the original value or update with new value
    getMoreTopicQuestion_page = page ? page : getMoreTopicQuestion_page;
    getMoreTopicQuestion_page_itemInPage = itemInPage ? itemInPage : getMoreTopicQuestion_page_itemInPage;
    getMoreTopicQuestion_page_type = type ? type : getMoreTopicQuestion_page_type;
    getMoreTopicQuestin_id = topic_id ? topic_id : getMoreTopicQuestin_id;
    getMoreTopicQuestin_sorted = sorted ? sorted :getMoreTopicQuestin_sorted;
    getMoreTopicQuestion();
}

/**
 * JS Method support for the `more` button
 */
var getMoreTopicQuestion_page = 1;
var getMoreTopicQuestion_page_itemInPage = 3;
var getMoreTopicQuestion_page_type = null;
var getMoreTopicQuestin_id = null;
var getMoreTopicQuestin_sorted = '';
function getMoreTopicQuestion() {
    var $button = $('#topics_more_button');
    $button.prop('disabled', true);
    $.post('/topic/questions', {
        topic_id : getMoreTopicQuestin_id,
        type : getMoreTopicQuestion_page_type,
        page : getMoreTopicQuestion_page,
        itemInPage : getMoreTopicQuestion_page_itemInPage,
        sorted : getMoreTopicQuestin_sorted
    }, function(results) {
        if (!results.length) {
            $button.html('No More Already');
            return;
        }
        $button.prop('disabled', false);

        var processResults = {
            questions : results
        };
        // compile template
        var template = Handlebars.templates['_topic_question_item.html'];
        // send data
        $('#topic_questions').append(template(processResults));

        // generate ajax request to get answers
        $.each(results, function(index, question) {
            // send ajax request to get answers for the question
            // api use please refer to the documentation
            getMoreAnswers('topic_question_' + question.id + '_content', question.id, 1, 3, null, null);
        });
    });

    // next page
    getMoreTopicQuestion_page++;
}

/**
 * generic method for topic autocomplete
 *
 * @param inputId
 * @param selectNum
 */
function topicAutocomplete(inputId, selectNum) {
    $("#" + inputId).select2({
        width: '300px',
        dropdownAutoWidth : true,
        placeholder: 'Please select some topics',
        minimumInputLength : 1,
        maximumSelectionLength : selectNum,
        ajax: {
            url: "/api/autocomplete",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    queries: [{
                        type : 'topic',
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

/**
 * show topic with specific sort method
 *
 * @param sorted
 */
function topic_show_sort(clickObject, sorted) {
    var $link = $(clickObject);
    // clear content
    $('#topic_questions').html('');
    // ajax get questions
    getTopicQuestions(null, null, 1, null, sorted);
    // trigger link display
    $link.addClass('font-black');
    $link.siblings('a').removeClass('font-black');
}

function topic_show_get_questions(clickObject, topic_id, sorted) {
    var $button = $(clickObject);
    // clear content
    $('#topic_questions').html('');
    // clear active button
    $('#subscribe_topics').find('.active').removeClass('active');
    // trigger current button
    $button.addClass('active');
    // ajax get questions
    getTopicQuestions(topic_id, null, 1, 10, sorted);

}