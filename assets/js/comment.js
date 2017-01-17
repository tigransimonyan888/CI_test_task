jQuery(document).ready(function() {

    tinymce.init({
        selector: '#comment-description', setup: function (editor) {}
    });

    // attach a submit handler to the form
    $("form#comment-form").submit(function(event) {

        // stop form from submitting normally
        event.preventDefault();

        // get the action attribute from the <form action=""> element
        var $form = $( this ),
            url = $form.attr( 'action' ),
            submit_btn = $form.find('input[type="submit"]');

        submit_btn.prop('disabled', true);

        var posting = $.post( url,
            {
                commentDescription: tinymce.get('comment-description').getContent(),
                itemId: $form.find('input[name="item-id"]').val()
            },
            'json' ).done(function( data ) {

            var resp = JSON.parse(data);

            if (resp.has_error){
                $('.comment-error').html(resp.message);
            } else {
                $('.comment-list').append(resp.comment_view);
                tinymce.activeEditor.setContent('');
                submit_btn.removeAttr('disabled');
            }
        });
    });
});