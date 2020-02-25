# Ajax forms

##  Описание

Предоставляет возможность отправлять лубую форму с классом `sending-form` (это для webflow-integration) и `sending-form-custom` через ajax и сохраняет ее на сайте.

Предварительно на сайте нужно создать форму и поля к ней. Для каждой формы есть страница с сообщениями от этой формы.

## Установка

Нужно выгрузить js, который отправляет форму с классом `sending-form` и `sending-form-custom`

    php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force
    
    php artisan migrate

    php artisan make:ajax-forms
                            {--all : Run all}
                            {--controllers : Export controllers}
                            {--models : Export models}
                            {--policies : Export and create rules}
                            {--only-default : Create default rules}
                            {--js : Include js}
                            {--config : Create config}
                            {--menu : Config menu}
                             
### Versions:

    v1.2.4:
        - Добавлен аттрибут --only-default
    Обновление:
        - php artisan make:ajax-forms --controllers
    
    v1.2.3:
        - Добавлены права доступа
    Обновление:
        - php artisan make:ajax-forms --policies