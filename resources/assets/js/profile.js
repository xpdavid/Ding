/*
Section for user profile edit
 */

/**
 * Bind the event 'toggle user setting form' to the edit button with id = $id
 *
 * @param $id
 */
function genericUserProfileEditToggleSetting(id) {

    $(id).find('a').click(function(e) {
        e.preventDefault();

        $(id).toggle();
        $(id + '_edit').toggle();
    });

    $(id + '_edit').find('a').click(function(e) {
        e.preventDefault();

        $(id).toggle();
        $(id + '_edit').toggle();
    });
    
}

/**
 * Show edit button when cursor hover
 *
 * @param id
 */
function genericUserProfileEditHover(id) {
    $(id + '_edit_button').hide();
    $(id + '_layout').hover(function() {
        $(id + '_edit_button').toggle();
    });
}

/**
 * Generic method to bind list delete button hover event
 *
 * @param id
 */
function genericUserProfileListEditHover(id) {
    $( "li[id^=" + id + "]" ).each(function(index, liUI) {
        if (!$(liUI).children('a').is(':visible')) {
            return ;
        }
        $(liUI).children('a').toggle();

        $(liUI).hover(function() {
            $(liUI).children('a').toggle();
        });
    });
}

/**
 * Popup candidate text for the input field (type-ahead)
 * @param id
 * @param array request
 */
function genericTypeAhead(id, type) {
    $(id).typeahead({
        delay: 400,
        minLength: 1,
        source: function (query, process) {
            $.post({
                url: '/api/autocomplete',
                data: {
                    queries : [{
                        type : type,
                        max_match : 10,
                        use_similar : 0,
                        term : query,
                    }]
                },
            }).done(function(response) {
                return process(response);
            });
        },
    });
}

/**
 * Bind input field event
 */
$(function() {
    // try bind every input field with type-ahead
    try {
        genericTypeAhead('[name="institution"]', 'institution');
        genericTypeAhead('[name="major"]', 'major');
        genericTypeAhead('[name="organization"]', 'organization');
        genericTypeAhead('[name="designation"]', 'designation');
        $('#specializations').select2({
            width: '100%',
            dropdownAutoWidth : true,
            placeholder: 'Please select some topics',
            minimumInputLength : 1,
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
    } catch (e) {
        console.log('hover event binding fail');
    }
});

/**
 * Send ajax request to save user sex
 *
 */
function saveUserSex() {
    $.post({
        url: '/people/update',
        data: {
            sex : $("#user_sex_radio input[type='radio']:checked").val(),
            type: 'sex',
        },
        dataType: 'json'
    }).done(function() {
        $("#user_sex_status").text($("#user_sex_radio input[type='radio']:checked").val());
        // hide the form
        $('#user_sex_edit').toggle();
        $('#user_sex').toggle();
    }).fail(function() {
        console.log('save user sex fail.');
    });
}

/**
 * Send ajax request to save user setting to displaying facebook
 *
 */
function saveUserDisplayFacebook() {
    $.post({
        url: '/people/update',
        data: {
            facebook : $("#user_display_facebook_radio input[type='radio']:checked").val(),
            type: 'facebook',
        },
        dataType: 'json'
    }).done(function(data) {
        $("#user_display_facebook_status").text(data.facebook ? 'Yes' : 'No');
        // hide the form
        $('#user_display_facebook_edit').toggle();
        $('#user_display_facebook').toggle();
    }).fail(function() {
        console.log('save user display facebook fail.');
    });
}

/**
 * Send ajax request to save user setting to displaying facebook
 *
 */
function saveUserDisplayEmail() {
    $.post({
        url: '/people/update',
        data: {
            email : $("#user_display_email_radio input[type='radio']:checked").val(),
            type: 'email',
        },
        dataType: 'json'
    }).done(function(data) {
        $("#user_display_email_status").text(data.email ? 'Yes' : 'No');
        // hide the form
        $('#user_display_email_edit').toggle();
        $('#user_display_email').toggle();
    }).fail(function() {
        console.log('save user display email fail.');
    });

}

/**
 * Send ajax request to save user bio
 *
 */
function saveUserBio() {
    if ( $("#user_bio_input").val() == "") {
        $("#user_bio_input").focus();
        return ;
    }
    $.post({
        url: '/people/update',
        data: {
            bio : $("#user_bio_input").val(),
            type: 'bio',
        },
        dataType: 'json'
    }).done(function(data) {
        $("#user_bio_status").text(data.bio);
        // hide the form
        $('#user_bio_edit').toggle();
        $('#user_bio').toggle();
    }).fail(function() {
        console.log('save user bio fail.');
    });
}

/**
 * Send ajax request to save user self intro
 */
function saveUserIntro() {
    $.post({
        url: '/people/update',
        data: {
            intro : $("#user_self_intro_input").val(),
            type: 'intro',
        },
        dataType: 'json'
    }).done(function(data) {
        $("#user_self_intro_status").text(data.self_intro);
        // hide the form
        $('#user_self_intro_edit').toggle();
        $('#user_self_intro').toggle();
    }).fail(function() {
        console.log('save user self-intro fail.');
    });
}


/**
 * AJAX request to the sever to change the user education experience
 * @param url_name
 */
function saveEducationExp() {
    $.post({
        url: '/people/update',
        data: {
            institution : $('[name="institution"]').val(),
            major : $('[name="major"]').val(),
            type : 'education'
        },
        dataType: 'json'
    }).done(function(data) {
        // empty data
        if (!data.status) return;
        // clear value
        $('[name="institution"]').val("");
        $('[name="major"]').val("");
        // check has the same experience
        if ($('#educationExp_' + data.id).length != 0) {
            // do nothing
            return ;
        }

        var template = Handlebars.templates['_profile_edit_item.html'];

        // append generate item to the list
        $('#user_education_list').append(
            template({
                items : [{
                    id : 'educationExp_' + data.id,
                    name : {
                        name : data.name,
                        url : ''
                    },
                    delete_onclick : 'detachEducationExp(event, ' + data.id + ')',
                    img_url : data.img
                }]
            })
        );

        // re-bind the new listing with hover event
        genericUserProfileListEditHover('educationExp');

    }).fail(function() {
        console.log('save education experience fail.');
    });

    return false;
}

/**
 * Javascript support for delete education experience
 *
 * @param event
 * @param EducationExp_id
 * @returns {boolean}
 */
function detachEducationExp(event, EducationExp_id) {
    event.preventDefault(); // prevent default html anchor

    $.post({
        url: '/people/delete',
        data: {
            educationExp_id : EducationExp_id,
            type : 'education'
        },
        dataType: 'json'
    }).done(function() {
        $('#educationExp_' + EducationExp_id).remove();
    }).fail(function() {
        console.log('delete education experience fail.');
    });

    return false;
}

/**
 * AJAX request to the sever to change the user job experience
 * @param url_name
 */
function saveJob() {
    $.post({
        url: '/people/update',
        data: {
            organization : $('[name="organization"]').val(),
            designation : $('[name="designation"]').val(),
            type : 'job'
        },
        dataType: 'json'
    }).done(function(data) {
        // empty data
        if (!data.status) return;

        // clear value
        $('[name="organization"]').val("");
        $('[name="designation"]').val("");

        // check has the same experience
        if ($('#job_' + data.id).length != 0) {
            // do nothing
            return ;
        }
        var template = Handlebars.templates['_profile_edit_item.html'];

        // append generate item to the list
        $('#user_job_list').append(
            template({
                items : [{
                    id : 'job_' + data.id,
                    name : {
                        name : data.name,
                        url : ''
                    },
                    delete_onclick : 'detachJob(event, ' + data.id + ')',
                    img_url : data.img
                }]
            })
        );

        // re-bind the new listing with hover event
        genericUserProfileListEditHover('specialization');

    }).fail(function() {
        console.log('save job fail.');
    });

    return false;
}

/**
 * Javascript support for delete job
 *
 * @param event
 * @param job_id
 * @returns {boolean}
 */
function detachJob(event, job_id) {
    if (event) {
        event.preventDefault(); // prevent default html anchor
    }

    $.post({
        url: '/people/delete',
        data: {
            job_id : job_id,
            type : 'job'
        },
        dataType: 'json'
    }).done(function() {
        $('#job_' + job_id).remove();
    }).fail(function() {
        console.log('delete job fail.');
    });

    return false;
}

/**
 * AJAX request to the sever to change the user specialization
 * @param url_name
 */
function saveSpecialization() {
    var $input = $('#specializations');
    if ($input.val() == "") {
        $input.focus();
        return ;
    }
    $.post({
        url: '/people/update',
        data: {
            specializations : $input.val(),
            type : 'specialization'
        },
        dataType: 'json'
    }).done(function(data) {
        // empty data
        if (!data.length) return;
        var template = Handlebars.templates['_profile_edit_item.html'];

        var process = [];
        $.each(data, function(index, item) {
            // check if has already in the list
            if ($('#specialization_' + item.id).length != 0) return ;
            // process results
            process.push({
                id : 'specialization_' + item.id,
                name : {
                    url : '/topic/' + item.id,
                    name : item.name
                },
                delete_onclick : 'detachSpecialization(event, ' + item.id + ')',
                img_url : item.img
            });
        });

        // append generate item to the list
        $('#user_specialization_list').append(
            template({
                items : process
            })
        );

        // clear value
        $('#specializations').val('').change()

        // hide the form
        $('#user_specialization_edit').toggle();
        $('#user_specialization').toggle();

        // re-bind the new listing with hover event
        genericUserProfileListEditHover('specialization');

    }).fail(function() {
        console.log('save specialization fail.');
    });

    return false;
}

/**
 * Javascript support for delete education experience
 *
 * @param event
 * @param EducationExp_id
 * @returns {boolean}
 */
function detachSpecialization(event, specialization_id) {
    if (event) {
        event.preventDefault(); // prevent default html anchor
    }

    $.post({
        url: '/people/delete',
        data: {
            specialization_id : specialization_id,
            type : 'specialization'
        },
        dataType: 'json'
    }).done(function() {
        $('#specialization_' + specialization_id).remove();
    }).fail(function() {
        console.log('delete specialization fail.');
    });

    return false;
}


/**
 * Show bookmark page
 *
 * @param base_id
 * @param type
 * @param item_id
 * @param page
 * @param callback
 */
function showBookmarkPage(base_id, type, item_id, page, callback) {
    $.post('/bookmark', {
        id : item_id,
        page : page,
        type : type,
    }, function(results) {
        if (results.data.length) {
            // compile template
            var template = Handlebars.templates['_bookmark_item.html'];
            var processResults = {
                bookmarks : results.data
            };
            
            // send data
            $('#' + base_id + '_content').html(template(processResults));

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, type, item_id, 'showBookmarkPage'));

            // check callback
            if(callback && typeof callback == "function"){
                callback();
            }
        } else {

        }
    });
}

/**
 * Show current user's subscribed users
 *
 * @param base_id
 * @param type
 * @param url_name
 * @param page
 * @param callback
 */
function showFollowFollowerPage(base_id, type, url_name, page, callback) {
    $.post('/people/' + url_name + '/follow-follower', {
        page : page,
        type : type,
    }, function(results) {
        if (results.data.length) {
            // compile template
            var template = Handlebars.templates['_search_user.html'];
            var processResults = {
                users : results.data
            };

            // send data
            $('#' + base_id + '_content').html(template(processResults));

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, type, url_name, 'showFollowFollowerPage'));

            // check callback
            if(callback && typeof callback == "function"){
                callback();
            }
        } else {

        }
    });
}

/**
 * send ajax call to get user's questions
 *
 * @param base_id
 * @param itemInPage
 * @param url_name
 * @param page
 * @param append
 */
function showUserQuestionPage(base_id, itemInPage, url_name, page, append, callback) {
    // check use append
    if(!append){
        $('#' + base_id + '_content').html('');
        $('#' + base_id + '_nav').html('');
    }
    $.post('/people/' + url_name + '/question', {
        page : page,
        itemInPage : itemInPage,
    }, function(results) {
        if (results.data.length) {
            // compile template
            var template = Handlebars.templates['_subscribed_question.html'];
            var processResults = {
                questions : results.data
            };

            // send data
            $('#' + base_id + '_content').append(template(processResults));

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, itemInPage, url_name, 'showUserQuestionPage'));
        } else {

        }

        // check call back
        if (callback && typeof callback == "function") {
            callback(results);
        }
    });
}


/**
 * send ajax call to get user's answers
 *
 * @param base_id
 * @param itemInPage
 * @param url_name
 * @param page
 * @param append
 */
function showUserAnswerPage(base_id, itemInPage, url_name, page, append, callback) {
    // check use append
    if(!append){
        $('#' + base_id + '_content').html('');
        $('#' + base_id + '_nav').html('');
    }
    $.post('/people/' + url_name + '/answer', {
        page : page,
        itemInPage : itemInPage,
        topic : $('#' + base_id).data('topic')
    }, function(results) {
        if (results.data.length) {

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

                // remove profile since it is the same
                $.each(item.answers, function(index, answer) {
                    $('#answer_' + answer.id + '_profile').remove();
                });
            });

            // rerender math symbols
            rerenderMath(base_id);

            // update nav bar
            $('#' + base_id + '_nav').html(compilePageNav(page, results.pages, base_id, itemInPage, url_name, 'showUserAnswerPage'));
        } else {

        }

        // check call back
        if (callback && typeof callback == "function") {
            callback(results);
        }
    });
}

var profileGetMoreQuestion_page = 1;
function profileGetMoreQuestion(url_name) {
    showUserQuestionPage('question', 4, url_name, profileGetMoreQuestion_page, true, function(results) {
        if(results.data.length == 0) {
            $('#question_button').prop('disabled', true);
            $('#question_button').html('No More Already');
        }
    });
    profileGetMoreQuestion_page++;
}

var profileGetMoreAnswer_page = 1;
function profileGetMoreAnswer(url_name) {
    showUserAnswerPage('answer', 3, url_name, profileGetMoreAnswer_page, true, function(results) {
        if(results.data.length == 0) {
            $('#answer_button').prop('disabled', true);
            $('#answer_button').html('No More Already');
        }
    });
    profileGetMoreAnswer_page++;
}


var profileUplodatesDate = null;
function getMoreUpdates(url_name) {
    $.post('/people/' + url_name + '/updates', {
        startDate : profileUplodatesDate
    }, function(results) {
       if (results.status) {
           profileUplodatesDate = results.end;
           var $area = $('#updates_content');
           $.each(results.data, function(index, item) {
               var $outer = $('<div></div>');
               var $head = $('<div></div>');
               var $time = $('<div></div>');
               $time.addClass('float-right');
               $time.html(item.time);
               $head.addClass('font-greyLight');
               $head.append($time[0]);
               // determine the updates type. (refer to people controller)
               switch (item.type) {
                   case 1:
                       $head.prepend('Post a question');
                       var template = Handlebars.templates['_highlight_question_short.html'];
                       $outer.html(template({
                           questions : [item]
                       }));
                       $outer.prepend($head[0]);
                       break;
                   case 2:
                       $head.prepend('Subscribe to a question');
                       var template = Handlebars.templates['_highlight_question_short.html'];
                       $outer.html(template({
                           questions : [item]
                       }));
                       $outer.prepend($head[0]);
                       break;
                   case 3:
                       $head.prepend('Post an answer');
                       var template = Handlebars.templates['_userCenter_home_item.html'];
                       $outer.html(template({
                           questions : [item]
                       }));
                       $outer.prepend($head[0]);
                       break;
                   case 4:
                       $head.prepend('Vote up an answer');
                       var template = Handlebars.templates['_userCenter_home_item.html'];
                       $outer.html(template({
                           questions : [item]
                       }));
                       $outer.prepend($head[0]);
                       break;
                   case 5:
                       $head.prepend('Subscribe to a topic');
                       var $img = $('<img>');
                       var $div = $('<div></div>');
                       var $a = $('<a></a>');
                       $div.addClass('margin-top');
                       $img.addClass('img-rounded');
                       $img.addClass('space-right');
                       $img.prop('src', item.topic_pic);
                       $a.html(item.name);
                       $a.prop('href', '/topic/' + item.id);
                       $div.append($img[0]);
                       $div.append($a[0]);

                       var $hr = $('<hr>');
                       $hr.addClass('small_hrLight');

                       $outer.prepend($head[0]);
                       $outer.append($div[0]);
                       $outer.append($hr[0]);
                       break;
               }
               $area.append($outer[0]);
           });

           // rerender math symbols
           rerenderMath('updates_content');
       } else {
           $('#updates_button').remove();
       }
    });
}

/**
 * Send ajax request to cancel blocking
 *
 * @param block_id
 */
function cancelBlocking(event, block_id) {
    if (event) {
        event.preventDefault();
    }
    swal({
        title: "Are you sure?",
        text: "You will continue receive any update from this user!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, cancel it!",
        closeOnConfirm: false
    }, function(){
        $.post('/settings/update', {
            'cancel_block' : block_id
        }, function(data) {
            window.location.replace('/settings/blocking');
        });
    });
}


/**
 * Send ajax request to cancel hide topic
 *
 * @param topic_id
 */
function cancelHideTopic(event, topic_id) {
    if (event) {
        event.preventDefault();
    }
    swal({
        title: "Are you sure?",
        text: "You will continue receive any update from this topic!",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, cancel it!",
        closeOnConfirm: false
    }, function(){
        $.post('/settings/update', {
            'cancel_hide_topic' : topic_id
        }, function(data) {
            window.location.replace('/settings/blocking');
        });
    });
}


/**
 * when hover, show the arrow
 *
 * @param prefix
 * @param arrow_class
 */
function hoverShowArrow(prefix, arrow_class) {
    $('div[id^=' + prefix + ']').hover(function() {
        console.log(this);
        $(this).find('.' + arrow_class).show();
    }, function() {
        $(this).find('.' + arrow_class).hide();
    });
}

/**
 * Bind ban user button event
 */
function bindBanUserEvent() {
    $('body').on('click', '[data-action="user_operation"]', function(e) {
        if (e) {
            e.preventDefault()
        }
        var self = this;
        var button = $(self).data('type') == 'cancel' ? 'Cancel' : 'Yes, ban it!';
        swal({
            title: "Ban/Cancel User?",
            text: "If ban user, user cannot login",
            type: "warning",
            showCancelButton: true,
            confirmButtonText: button,
            closeOnConfirm: false
        }, function() {
            $.post('/people/' + $(self).data('url_name') + '/ban', {
                operation : $(self).data('type')
            }, function(results) {
                if (results.status) {
                    swal({
                        title: "Success",
                        text: "Operation success",
                        type: "success",
                    }, function() {
                        window.location.reload();
                    });
                }
            })
        });
    })
}
