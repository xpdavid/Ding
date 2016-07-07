/**
 * Set up ajax request header for laravel
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


/**
 * scroll to the element
 *
 * @param event
 * @param element_id
 */
function scroll_to(element_id, event) {
    if (event) {
        event.preventDefault();
    }

    $('html,body').animate({scrollTop: $('#' + element_id).offset().top - 200}, 1000);
}

/**
 * toggle display of the element with id
 *
 * @param id
 */
function toggle(id, event) {
    if (event) {
        event.preventDefault();
    }
    $('#' + id).toggle();
}

/**
 * Once click of the vote button than show the vote up and vote down button
 */
function vote_button_trigger(clickObject, show_id) {
    $(clickObject).hide();
    $('#' + show_id).show();
}

/**
 * Send AJAX request to subscribe a topic
 *
 * @param topic_id
 */
function subscribeTopic(topic_id, op, callback) {
    $.post('/subscribe/topic/' + topic_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Send ajax call to subscribe a question
 *
 * @param event
 * @param clickObject
 * @param topic_id
 */
function topics_subscribe(event, clickObject, topic_id) {
    event.preventDefault();
    var $click = $(clickObject);
    if ($click.hasClass('active')) {
        // is already subscribed
        subscribeTopic(topic_id, 'unsubscribe', function() {
            // remove class
            $click.removeClass('active');
            // show subscribe text
            $click.find('span:nth-child(1)').show();
            $click.find('span:nth-child(2)').text('');
            $click.find('span:nth-child(2)').text('Subscribe')
        });
    } else {
        // current operation is subscribe the topic
        subscribeTopic(topic_id, null, function() {
            // add active class
            $click.addClass('active');
            // show unsubscribe text
            $click.find('span:nth-child(1)').hide();
            $click.find('span:nth-child(2)').text('');
            $click.find('span:nth-child(2)').text('Unsubscribe')
        });
    }
}

/**
 * trigger subscribe button in specific topic page
 *
 * @param clickObject
 * @param topic_id
 */
function topic_show_subscribe(clickObject, topic_id) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeQuestion(topic_id, null, function() {
            $button.html('Unsubscribe');
            $button.removeClass('btn-success');
            $button.addClass('btn-warning');
        });
    } else {
        // has subscribed
        subscribeQuestion(topic_id, 'unsubscribe', function() {
            $button.html('Subscribe');
            $button.removeClass('btn-warning');
            $button.addClass('btn-success');
        });
    }
}

/**
 * Send ajax request to subscribe a question
 *
 * @param question_id
 * @param op
 * @param callback
 */
function subscribeQuestion(question_id, op, callback) {
    $.post('/subscribe/question/' + question_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Send ajax request to subscribe a user
 *
 * @param user_id
 * @param op
 * @param callback
 */
function subscribeUser(user_id, op, callback) {
    $.post('/subscribe/user/' + user_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Subscribe user by click button
 * @param clickObject
 * @param user_id
 */
function user_button_subscribe(clickObject, user_id, textID) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeUser(user_id, null, function(results) {
            $button.html('Unsubscribe');
            $button.removeClass('btn-success');
            $button.addClass('btn-default');
            if (textID) {
                $('#' + textID).html(results.numSubscriber);
            }
        });
    } else {
        // has subscribed
        subscribeUser(user_id, 'unsubscribe', function(results) {
            $button.html('Subscribe');
            $button.removeClass('btn-default');
            $button.addClass('btn-success');
            if (textID) {
                $('#' + textID).html(results.numSubscriber);
            }
        });
    }
}

/**
 * Send ajax request to subscribe a bookmark
 *
 * @param bookmark_id
 * @param op
 * @param callback
 */
function subscribeBookmark(bookmark_id, op, callback) {
    $.post('/subscribe/bookmark/' + bookmark_id, {
        op : op
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}


/**
 * AJAX send operation to server
 *
 * @param notification_id
 * @param op
 * @param callback
 */
function notificationOperation(notification_id, op, callback) {
    $.post('/notification/operation', {
        op : op,
        id : notification_id
    }, function(results) {
        if (callback && typeof callback == "function") {
            callback(results);
        }
    })
}

/**
 * Highlight a element, if rollback is true, after two second, it will auto 'dehighlight'.
 *
 * @param elementID
 * @param rollback(optional)
 */
function highlight(elementID, rollback) {
    $('#' + elementID).addClass('highlight');
    // hightlight 2 second
    if (rollback) {
        setTimeout(function(){
            $('#' + elementID).removeClass('highlight');
        }, 2000);
    }

}

/**
 * Highlight the keyword when the search is done
 *
 * @param text
 * @param keyword
 */
function highlight_keyword(text, keyword) {
    keyword = keyword.replace('$', '\\$');
    keyword = keyword.replace('(', '\\(');
    keyword = keyword.replace(')', '\\)');
    keyword = keyword.replace('*', '\\*');
    keyword = keyword.replace('+', '\\+');
    keyword = keyword.replace('.', '\\.');
    keyword = keyword.replace('[', '\\[');
    keyword = keyword.replace(']', '\\]');
    keyword = keyword.replace('?', '\\?');
    keyword = keyword.replace('\\', '\\');
    keyword = keyword.replace('^', '\\^');
    keyword = keyword.replace('{', '\\{');
    keyword = keyword.replace('}', '\\}');
    keyword = keyword.replace('|', '\\|');
    var reg = new RegExp(keyword, 'gi');
    return text.replace(reg, function(str) {return '<em>'+str+'</em>'});
}


/**
 * Auto complete for select user
 */
function user_name_autocomplete(id) {
    $('#' + id).select2({
        width: '100%',
        dropdownAutoWidth : true,
        placeholder: 'select peoples',
        minimumInputLength : 1,
        ajax: {
            url: "/api/autocomplete",
            dataType: 'json',
            method: 'POST',
            delay: 250,
            data: function (params) {
                return {
                    queries: [{
                        type : 'people',
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
 * auto complete for topics
 * @param id
 */
function topic_autocomplete(id) {
    $('#' + id).select2({
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
}

/**
 * Bind the upload button with event
 */
function cropImage(img_id, aspectRatio, callback) {
    // Import image
    var $inputImage = $('#' + img_id + '_upload');
    var $image = $('#' + img_id);
    var URL = window.URL || window.webkitURL;
    var blobURL;

    $image.cropper({
        aspectRatio: aspectRatio,
        crop: function(e) {
            // Output the result data for cropping image.

        },
        viewMode : 0,
        minContainerWidth : '500',
        minContainerHeight : '300'
    });

    if (URL) {
        $inputImage.change(function () {
            var files = this.files;
            var file;

            if (!$image.data('cropper')) {
                return;
            }

            if (files && files.length) {
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    blobURL = URL.createObjectURL(file);
                    $image.one('built.cropper', function () {

                        // Revoke when load complete
                        URL.revokeObjectURL(blobURL);
                    }).cropper('reset').cropper('replace', blobURL);
                    $inputImage.val('');
                } else {
                    window.alert('Please choose an image file.');
                }
            }
        });
    } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
    }

    // reset button
    $('#' + img_id + '_reset').click(function() {
        $image.cropper('reset');
    });

    // left rotate button
    $('#' + img_id + '_left_rotate').click(function() {
        $image.cropper('rotate', -45);
    });

    // right rotate button
    $('#' + img_id + '_right_rotate').click(function() {
        $image.cropper('rotate', 45);
    });


    // bring the upload modal to the front
    $('#' + img_id + '_modal').on('show.bs.modal', function (e) {
        console.log(1);
        $(this).css('z-index', 100002);
    });


    var $uploadClick = $('#' + img_id + '_upload_click');
    $uploadClick.click(function() {
        $uploadClick.prop('disabled', true);
        $uploadClick.html('Uploading..');


        var id = $uploadClick.data('id');
        var type = $uploadClick.data('type');
        var url = $uploadClick.data('url');

        $image.cropper('getCroppedCanvas').toBlob(function (blob) {
            var formData = new FormData();

            formData.append('croppedImage', blob);
            formData.append('id', id);
            formData.append('type', type);

            $.ajax(url, {
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (results) {
                    if (callback && typeof callback == "function") {
                        callback(results);
                    } else {
                        swal({
                            title : "Upload Success",
                            text : "The picture has uploaded!",
                            type : "success"
                        }, function() {
                            window.location.reload();
                        });
                    }
                },
                error: function () {
                    swal("Upload Error", "Sever post a question :(", "error");
                    $uploadClick.prop('disabled', false);
                    $uploadClick.html('Upload');
                }
            });
        });

    });
}

/**
 * Set all <img> tag under element `id` with `img-responsive`
 *
 * @param id
 */
function imgResponsiveIn(id) {
    $('#' + id).find('img').each(function() {
        if (!$(this).hasClass('img-responsive')) {
            $(this).addClass('img-responsive');
        }
    });

}

/**
 * TinyMCE editor
 * @param textareaID
 */
function tinyMCEeditor(textareaID) {
    // init
    var upload_callback_helper = null;
    var upload_callback = function(result) {
        upload_callback_helper(result.url);
        $('#crop_img_' + textareaID + '_modal').modal('hide');
    };
    cropImage('crop_img_' + textareaID , NaN, upload_callback);
    tinymce.init({
        menubar : false,
        selector: '#' + textareaID,
        paste_as_text: true,
        plugins: 'code advlist autolink link image imagetools table media codesample fullscreen paste',
        toolbar: ['code | undo redo | bold italic underline | blockquote codesample bullist numlist math | link image media | fullscreen',],
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        setup: function (editor) {
            editor.on('FullscreenStateChanged', function(e) {
                if (e.state) {
                    // hide nav bar when have fullscreen mode
                    $('.navbar').fadeOut();
                } else {
                    $('.navbar').show();
                }
            });

            // add Formular editor
            editor.addButton('math', {
                text: 'Equation',
                icon: false,
                onclick: function () {
                    callTexEditor('');
                }
            });

            editor.on('dblclick', function(ed) {
                if ($(ed.target).data('type') == "tex") {
                    callTexEditor(decodeURIComponent($(ed.target).data('value')));
                }
            });
        },
        // increase the font-size
        content_css : '/js/tinymce/content.css',
        // for upload image
        file_picker_callback: function(callback, value, meta) {
            // Provide image and alt text for the image dialog
            if (meta.filetype == 'image') {
                upload_callback_helper = callback;
                $('#crop_img_' + textareaID + '_modal').modal('show');
            }
        },
        file_picker_types: 'image'
    });
}

// bind expend buttong
$(function() {
    bindExpendAll();
});

//# sourceMappingURL=main.js.map
