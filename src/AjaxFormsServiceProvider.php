<?php

namespace PortedCheese\AjaxForms;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\AjaxForms\Console\Commands\AjaxFormsMakeCommand;
use PortedCheese\AjaxForms\Console\Commands\AjaxFormsOverrideCommand;
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
            __DIR__ . '/resources/assets/js' => resource_path('js/vendor/'),
        ], 'public');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                AjaxFormsMakeCommand::class,
                AjaxFormsOverrideCommand::class,
            ]);
        }
    }

    public function register()
    {

    }

}
