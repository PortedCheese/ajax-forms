# Ajax forms

##  Description

Предоставляет возможность отправлять лубую форму с классом `sending-form` (это для webflow-integration) и `sending-form-custom` через ajax и сохраняет ее на сайте.

Предварительно на сайте нужно создать форму и поля к ней. Для каждой формы есть страница с сообщениями от этой формы.

## Install

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
                            {--sass : Include scss}
                            {--menu : Config menu}

## Update v1.7

- Добавлен параметр VendorName для корректной установки пакета.
- При успешной отправке форма очизается от пользовательских данных.
- Абсолютное позициционирование уведомления об отправке формы. Параметр конфига alertAbsolute: [true, false = defsult]. Для абсолютного позиционирования уведомления добавьте к форме класс "position-relative" (bootstrap).

                
       php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force

       php artisan make:ajax-forms  --sass 
    

## Config

Выгрузка конфигурации:

    php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=config                    