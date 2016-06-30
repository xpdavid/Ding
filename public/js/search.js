/**
 * Send ajax call to show results
 * @param base_id
 * @param type
 * @param query
 * @param page
 * @param callback
 */
var searchRange = Infinity;
var searchType = null;
function showResultPage(base_id, type, query, page, callback) {
    $.post('/search', {
        type : type,
        query : query,
        page : page,
        range : searchRange
    }, function(results) {
        switch (type) {
            case 'question' :
                // highlight query
                $.each(results.data, function(index, question) {
                    question.title = highlight_keyword(question.title, query);
                    question.content = highlight_keyword(question.content, query);
                });
                // compile template
                var template = Handlebars.templates['_search_question.html'];
                $('#' + base_id + '_content').html(template({
                    questions : results.data
                }));

                break;
            case 'answer' :
                // show results
                $('#' + base_id + '_content').html('');
                $.each(results.data, function(index, item) {
                    var template_question = Handlebars.templates['_topic_question_item.html'];
                    // compile question first
                    $('#' + base_id + '_content').append(template_question({
                        questions: [item.question]
                    }));
                    // highlight answers results
                    $.each(item.answers, function(index, answer) {
                        answer.answer = highlight_keyword(answer.answer, query);
                    });
                    // compile answers
                    var template_answer = Handlebars.templates['_answer_item.html'];
                    $('#topic_question_' + item.question.id + '_content').html(template_answer({
                        answers : item.answers
                    }));
                });

                break;
            case 'user' :
                // highlight user name
                $.each(results.data, function(index, user) {
                    user.name = highlight_keyword(user.name, query);
                });
                // compile template
                var template = Handlebars.templates['_search_user.html'];
                $('#' + base_id + '_content').html(template({
                    users : results.data
                }));
                break;
            case 'topic' :
                // highlight topic name
                $.each(results.data, function(index, topic) {
                    topic.name = highlight_keyword(topic.name, query);
                });
                // process data
                var processResults = [];
                var data = results.data;
                $.each(data, function(index, topic) {
                    if (index % 2 == 0) {
                        var left = data[index] ? data[index] : {};
                        var right = data[index + 1] ? data[index + 1] : {};
                        processResults.push({
                            left : left,
                            right : right
                        });
                    } else {
                        // do nothing
                    }
                });
                // compile template
                var template_topic = Handlebars.templates['_topics_item.html'];
                // send data
                $('#' + base_id + '_content').html(template_topic({
                    group2Topics : processResults
                }));
                break;
        }
        // compile navbar
        if (results.pages == 0) {
            $('#' + base_id + '_nav').html('');
        } else {
            $('#' + base_id + '_nav').html(
                compilePageNav(page, results.pages, base_id, type, query, 'showResultPage')
            );
        }
    });
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