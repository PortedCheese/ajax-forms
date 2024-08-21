# Changelog

Описание версий и необходимые обновления

## [Update v2.0.0]
- base-settings 5.0 (bootstrap 5)
- переписан assets/js/form.js на js 
- обновлены admin views
- оновлен site.messages view


    php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force

## [Update v1.7.3]
- Возможность добавления нескольких email для уведомлений, обновлены: тип поля -  email multiple, валидация этого поля, а также модель AjaxFormSubmission

## [Update v1.7.2]
- Добавлена блокировка кнопки submit (для .sending-form-custom), пока не будут заполнены required-поля 


    php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force

## [Update v1.7]

- Добавлен параметр VendorName для корректной установки пакета.
- При успешной отправке форма очизается от пользовательских данных.
- Абсолютное позициционирование уведомления об отправке формы. Параметр конфига alertAbsolute: [true, false = defsult]. Для абсолютного позиционирования уведомления добавьте к форме класс "position-relative" (bootstrap).


       php artisan vendor:publish --provider="PortedCheese\AjaxForms\AjaxFormsServiceProvider" --tag=public --force

       php artisan make:ajax-forms  --scss 


## [v1.5.0]

### Изменения:

- Конфиг переписан в файл, из команды убрана генерация конфигурации
- Добавлено событие CreateNewSubmission
- В сообщение теперь выводятся заполненные поля
- Валидация перенесена в контроллер

### Обновление:

- Убрать конфигурацию из БД
- Если изменена валидация, перенести в контроллер

## [v1.2.4]

### Добавлено

- аттрибут --only-default

### Обновление
    
    php artisan make:ajax-forms --controllers
    
## [v1.2.3]

### Добавлено
- права доступа
    
### Обновление
    
    php artisan make:ajax-forms --policies