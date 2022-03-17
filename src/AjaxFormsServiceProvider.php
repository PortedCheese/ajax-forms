<?php

namespace PortedCheese\AjaxForms;


use Illuminate\Support\ServiceProvider;
use PortedCheese\AjaxForms\Console\Commands\AjaxFormsMakeCommand;
use PortedCheese\AjaxForms\Events\CreateNewSubmission;
use PortedCheese\AjaxForms\Listeners\SendNewSubmissionNotification;

class AjaxFormsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Публикация конфигурации.
        $this->publishes([
            __DIR__ . "/config/ajax-forms.php" => config_path("ajax-forms.php"),
        ], "config");

        // Подключение роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ajax-forms');

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/assets/js' => resource_path('js/vendor/'),
            __DIR__ . "/resources/sass" => resource_path("sass/vendor/ajax-forms"),
        ], 'public');


        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                AjaxFormsMakeCommand::class,
            ]);
        }

        // Events.
        $this->app["events"]->listen(CreateNewSubmission::class, SendNewSubmissionNotification::class);
    }

    public function register()
    {
        // Стандартная конфигурация.
        $this->mergeConfigFrom(
            __DIR__ . "/config/ajax-forms.php", "ajax-forms"
        );

        $this->app->singleton("form-submissions-actions", function () {
            $class = config("ajax-forms.formSubmission");
            return new $class;
        });
    }

}
