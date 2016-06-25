/*
Section for user profile edit
 */

/**
 * Bind the event 'toggle user setting form' to the edit button with id = $id
 *
 * @param $id
 */
function genericUserProfileEditToggleSetting(id) {
    // hide two div
    $(id + '_edit').toggle();

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

    $(id + '_edit').find('button').click(function(e) {

        $(id).toggle();
        $(id + '_edit').toggle();
    });
    
}

$(window).on('load', function() {
    // try bind every button with the event 'click and then toggle'
    try {
        genericUserProfileEditToggleSetting('#user_sex');
        genericUserProfileEditToggleSetting('#user_display_facebook');
        genericUserProfileEditToggleSetting('#user_display_email');
        genericUserProfileEditToggleSetting('#user_bio');
        genericUserProfileEditToggleSetting('#user_self_intro');
        genericUserProfileEditToggleSetting('#user_education');
        genericUserProfileEditToggleSetting('#user_job');
        genericUserProfileEditToggleSetting('#user_specialization');

    } catch (e) {
        console.log('edit button event binding fail');
    }
})



/**
 * Show edit button when cursor hover
 *
 * @param id
 */
function genericUserProfileEditHover(id) {
    $(id).find('a').toggle();

    $(id).parent().parent().parent().hover(function() {
        $(id).find('a').toggle();
    });
}

function genericUserProfileListEditHover(id) {
    $( "li[id^=" + id + "]" ).each(function(index, liUI) {
        $(liUI).children('a').toggle();

        $(liUI).hover(function() {
            $(liUI).children('a').toggle();
        });
    });
}

$(function() {
    // try bind every div with hover event
    try {
        genericUserProfileEditHover('#user_sex');
        genericUserProfileEditHover('#user_display_facebook');
        genericUserProfileEditHover('#user_display_email');
        genericUserProfileEditHover('#user_bio');
        genericUserProfileEditHover('#user_self_intro');

        // for listing (using li), bind every li with hover event
        genericUserProfileListEditHover('educationExp');
        genericUserProfileListEditHover('job');
        genericUserProfileListEditHover('specialization');

    } catch (e) {
        console.log('hover event binding fail');
    }
})


/**
 * Popup candidate text for the input field (type-ahead)
 *
 */
function genericTypeAhead(id, source_url) {
    $(id).typeahead({
        delay: 400,
        source: function (query, process) {
            $.ajax({
                url: source_url,
                data: {query: query},
                dataType: 'json'
            }).done(function(response) {
                return process(response);
            });
        }
    });
}

$(function() {
    // try bind every input field with type-ahead
    try {
        genericTypeAhead('[name="institution"]', '/api/institution-list');
        genericTypeAhead('[name="major"]', '/api/major-list');
        genericTypeAhead('[name="organization"]', '/api/organization-list');
        genericTypeAhead('[name="designation"]', '/api/designation-list');
        genericTypeAhead('[name="specialization"]', '/api/specialization-list');
    } catch (e) {
        console.log('hover event binding fail');
    }
})

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
    }).fail(function() {
        console.log('save user sex fail.');
    });

    return false;
}

function saveUserDisplayFacebook() {
    $.post({
        url: '/people/update',
        data: {
            facebook : $("#user_display_facebook_radio input[type='radio']:checked").val(),
            type: 'facebook',
        },
        dataType: 'json'
    }).done(function() {
        $("#user_display_facebook_status").text($("#user_display_facebook_radio input[type='radio']:checked").val());
    }).fail(function() {
        console.log('save user display facebook fail.');
    });

    return false;
}

function saveUserDisplayEmail() {
    $.post({
        url: '/people/update',
        data: {
            email : $("#user_display_email_radio input[type='radio']:checked").val(),
            type: 'email',
        },
        dataType: 'json'
    }).done(function() {
        $("#user_display_email_status").text($("#user_display_email_radio input[type='radio']:checked").val());
    }).fail(function() {
        console.log('save user display email fail.');
    });

    return false;
}

function saveUserBio() {
    $.post({
        url: '/people/update',
        data: {
            bio : $("#user_bio_edit input[type='text']").val(),
            type: 'bio',
        },
        dataType: 'json'
    }).done(function() {
        $("#user_bio_status").text($("#user_bio_edit input[type='text']").val());
    }).fail(function() {
        console.log('save user bio fail.');
    });

    return false;
}

function saveUserIntro() {
    $.post({
        url: '/people/update',
        data: {
            intro : $("#user_self_intro_edit").find('textarea').val(),
            type: 'intro',
        },
        dataType: 'json'
    }).done(function() {
        $("#user_self_intro_status").text($("#user_self_intro_edit").find('textarea').val());
    }).fail(function() {
        console.log('save user self-intro fail.');
    });

    return false;
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
        // generate education experience name
        var educationExpName = $('[name="institution"]').val();
        if ($('[name="major"]').val() != "") {
            educationExpName = educationExpName + ' ⋅ ' + $('[name="major"]').val();
        }

        // append generate item to the list
        $('#user_education_list').append(
            generateItemUI(
                'educationExp' + data.educationExp_id,
                educationExpName,
                'detachEducationExp(event, ' + data.educationExp_id  + ')'
            )
        );

        // clear value
        $('[name="institution"]').val("");
        $('[name="major"]').val("");

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
        $('#educationExp' + EducationExp_id).remove();
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
        // generate job name
        var jobName = $('[name="organization"]').val() + ' ⋅ ' + $('[name="designation"]').val();

        // append generate item to the list
        $('#user_job_list').append(
            generateItemUI(
                'job' + data.job_id,
                jobName,
                'detachJob(event, ' + data.job_id  + ')'
            )
        );

        // clear value
        $('[name="organization"]').val("");
        $('[name="designation"]').val("");

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
    event.preventDefault(); // prevent default html anchor

    $.post({
        url: '/people/delete',
        data: {
            job_id : job_id,
            type : 'job'
        },
        dataType: 'json'
    }).done(function() {
        $('#job' + job_id).remove();
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
    $.post({
        url: '/people/update',
        data: {
            specialization : $('[name="specialization"]').val(),
            type : 'specialization'
        },
        dataType: 'json'
    }).done(function(data) {
        // display edit button
        $('#user_specialization_edit').find('a').click();

        // generate specialization name
        var specializationName = $('[name="specialization"]').val();

        // append generate item to the list
        $('#user_specialization_list').append(
            generateItemUI(
                'specialization' + data.specialization_id,
                specializationName,
                'detachSpecialization(event, ' + data.specialization_id  + ')'
            )
        );

        // clear value
        $('[name="specialization_name"]').val("");

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
    event.preventDefault(); // prevent default html anchor

    $.post({
        url: '/people/delete',
        data: {
            specialization_id : specialization_id,
            type : 'specialization'
        },
        dataType: 'json'
    }).done(function() {
        $('#specialization' + specialization_id).remove();
    }).fail(function() {
        console.log('delete specialization fail.');
    });

    return false;
}


/**
 * generate ItemUI so that we can add item without refresh
 *
 * there need a function that auto find img by using some keywords
 *
 * @param title
 * @param deleteAction
 */
function generateItemUI(id, title, deleteAction) {
    titleUI = $('#itemContent_title');
    deleteButtonUI = $('#itemContent_delete');

    // set value
    titleUI.html(title);
    deleteButtonUI.attr('onclick', deleteAction);
    $('#itemContent').find('li').attr('id', id);

    // clear the all attribute
    titleUI.attr('id', '');
    deleteButtonUI.attr('id', '');

    // copy
    $content = $('#itemContent').html();

    // clear the all content
    titleUI.attr('id', 'itemContent_title');
    deleteButtonUI.attr('id', 'itemContent_delete');
    $('#itemContent').find('li').attr('id', "");
    titleUI.html("");

    return $content;
}


