jQuery(document).ready(function () {

    tinymce.init({
        selector: '#comment-description',
        setup: function (editor) {
            editor.on('keyup', function(e) {
                tinymceOnKeyup();
            });
        }
    });

    // attach a submit handler to the form
    $("form#comment-form").submit(function (event) {

        // stop form from submitting normally
        event.preventDefault();

        // get the action attribute from the <form action=""> element
        var $form = $(this),
            url = $form.attr('action'),
            submit_btn = $form.find('input[type="submit"]');

        submit_btn.prop('disabled', true);

        $.post(url,
            {
                commentDescription: tinymce.get('comment-description').getContent(),
                itemId: $form.find('input[name="item-id"]').val()
            },
            'json').done(function (resp) {

            if (resp.has_error) {
                $('.comment-error').html(resp.message);
                submit_btn.removeAttr('disabled');
            } else {
                $('.comment-list').append(resp.comment_view);
                tinymce.activeEditor.setContent('');
                submit_btn.removeAttr('disabled');
            }
        });
    });

    setInterval(function () {
        checkNewComments();
        getTypingUser();
    }, 3000);
});

function showNewComments(lastComments, firstCommentId) {
    $('.comment-list').append(lastComments);
    $('#comments-notification-box').html('');

    $('html, body').animate({
        scrollTop: $("#comment_" + firstCommentId).offset().top
    }, 3000);
}

function checkNewComments() {
    var lastCommentAttrId = $('.comment-list li:last').attr('id'),
        itemId = $('input[name="item-id"]').val();

    var lastCommentId = (typeof lastCommentAttrId !== "undefined" ) ? lastCommentAttrId.split("_")[1] : 1;

    $.post(
        '/check-new-comments',
        {lastCommentId: lastCommentId, itemId: itemId})
        .done(function (data) {
            if (typeof data.comment_view !== "undefined") {
                $('#comments-notification-box').html(data.notification_view);

                $('.get-new-comments').on('click', function () {
                    showNewComments(data.comment_view, data.firstCommentId);
                });
            }
        });
}

function getTypingUser() {
    $.ajax({
        method: "get",
        url: "/comments/get-typing-users/" + $('input[name="item-id"]').val(),
        dataType: "json",
        success: function (data) {
            if (data.typingUsers) {
                $('.typing-users').show();
                $('.typing-users .user').text(data.typingUsers);
                var is_or_are;
                if (data.typingUsers.indexOf(',') > -1) {
                    is_or_are = 'are';
                } else {
                    is_or_are = 'is';
                }
                $('.typing-users .type').text(' ' + is_or_are + ' typing');
            } else {
                $('.typing-users').hide();
                $('.typing-users .user').text('');
            }
        }
    });
}

function tinymceOnKeyup() {
    $.ajax({
        method: "POST",
        url: "/comments/set-typing-user",
        data: {comment_id: $('input[name="item-id"]').val()},
        dataType: "json"
    });
}