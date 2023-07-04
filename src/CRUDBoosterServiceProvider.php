<?php

namespace crocodicstudio\crudbooster;

use crocodicstudio\crudbooster\commands\CrudboosterInstallationCommand;
use crocodicstudio\crudbooster\commands\CrudboosterUpdateCommand;
use crocodicstudio\crudbooster\commands\CrudboosterVersionCommand;
use crocodicstudio\crudbooster\commands\Mailqueues;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class CRUDBoosterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * Call when after all packages has been loaded
     *
     * @return void
     */

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/views', 'crudbooster');
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/localization', 'crudbooster');
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/configs/crudbooster.php' => config_path('crudbooster.php')], 'cb_config');
            $this->publishes([__DIR__ . '/userfiles/controllers/CBHook.php' => app_path('Http/Controllers/CBHook.php')], 'CBHook');
            $this->publishes([__DIR__ . '/userfiles/controllers/AdminCmsUsersController.php' => app_path('Http/Controllers/AdminCmsUsersController.php')], 'cb_user_controller');
            $this->publishes([__DIR__ . '/controllers/AdminFormsController.php' => app_path('Http/Controllers/AdminFormsController.php')], 'cb_form_controller');

            $this->publishes([__DIR__ . '/Rules' => app_path('Rules')], 'cb_rules');
            $this->publishes([__DIR__ . '/assets' => public_path('vendor/crudbooster')], 'cb_asset');
            # File-Manager
            $this->publishes([__DIR__ . '/filemanager/includes/img' => public_path('vendor/filemanager/img')], 'filemanager_img');
            $this->publishes([__DIR__ . '/filemanager/includes/images' => public_path('vendor/filemanager/images')], 'filemanager_images');
            $this->publishes([__DIR__ . '/filemanager/includes/css' => public_path('vendor/filemanager/css')], 'filemanager_css');
            $this->publishes([__DIR__ . '/filemanager/includes/js' => public_path('vendor/filemanager/js')], 'filemanager_js');
            $this->publishes([__DIR__ . '/filemanager/includes/lang' => public_path('vendor/filemanager/lang')], 'filemanager_lang');
            $this->publishes([__DIR__ . '/filemanager/includes/svg' => public_path('vendor/filemanager/svg')], 'filemanager_svg');
            # Landing Page
            $this->publishes([__DIR__ . '/landing_page' => public_path('landing_page')], 'landing_page_files');
            # Landing Page Builder
            $this->publishes([__DIR__ . '/views/landing_page_builder/thankyou.blade.php' => resource_path('views/landing_page_builder/thankyou.blade.php')], 'landing_page_builder_thankyou_view');
            $this->publishes([__DIR__ . '/views/landing_page_builder/view.blade.php' => resource_path('views/landing_page_builder/view.blade.php')], 'landing_page_builder_view_view');
            $this->publishes([__DIR__ . '/landing_page_builder' => public_path('landing_page_builder')], 'landing_page_builder_files');
            # controllers
            $this->publishes([__DIR__ . '/userfiles/controllers/LandingPagesController.php' => app_path('Http/Controllers/LandingPagesController.php')], 'landing_page_builder_plugins');
            # lang
            $this->publishes([__DIR__ . '/localization' => resource_path('lang')], 'crudbooster_lang');
        }

        $this->customValidation();
    }

    /**
     * Register the application services.
     * Call when this package is first time loaded
     *
     * @return void
     */
    public function register()
    {
        require __DIR__ . '/helpers/Helper.php';

        $this->mergeConfigFrom(__DIR__ . '/configs/crudbooster.php', 'crudbooster');

        $this->registerSingleton();

        if ($this->app->runningInConsole()) {
            $this->commands('crudboosterinstall');
            $this->commands('crudboosterupdate');
            $this->commands('crudboosterVersionCommand');
            $this->commands('crudboosterMailQueue');
        }

        $loader = AliasLoader::getInstance();
        $loader->alias('PDF', 'Barryvdh\DomPDF\Facade');
        $loader->alias('Excel', 'Maatwebsite\Excel\Facades\Excel');
        $loader->alias('Image', 'Intervention\Image\Facades\Image');
        $loader->alias('CRUDBooster', 'crocodicstudio\crudbooster\helpers\CRUDBooster');
        $loader->alias('CB', 'crocodicstudio\crudbooster\helpers\CB');
    }

    private function registerSingleton()
    {
        $this->app->singleton('crudbooster', function () {
            return true;
        });

        $this->app->singleton('crudboosterinstall', function () {
            return new CrudboosterInstallationCommand;
        });

        $this->app->singleton('crudboosterupdate', function () {
            return new CrudboosterUpdateCommand;
        });

        $this->app->singleton("crudboosterVersionCommand", function () {
            return new CrudboosterVersionCommand;
        });

        $this->app->singleton("crudboosterMailQueue", function () {
            return new Mailqueues;
        });
    }

    private function customValidation()
    {
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^[\pL\s]+$/u', $value);
        }, 'The :attribute should be letters and spaces only');

        Validator::extend('alpha_num_spaces', function ($attribute, $value) {
            // This will only accept alphanumeric and spaces.
            return preg_match('/^[a-zA-Z0-9\s]+$/', $value);
        }, 'The :attribute should be alphanumeric characters and spaces only');
    }
}
