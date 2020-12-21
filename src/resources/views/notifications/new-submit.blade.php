@component('mail::message')
# Здравствуйте!

На сайте было зарегистрировано обращение.

@component('mail::table')
| Поле | Значение |
| :--- | :--------: |
@foreach ($headers as $head)
| {{ $head->title }} | {!! isset($fields[$head->id]) ? $fields[$head->id]["render"] : '-' !!} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url])
Просмотр
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent