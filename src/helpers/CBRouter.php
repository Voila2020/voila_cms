<?php

namespace crocodicstudio\crudbooster\helpers;

use crocodicstudio\crudbooster\controllers\AdminController;
use crocodicstudio\crudbooster\controllers\CBController;
use crocodicstudio\crudbooster\controllers\CmsFormController;
use crocodicstudio\crudbooster\controllers\EmailTemplatesController;
use crocodicstudio\crudbooster\controllers\FileManagerController;
use crocodicstudio\crudbooster\controllers\PasswordReset;
use crocodicstudio\crudbooster\controllers\SeoController;
use crocodicstudio\crudbooster\controllers\TranslationController;
use crocodicstudio\crudbooster\middlewares\CBAuthAPI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class CBRouter
{
    private static $cb_namespace = '\crocodicstudio\crudbooster\controllers';

    public static function getCBControllerFiles()
    {
        $controllers = glob(__DIR__ . '/../controllers/*.php');
        $result = [];
        foreach ($controllers as $file) {
            $result[] = str_replace('.php', '', basename($file));
        }
        return $result;
    }

    private static function apiRoute()
    {
        // API Authentication
        Route::group(['middleware' => ['api'], 'namespace' => static::$cb_namespace], function () {
            Route::post("api/get-token", "ApiAuthorizationController@postGetToken");
        });

        Route::group(['middleware' => ['api', CBAuthAPI::class], 'namespace' => 'App\Http\Controllers'], function () {

            $dir = scandir(base_path("app/Http/Controllers"));
            foreach ($dir as $v) {
                $v = str_replace('.php', '', $v);
                $names = array_filter(preg_split('/(?=[A-Z])/', str_replace('Controller', '', $v)));
                $names = strtolower(implode('_', $names));

                if (substr($names, 0, 4) == 'api_') {
                    $names = str_replace('api_', '', $names);
                    Route::any('api/' . $names, $v . '@execute_api');
                }
            }
        });
    }

    private static function uploadRoute()
    {
        Route::group(['middleware' => ['web'], 'namespace' => static::$cb_namespace], function () {
            Route::get('api-documentation', ['uses' => 'ApiCustomController@apiDocumentation', 'as' => 'apiDocumentation']);
            Route::get('download-documentation-postman', ['uses' => 'ApiCustomController@getDownloadPostman', 'as' => 'downloadDocumentationPostman']);
            Route::get('uploads/{one?}/{two?}/{three?}/{four?}/{five?}', ['uses' => 'FileController@getPreview', 'as' => 'fileControllerPreview']);
        });
    }

    private static function authRoute()
    {
        Route::group(['middleware' => ['web'], 'prefix' => config('crudbooster.ADMIN_PATH'), 'namespace' => static::$cb_namespace], function () {

            Route::post('unlock-screen', ['uses' => 'AdminController@postUnlockScreen', 'as' => 'postUnlockScreen']);
            Route::get('lock-screen', ['uses' => 'AdminController@getLockscreen', 'as' => 'getLockScreen']);
            Route::post('forgot', ['uses' => 'AdminController@postForgot', 'as' => 'postForgot']);
            Route::get('forgot', ['uses' => 'AdminController@getForgot', 'as' => 'getForgot']);
            Route::post('register', ['uses' => 'AdminController@postRegister', 'as' => 'postRegister']);
            Route::get('register', ['uses' => 'AdminController@getRegister', 'as' => 'getRegister']);
            Route::get('logout', ['uses' => 'AdminController@getLogout', 'as' => 'getLogout']);
            Route::post('login', ['uses' => 'AdminController@postLogin', 'as' => 'postLogin']);
            Route::get('login', ['uses' => 'AdminController@getLogin', 'as' => 'getLogin']);
        });
    }

    private static function userControllerRoute()
    {
        Route::group([
            'middleware' => ['web', '\crocodicstudio\crudbooster\middlewares\CBBackend'],
            'prefix' => config('crudbooster.ADMIN_PATH'),
            'namespace' => 'App\Http\Controllers',
        ], function () {

            $modules = [];
            try {
                // Todo: change table
                $modules = db('cms_moduls')
                    ->where('path', '!=', '')
                    ->where('controller', '!=', '')
                    ->whereNotNull("path")
                    ->whereNotNull("controller")
                    // ->where('is_protected', 0)
                    ->where('deleted_at', null)
                    ->get();
            } catch (\Exception $e) {
                Log::error("Load cms moduls is failed. Caused = " . $e->getMessage());
            }

            foreach ($modules as $v) {
                if (@$v->path && @$v->controller) {
                    try {
                        CRUDBooster::routeController($v->path, $v->controller);
                    } catch (\Exception $e) {
                        Log::error("Path = " . $v->path . "\nController = " . $v->controller . "\nError = " . $e->getMessage());
                    }
                }
            }
        });
    }

    private static function cbRoute()
    {
        Route::group([
            'middleware' => ['web', '\crocodicstudio\crudbooster\middlewares\CBBackend'],
            'prefix' => config('crudbooster.ADMIN_PATH'),
            'namespace' => static::$cb_namespace,
        ], function () {

            // Todo: change table
            if (request()->is(config('crudbooster.ADMIN_PATH'))) {
                $menus = db('cms_menus')->where('is_dashboard', 1)->first();
                if ($menus) {
                    Route::get('/', 'StatisticBuilderController@getDashboard');
                } else {
                    CRUDBooster::routeController('/', 'AdminController', static::$cb_namespace);
                }
            }


            CRUDBooster::routeController('api_generator', 'ApiCustomController', static::$cb_namespace);

            // Todo: change table
            $modules = [];
            try {
                $modules = db('cms_moduls')->whereIn('controller', CBRouter::getCBControllerFiles())->get();
            } catch (\Exception $e) {
                Log::error("Load cms moduls is failed. Caused = " . $e->getMessage());
            }

            foreach ($modules as $v) {
                if (@$v->path && @$v->controller) {
                    try {
                        CRUDBooster::routeController($v->path, $v->controller, static::$cb_namespace);
                    } catch (\Exception $e) {
                        Log::error("Path = " . $v->path . "\nController = " . $v->controller . "\nError = " . $e->getMessage());
                    }
                }
            }
        });
    }

    private static function voilaCMSRoutes()
    {
        # page builder
        Route::group([
            'namespace' => static::$cb_namespace,
        ], function () {
            Route::post('submit-form/{id}', [CmsFormController::class, 'submit']);
            Route::get('thankyou/{id}', [CmsFormController::class, 'getLandingPageThankyou']);
        });
        Route::group([
            'middleware' => ['web', '\crocodicstudio\crudbooster\middlewares\CBBackend'],
            'prefix' => "",
            'namespace' => static::$cb_namespace
        ], function () {
            # file-manager
            Route::get('/filemanager-dialog', [FileManagerController::class, 'index'])->name('dialog');
            Route::match(array('GET', 'POST'), '/upload', [FileManagerController::class, 'upload'])->name('filemanager.upload');
            Route::match(array('GET', 'POST'), '/execute', [FileManagerController::class, 'execute'])->name('filemanager.excute');
            Route::match(array('GET', 'POST'), '/ajax_calls', [FileManagerController::class, 'ajaxCall'])->name("filemanager.ajax_calls");
            Route::post('/download', [FileManagerController::class, 'forceDownload'])->name("filemanager.download");
        });
        Route::group([
            'middleware' => ['web', '\crocodicstudio\crudbooster\middlewares\CBBackend'],
            'prefix' => config('crudbooster.ADMIN_PATH'),
            'namespace' => static::$cb_namespace,
        ], function () {
            # Switch button
            Route::post('/edit-switch-action', [AdminController::class, 'postEditSwitchAction']);
            # Seo
            Route::post('/sort-table', [AdminController::class, 'postSortTable'])->name('sortTable');
            Route::get('/seo-home', [SeoController::class, 'get'])->name('seo-home');
            Route::get('/seo/{model}/{model_id?}', [SeoController::class, 'get'])->name('seo-model');
            Route::post('/seo-store/{model}', [SeoController::class, 'store'])->name('seo-store');
            # Translate
            Route::get('/languages', [TranslationController::class, 'index'])->name('languages');
            Route::post('translations/update', [TranslationController::class, 'transUpdate'])->name('translation.update.json');
            Route::post('translations/updateKey', [TranslationController::class, 'transUpdateKey'])->name('translation.update.json.key');
            Route::delete('translations/destroy/{key}', [TranslationController::class, 'destroy'])->name('translations.destroy');
            Route::post('translations/create', [TranslationController::class, 'store'])->name('translations.create');
            # Form
            Route::get('/getForms', [CmsFormController::class, 'getForms']);
            Route::get('/getFormCode/{id}', [CmsFormController::class, 'getFormCode']);
            # logs
            Route::get('/clear-logs', [AdminController::class, 'clearLogs']);
            # Switch Language
            Route::get('/switch-language/{locale}', [AdminController::class, 'switchLanguage'])->name('cb.switch_language');
            # Email Builder
            Route::get('/email-builder/{id?}', [EmailTemplatesController::class, 'showEmailBuilder'])->name('email_builder.index');
            Route::put('/email_templates/save-template/{id}', [EmailTemplatesController::class, 'saveEmailTemplate'])->name("email_builder.store");
            Route::get('/email-builder-templates/{id}', [EmailTemplatesController::class, 'showEmailBuilderTemplates'])->name('email_builder_templates.index');
        });

        Route::group([
            'middleware' => ['web'],
            'prefix' => config('crudbooster.ADMIN_PATH'),
            'namespace' => static::$cb_namespace,
        ], function () {
            # reset password
            Route::post('/reset-password', [AdminController::class, 'resetPassword'])->name('cms_reset_password');
            Route::get('/password/reset/{token}', [AdminController::class, 'viewPasswordReset'])->name('cms_view_reset_page');
        });
    }

    public static function route()
    {
        static::apiRoute();
        static::uploadRoute();
        static::authRoute();
        static::userControllerRoute();
        static::cbRoute();
        static::voilaCMSRoutes();
    }
}
