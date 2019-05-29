<?php

namespace PortedCheese\AjaxForms\Console\Commands;

use Illuminate\Console\Command;
use PortedCheese\BaseSettings\Console\Commands\BaseOverrideCommand;

class AjaxFormsOverrideCommand extends BaseOverrideCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'override:ajax-forms
                    {--admin : Scaffold admin}
                    {--site : Scaffold site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change ajax-forms default logic';

    protected $controllers = [
        'Admin' => ["FieldController", "FormController"],
        'Site' => ["FormController"],
    ];

    protected $packageName = 'AjaxForms';

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
        if ($this->option('admin')) {
            $this->createControllers("Admin");
            $this->expandSiteRoutes('admin');
        }

        if ($this->option('site')) {
            $this->createControllers("Site");
            $this->expandSiteRoutes('web');
        }
    }
}
