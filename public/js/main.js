/**
 * Set up ajax request header for laravel
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    statusCode : {
        405 : function() {
            swal('Warning', 'Please bind your account with IVLE', 'warning');
        },
        401 : function() {
            swal('Error', 'Login Required. More info after login', 'error');
        }
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
function topic_show_subscribe(clickObject, topic_id, text_id) {
    var $button = $(clickObject);
    if ($button.hasClass('btn-success')) {
        // has not subscribed yet
        subscribeTopic(topic_id, null, function(results) {
            if (results.status) {
                $('#' + text_id).html(results.numSubscriber);
                $button.html('Unsubscribe');
                $button.removeClass('btn-success');
                $button.addClass('btn-warning');
            }
        });
    } else {
        // has subscribed
        subscribeTopic(topic_id, 'unsubscribe', function(results) {
            if (results.status) {
                $('#' + text_id).html(results.numSubscriber);
                $button.html('Subscribe');
                $button.removeClass('btn-warning');
                $button.addClass('btn-success');
            }
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
                    $uploadClick.prop('disabled', false);
                    $uploadClick.html('Upload');
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
 *
 * @param textareaID
 * @param initcallback
 */
function tinyMCEeditor(textareaID, initcallback) {
    // cannot find the element.
    if ($('#' + textareaID).length == 0) return;
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
        browser_spellcheck: true,
        plugins: 'code advlist autolink link image table media codesample fullscreen paste',
        toolbar: ['code | undo redo | bold italic underline | blockquote codesample bullist numlist math | link image media | fullscreen',],
        relative_urls : false,
        remove_script_host : false,
        convert_urls : true,
        elementpath : false,
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
                    // set active
                    tinyMCE.setActive(editor);
                    // call tex editor with empty formula
                    callTexEditor('');
                }
            });

            editor.on('dblclick', function(ed) {
                if ($(ed.target).data('type') == "tex") {
                    callTexEditor(decodeURIComponent($(ed.target).data('value')));
                }
            });

            editor.on('init', function(ed) {
                tinyMCEAutoSave(textareaID);

                if (initcallback && typeof initcallback == "function") {
                    initcallback(editor);
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

/**
 * Auto save process for tinyMCE
 * @param editor
 */
function tinyMCEAutoSave(editor) {
    $(tinymce.get(editor).editorContainer)
        .find('.mce-statusbar')
        .find('.mce-container-body')
        .prepend('<div class="mce-flow-layout-item mce-path"><div class="mce-path-item" id="autosave_' + editor + '"></div></div>');
    var $message = $('#autosave_' + editor);
    var $editor = tinymce.get(editor);
    var offset = 30;
    var count = offset;
    var preContent = "";

    var autosaveInterval = setInterval(function() {
        console.log(count);
        if (!$('#' + editor).data('autosave')) {
            count = offset;
            return ;
        }

        // generate button
        var $a_tag = $('<a></a>');
        $a_tag.click(function(e) {
            e.preventDefault();
            autosave();
        });
        $a_tag.css('margin-right', '8px');
        // add cancel button

        $a_tag.html('Save Draft');
        $message.html($a_tag);

        if (count == 0) {
            var $div = $('<div>' + $editor.getContent() + '</div>');
            if ($div.text().replace(" ", "").length > 5 || $div.find('img').length > 0) {
                // autosave 5 character above
                autosave();
            }
            count = offset;
        } else if (count < 5) {
            $a_tag.html('Save Draft(' + count + ')');


            // count down generate cancel button
            var $a_cancel = $('<a></a>');
            $a_cancel.addClass('text-danger margin-top');
            $a_cancel.html('Cancel');
            $a_cancel.click(function(e) {
                e.preventDefault();
                count = offset;
            });
            $message.append($a_cancel);
        }

        count--;
    }, 1000);

    function autosave() {
        var $postData = $('[data-type="' + editor + '_draft"]');

        // determine if the data changed
        var flag = false;
        $postData.each(function(index, item) {
            var value = $(this).data('value') ? $(this).data('value') : $(this).val();
            if (!equal(preContent[index], value)) {
                flag = true || flag;
            }
        });

        // the editor content changed
        flag = flag || ($editor.getContent() != preContent[preContent.length - 1]);

        if (flag) {
            // only change then post draft
            var request = {};
            $postData.each(function() {
                if ($(this).data('value')) {
                    request[$(this).data('key')] = $(this).data('value');
                } else {
                    request[$(this).data('key')] = $(this).val();
                }
            });

            request['text'] = changeImageToTex($editor.getContent());

            $.post($('#' + editor).data('draft_url'), request, function(results) {
                $('#' + editor + '_draft_id').data('value', results.id);
                $('#' + editor + '_draft_id').val(results.id);
                $message.html('Saved');
            })
                .fail(function(error) {
                    $.each(error.responseJSON, function(index, value) {
                        showError('_' + index, true);
                    });
                });
        }

        count = offset;

        // backup previous content
        preContent = [];
        $postData.each(function(index, item) {
            var value = $(this).data('value') ? $(this).data('value') : $(this).val();
            preContent[index] = value;
        });
        preContent.push($editor.getContent());
    }
}



/**
 * Bind data-toggle hide/show event
 */
function bindHideShow() {
    $('body').on('click', '[data-toggle="hide"]', function(e) {
        e.preventDefault();

        if ($(this).data('hide')) {
            $('#' + $(this).data('hide')).hide();
        }

        if ($(this).data('show')) {
            $('#' + $(this).data('show')).show();
        }

    });
}

/**
 * Show error div. (lasted for 2 seconds)
 * @param base_id
 */
function showError(base_id, autoHide) {
    if (autoHide) {
        // last for 2 second
        $('#' + base_id + '_error').show();
        setTimeout(function() {
            $('#' + base_id + '_error').fadeOut();
        }, 2000);
    } else {
        // show
        $('#' + base_id + '_error').show();
    }

}

// bind expend button
$(function() {
    bindHideShow();
});

/**
 * Check if two things are equal
 *
 * @param a
 * @param b
 * @returns {boolean}
 */
function equal(a1, a2) {
    return a1 === a2 || (
            a1 !== null && a2 !== null &&
            $.isArray(a1) && $.isArray(a2) &&
            a1.length === a2.length &&
            a1
                .map(function (val, idx) { return val === a2[idx]; })
                .reduce(function (prev, cur) { return prev && cur; }, true)
        );
}

/**
 * Show point effect
 * @param $integer
 */
function pointUI(integer) {
    var $UI = $('#_point_operation');
    $UI.css('opacity', 0);
    $UI.html(integer > 0 ? '+' + integer : integer);
    $UI.css('top', '60px');
    $UI.animate({
        opacity : 1,
        top : '30px',
    }, 400, 'swing', function() {
        setTimeout(function() {
            $UI.animate({
                opacity : 0,
                top : 0,
            }, 200, 'swing', null);
        }, 400);
    });
}

/**
 * Determine if the element is in the view point
 *
 * @param el
 * @returns {boolean}
 */
$.fn.isOnScreen = function(){

    var win = $(window);

    var viewport = {
        top : win.scrollTop(), // for navbar
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));

};

/**
 * Check wheter show the button to close long answers/question
 *
 * @param $type
 */
function checkAndShowCloseButton($type) {
    var id;
    var regexp = new RegExp($type + "_full_content_(\\d{1,})", "g");
    var $div = $('div')
        .filter(function() {
            if (this.id.match(regexp)) {
                if ($(this).isOnScreen()) {
                    var id_temp = regexp.exec(this.id)[1];
                    if (!$('#' + $type + '_full_content_' + id_temp + '_viewport_bottom').isOnScreen()) {
                        id = id_temp;
                        return true;
                    }
                }
            }
            return false;
        });
    var $button = $('#close_detail_button_' + $type);
    if ($div.length != 0) {
        var offset = $div.offset();
        var viewportOffsetRight = offset.left - $(document).scrollLeft() + $div.width() - 65;
        $button.css('left', viewportOffsetRight);
        $button.data('hide', $type + '_full_' + id);
        $button.data('show', $type + '_summary_' + id);
        $button.show();
        if ($button.data('_id') != ($type + id)) {
            $button.css('opacity', 0);
            $button.css('bottom', '-30px');
            $button.animate({
                opacity : 1,
                bottom : '12px'
            }, 200, null);
        }
        $button.data('_id', $type + id);
    } else {
        $button.data('_id', null);
        $button.fadeOut();
    }
}
/**
 * Bind close detail button with scrolling event
 *
 * @param $type
 */
function bindFixedAnswerCloseButton($type) {
    var check = function() {
        checkAndShowCloseButton($type);
    };
    $(window).scroll(check);
    $('body').on('click', '[data-toggle="hide"]', check);
}

$(function() {
    bindFixedAnswerCloseButton('answer');
    bindFixedAnswerCloseButton('question');
});

/**
 * Fix for boostrap modal using tinymce
 */
$(function() {
    // Prevent Bootstrap dialog from blocking focusin
    $(document).on('focusin', function(e) {
        if ($(e.target).closest(".mce-window").length) {
            e.stopImmediatePropagation();
        }
    });
});


//# sourceMappingURL=main.js.map
