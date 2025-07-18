<?php

namespace crocodicstudio\crudbooster;

use crocodicstudio\crudbooster\commands\CrudboosterInstallationCommand;
use crocodicstudio\crudbooster\commands\CrudboosterUpdateCommand;
use crocodicstudio\crudbooster\commands\CrudboosterVersionCommand;
use crocodicstudio\crudbooster\commands\Mailqueues;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        if (Schema::hasTable('cms_menus')) {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        }


        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/configs/crudbooster.php' => config_path('crudbooster.php')], 'cb_config');
            $this->publishes([__DIR__ . '/userfiles/controllers/CBHook.php' => app_path('Http/Controllers/CBHook.php')], 'CBHook');
            $this->publishes([__DIR__ . '/userfiles/controllers/AdminCmsUsersController.php' => app_path('Http/Controllers/AdminCmsUsersController.php')], 'cb_user_controller');
            $this->publishes([__DIR__ . '/userfiles/controllers/AdminFormsController.php' => app_path('Http/Controllers/AdminFormsController.php')], 'cb_form_controller');
            $this->publishes([__DIR__ . '/userfiles/controllers/EmailTemplatesController.php' => app_path('Http/Controllers/EmailTemplatesController.php')], 'cb_email_template_controller');
            $this->publishes([__DIR__ . '/userfiles/controllers/LandingPagesController.php' => app_path('Http/Controllers/LandingPagesController.php')], 'cb_landing_pages_controller');
            $this->publishes([__DIR__ . '/userfiles/controllers/AdminFooterMenusController.php' => app_path('Http/Controllers/AdminFooterMenusController.php')], 'cb_footer_menus_controller');
            $this->publishes([__DIR__ . '/userfiles/controllers/AdminHeaderMenusController.php' => app_path('Http/Controllers/AdminHeaderMenusController.php')], 'cb_header_menus_controller');
            //Publish Models
            $this->publishes([__DIR__.'/userfiles/Models/Menu.php' => app_path('Models/Menu.php')], 'menu_model');
            //$this->publishes([__DIR__.'/userfiles/Models/MenuTranslation.php' => app_path('Models/MenuTranslation.php')], 'menu_translation_ model');

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
            $this->publishes([__DIR__ . '/views/landing_page_builder/builder.blade.php' => resource_path('views/landing_page_builder/builder.blade.php')], 'landing_page_builder_view');
            $this->publishes([__DIR__ . '/views/landing_page_builder/templates.blade.php' => resource_path('views/landing_page_builder/templates.blade.php')], 'landing_page_builder_templates_view');

            $this->publishes([__DIR__ . '/landing_page_builder' => public_path('landing_page_builder')], 'landing_page_builder_files');
            # Email Builder
            $this->publishes([__DIR__ . '/views/email_builder/templates_builder.blade.php' => resource_path('views/email_builder/templates_builder.blade.php')], 'email_builder_view');
            $this->publishes([__DIR__ . '/email_builder' => public_path('email_builder')], 'email_builder_files');
            # lang
            $this->publishes([__DIR__ . '/localization' => resource_path('lang')], 'crudbooster_lang');

            # Menus Header & Menus footer
            $this->publishes([
                __DIR__.'/views/headermenus/index.blade.php' => resource_path('views/headermenus/index.blade.php'),
            ], 'header_menus_views');
            $this->publishes([
                __DIR__.'/views/footermenus/index.blade.php' => resource_path('views/footermenus/index.blade.php'),
            ], 'header_menus_views');
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
