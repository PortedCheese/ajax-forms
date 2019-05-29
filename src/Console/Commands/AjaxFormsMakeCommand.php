<?php

namespace PortedCheese\AjaxForms\Console\Commands;

use App\Menu;
use App\MenuItem;
use Illuminate\Console\Command;
use PortedCheese\BaseSettings\Console\Commands\BaseConfigModelCommand;

class AjaxFormsMakeCommand extends BaseConfigModelCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:ajax-forms
                                {--menu : Only config menu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Settings for ajax forms';

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = [
        'AjaxForm.stub' => 'AjaxForm.php',
        'AjaxFormField.stub' => 'AjaxFormField.php',
        'AjaxFormSubmission.stub' => 'AjaxFormSubmission.php',
        'AjaxFormValue.stub' => 'AjaxFormValue.php',
    ];

    protected $configName = 'ajax-forms';

    protected $configValues = [
        'useOwnSiteRoutes' => false,
        'useOwnAdminRoutes' => false,
    ];

    protected $dir = __DIR__;

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
     *
     * @return mixed
     */
    public function handle()
    {
        if (! $this->option('menu')) {
            $this->exportModels();
        }
        $this->makeConfig();
        $this->makeMenu();
    }

    protected function makeMenu()
    {
        try {
            $menu = Menu::where('key', 'admin')->firstOrFail();
        }
        catch (\Exception $e) {
            return;
        }

        $title = "Формы";
        $itemData = [
            'title' => $title,
            'template' => "ajax-forms::admin.ajax-forms.menu",
            'url' => "#",
            'class' => '@fab fa-wpforms',
            'menu_id' => $menu->id,
        ];

        try {
            $menuItem = MenuItem::where('title', $title)->firstOrFail();
            $menuItem->update($itemData);
            $this->info("Элемент меню '$title' обновлен");
        }
        catch (\Exception $e) {
            $menuItem = MenuItem::create($itemData);
            $this->info("Элемент меню '$title' создан");
        }
    }
}
