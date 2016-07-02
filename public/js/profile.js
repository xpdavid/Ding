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
                    delete_onclick : 'detachEducationExp(event, ' + data.id + ')'
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
                    delete_onclick : 'detachJob(event, ' + data.id + ')'
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
        page : page
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

