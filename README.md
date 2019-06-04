# Ajax forms

##  Описание

Предоставляет возможность отправлять лубую форму с классом `sending-form` через ajax и сохраняет ее на сайте.

Предварительно на сайте нужно создать форму и поля к ней. Для каждой формы есть страница с сообщениями от этой формы.

## Установка

`composer require portedcheese/ajax-forms`

Нужно выгрузить js, который отправляет форму с классом `sending-form`

`php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force`

`php artisan make:ajax-forms {--menu : Only config menu}`

`php artisan override:ajax-forms
                     {--admin : Scaffold admin}
                     {--site : Scaffold site}`

Шаблон для меню `@includeIf('ajax-forms::admin.ajax-forms.menu')`