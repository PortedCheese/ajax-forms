(function ($) {
    $(document).ready(function(){
        changeForms();
    });

    function changeForms() {
        let $forms = $('.sending-form');
        $forms.each(function (index, element) {
            $(element)
                .find("input[type='submit']")
                .click(function (event) {
                    event.preventDefault();
                    clickElement(event);
                });
        });
    }

    function clickElement(event) {
        let token = $('head').find("meta[name='csrf-token']").attr('content');
        let $submit = $(event.target);
        let $form = $submit.parent('form');
        let formAttr = getAttributes($form);
        let data = {};
        data['input'] = $form.serialize();
        data['elements'] = [];
        $form.children().each(function (index, element) {
            data['elements'].push(getAttributes($(element)));
        });
        console.log(data);
        $submit.attr('disabled', 'disabled');
        $form.find('.alert').remove();
        $.ajax({
            url: '/ajax-forms/' + formAttr.name,
            type: 'post',
            data: data,
            headers: {
                'X-CSRF-TOKEN': token
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);
                $submit.removeAttr('disabled');
                if (data.messages.length) {
                    $(data.messages).prependTo($form);
                }
                if (data.success) {
                    $form.trigger('reset');
                }
            }
        });
    }

    function getAttributes ( $node ) {
        let attrs = {};
        $.each( $node[0].attributes, function ( index, attribute ) {
            attrs[attribute.name] = attribute.value;
        } );

        return attrs;
    }
})(jQuery);