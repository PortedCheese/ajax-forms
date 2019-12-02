# Ajax forms

##  Описание

Предоставляет возможность отправлять лубую форму с классом `sending-form` (это для webflow-integration) и `sending-form-custom` через ajax и сохраняет ее на сайте.

Предварительно на сайте нужно создать форму и поля к ней. Для каждой формы есть страница с сообщениями от этой формы.

## Установка

Нужно выгрузить js, который отправляет форму с классом `sending-form` и `sending-form-custom`

    php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force

    php artisan make:ajax-forms {--all : Run all}
                             {--controllers : Export controllers}
                             {--models : Export models}
                             {--js : Include js}
                             {--config : Create config}
                             {--menu : Config menu}