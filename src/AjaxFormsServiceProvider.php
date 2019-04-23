<?php

namespace PortedCheese\AjaxForms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\AjaxForms\Models\AjaxForm;

class AjaxFormsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Подключение роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');

        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ajax-forms');

        // Переменная для меню форм.
        view()->composer('ajax-forms::admin.ajax-forms.menu', function ($view) {
            $view->with('ajaxForms', AjaxForm::all());
        });

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/assets/js' => public_path('js/'),
        ], 'public');
    }

    public function register()
    {

    }

}
