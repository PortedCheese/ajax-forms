<?php

namespace PortedCheese\AjaxForms\Console\Commands;

use App\Menu;
use App\MenuItem;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class AjaxFormsMakeCommand extends BaseConfigModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ajax-forms
                                {--all : Run all}
                                {--controllers : Export controllers}
                                {--models : Export models}
                                {--js : Include js}
                                {--config : Create config}
                                {--menu : Config menu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Settings for ajax forms';

    protected $packageName = "AjaxForms";

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = ['AjaxForm', 'AjaxFormField', 'AjaxFormSubmission', 'AjaxFormValue',];

    protected $controllers = [
        "Admin" => ["FieldController", "FormController",],
        "Site" => ["FormController",],
    ];

    protected $jsIncludes = [
        "app" => ["form"]
    ];

    protected $configName = "ajax-forms";

    protected $configTitle = "Формы";

    protected $configTemplate = "ajax-forms::admin.settings";

    protected $configValues = [
        "privacyPolicy" => true,
        "recaptchaEnabled" => true,
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $all = $this->option("all");

        if ($this->option("menu") || $all) {
            $this->makeMenu();
        }

        if ($this->option("controllers") || $all) {
            $this->exportControllers("Admin");
            $this->exportControllers("Site");
        }

        if ($this->option("models") || $all) {
            $this->exportModels();
        }

        if ($this->option('js') || $all) {
            $this->makeJsIncludes('app');
        }

        if ($this->option("config") || $all) {
            $this->makeConfig();
        }
    }

    protected function makeMenu()
    {
        try {
            $menu = Menu::query()
                ->where('key', 'admin')
                ->firstOrFail();
        }
        catch (\Exception $e) {
            return;
        }

        $title = "Формы";
        $itemData = [
            'title' => $title,
            'template' => "ajax-forms::admin.ajax-forms.menu",
            'url' => "#",
            'ico' => 'fab fa-wpforms',
            'menu_id' => $menu->id,
        ];

        try {
            $menuItem = MenuItem::query()
                ->where('title', $title)
                ->firstOrFail();
            $menuItem->update($itemData);
            $this->info("Элемент меню '$title' обновлен");
        }
        catch (\Exception $e) {
            MenuItem::create($itemData);
            $this->info("Элемент меню '$title' создан");
        }
    }
}
