document.addEventListener('DOMContentLoaded', function() {
    changeForms();

    function changeForms(){
        let forms = document.querySelectorAll('.sending-form-custom');
        forms.forEach(function (element, index){
            disableSubmit(element);
            element.addEventListener('submit', function (event){
                event.preventDefault();
                clickElement(event, false);
            })

        });
        forms = document.querySelectorAll('.sending-form');
        forms.forEach(function (element, index){
            element.querySelectorAll("input[type='submit']").forEach(function (el, i){
                el.addEventListener("click",function (event){
                    event.preventDefault();
                    clickElement(event, true);
                })
            })
        });
    }

    function disableSubmit(form){
        let btn = form.querySelectorAll("button[type='submit']")[0];
        if (! btn) return;

        btn.setAttribute('disabled', 'disabled');

        form.addEventListener('change', function (event){
            let res = validateInputs(this);
            if (res)
                btn.removeAttribute('disabled');
            else
            if(! btn.getAttribute('disabled'))
                btn.setAttribute('disabled', 'disabled');
        })
    }

    function validateInputs(form){
        var validate = true;
        form.querySelectorAll('input[required]').forEach(function (el, i){
            var value = el.value;
            if (! value || value === "")
                validate = false;
        });
        return validate;
    }

    function clickElement(event, input) {
        let form = false;
        let submit = false;
        if (input) {
            submit = event.target;
            let parent = submit.parentElement;
            while (parent.nodeName != "form"){
                parent = parent.parentElement;
            }
            form = parent;
        }
        else {
            form = event.target;
            submit = form.querySelectorAll("input[type='submit']")[0];
            if (! submit) {
                submit = form.querySelectorAll("button[type='submit']")[0];
            }
            let submitAttr = getAttributes(submit);
            if (submitAttr.hasOwnProperty('data-target-submit')) {
                submit = submitAttr['data-target-submit'];
            }
        }
        let formData = new FormData(form);
        let formAttr = getAttributes(form);
        let formName = formAttr.hasOwnProperty('data-name') ? formAttr['data-name'] : formAttr['name']

        submit.setAttribute('disabled', 'disabled');
        if (input) {
            var buf = submit.getAttribute("value");
            submit.setAttribute("value", "Обработка");
        }
        else {
            submit.insertAdjacentHTML('beforeend',"<i class=\"loader fas fa-spinner fa-spin\"></i>");
        }
        form.querySelectorAll('.invalid-feedback').forEach(function (el, inx) {
            el.parentElement.querySelectorAll('input')[0].classList.remove('is-invalid');
            el.remove();
        });
        form.querySelectorAll('div.alert').forEach(function (el, inx) {
            el.remove();
        });

        axios
            .post("/ajax-forms/" + formName, formData)
            .then(response => {
                let data = response.data;
                if (data.messages.length) {
                    form.insertAdjacentHTML('afterbegin', data.messages);
                    if (data.messages.indexOf("success")) {
                        const event = new Event('reset');
                        form.dispatchEvent(event);
                    }
                }
            })
            .catch(error => {
                let data = error.response.data;
                for (let item in data.errors) {
                    let input = form.querySelectorAll("input[name='" + item + "']")[0];
                    if (input && false) {
                        let parent = input.parentElement.insertAdjacentHTML('beforeend',"<span class=\"invalid-feedback\" role=\"alert\"></span>");
                        let errorBlock = parent.querySelector('.invalid-feedback');
                        input.classList.toggle('is-invalid');
                        for (index in data.errors[item]) {
                            if (data.errors[item].hasOwnProperty(index)) {
                                errorBlock.insertAdjacentHTML('beforeend',"<strong>" + data.errors[item][index] + "</strong>");
                            }
                        }
                    }
                    else {
                        let messages = "<div class=\"alert alert-danger\" role=\"alert\">" +
                            "<button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\">" +
                            "<span aria-hidden=\"true\">&times;</span>" +
                            "</button>";
                        for (index in data.errors[item]) {
                            if (data.errors[item].hasOwnProperty(index)) {
                                messages += data.errors[item][index] + "<br>";
                            }
                        }
                        messages += "</div>";
                        form.insertAdjacentHTML('afterbegin', messages);
                    }
                }
            })
            .finally(() => {
                submit.removeAttribute('disabled');
                if (input) {
                    submit.setAttribute("value", buf);
                }
                else {
                    submit.querySelector(".loader").remove();
                }
            });
    }
    function getAttributes ( node ) {
        let attrs = {};
        if (node.hasAttributes()){
            var attributes = node.attributes;
            for (var i = attributes.length - 1; i >= 0; i--){
                attrs[attributes[i].name] = attributes[i].value;
            }
        }

        return attrs;
    }
});

