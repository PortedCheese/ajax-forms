(function ($) {
    $(document).ready(function(){
        changeForms();
    });

    function changeForms() {
        let $forms = $('.sending-form-custom');
        $forms.each(function (index, element) {
            disableSubmit(element);
            $(element).on('submit', function (event) {
                event.preventDefault();
                clickElement(event, false);
            });
        });
        $forms = $('.sending-form');
        $forms.each(function (index, element) {
            $(element)
                .find("input[type='submit']")
                .click(function (event) {
                    event.preventDefault();
                    clickElement(event, true);
                });
        });
    }

    function disableSubmit(form){
        let $btn = $(form).find("button[type='submit']");
        if (! $btn) return;

        $btn.attr('disabled', 'disabled');

        $(form).on('change', function (event) {
            let res = validateInputs(this);
            if (res)
                $btn.removeAttr('disabled');
            else
                if(! $btn.attr('disabled'))
                    $btn.attr('disabled', 'disabled');
        });
    }

    function validateInputs(form){
        var validate = true;
        $(form).find('input[required]').each(function (){
            var value = $(this).val();
            if (! value || value === "")
                 validate = false;
        });
        return validate;
    }

    function clickElement(event, input) {
        let $form = false;
        let $submit = false;
        if (input) {
            $submit = $(event.target);
            $form = $submit.parents('form');
        }
        else {
            $form = $(event.target);
            $submit = $form.find("input[type='submit']");
            if (! $submit.length) {
                $submit = $form.find("button[type='submit']");
            }
            let submitAttr = getAttributes($submit);
            if (submitAttr.hasOwnProperty('data-target-submit')) {
                $submit = $(submitAttr['data-target-submit']);
            }
        }
        let formData = new FormData($form[0]);
        let formAttr = getAttributes($form);
        let formName = formAttr.hasOwnProperty('data-name') ? formAttr['data-name'] : formAttr['name']

        $submit.attr('disabled', 'disabled');
        if (input) {
            var buf = $submit.attr("value");
            $submit.attr("value", "Обработка");
        }
        else {
            $submit.append("<i class=\"loader fas fa-spinner fa-spin\"></i>");
        }
        $form.find('.invalid-feedback').each(function (inx, el) {
            $(el).parent().find('input').removeClass('is-invalid');
            $(el).remove();
        });
        $form.find('div.alert').each(function (inx, el) {
            $(el).remove();
        });

        axios
            .post("/ajax-forms/" + formName, formData)
            .then(response => {
                let data = response.data;
                if (data.messages.length) {
                    $(data.messages).prependTo($form);
                    if (data.messages.indexOf("success")) {
                        $form.trigger('reset');
                    }
                }
            })
            .catch(error => {
                let data = error.response.data;
                for (let item in data.errors) {
                    let $input = $form.find("input[name='" + item + "']");
                    if ($input.length && false) {
                        let $parent = $input.parent().append("<span class=\"invalid-feedback\" role=\"alert\"></span>");
                        let $errorBlock = $parent.find('.invalid-feedback');
                        $input.toggleClass('is-invalid');
                        for (index in data.errors[item]) {
                            if (data.errors[item].hasOwnProperty(index)) {
                                $errorBlock.append("<strong>" + data.errors[item][index] + "</strong>");
                            }
                        }
                    }
                    else {
                        let messages = "<div class=\"alert alert-danger\" role=\"alert\">" +
                            "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" +
                            "<span aria-hidden=\"true\">&times;</span>" +
                            "</button>";
                        for (index in data.errors[item]) {
                            if (data.errors[item].hasOwnProperty(index)) {
                                messages += data.errors[item][index] + "<br>";
                            }
                        }
                        messages += "</div>";
                        $(messages).prependTo($form);
                    }
                }
            })
            .finally(() => {
                $submit.removeAttr('disabled');
                if (input) {
                    $submit.attr("value", buf);
                }
                else {
                    $submit.find(".loader").remove();
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
