<?php

namespace crocodicstudio\crudbooster\commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class CrudboosterInstallationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crudbooster:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUDBooster Installation Command';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $this->header();

        $this->checkRequirements();

        $this->info('Installing: ');

        if ($this->confirm('Do you have setting the database configuration at .env ?')) {

            # delete directories
            if (file_exists(public_path('vendor'))) {
                if ($this->confirm('Do you want to replace your files ?')) {
                    if (file_exists(public_path('vendor'))) {
                        File::deleteDirectory(public_path('vendor'));
                    }

                    if (file_exists(public_path('landing_page_builder'))) {
                        File::deleteDirectory(public_path('landing_page_builder'));
                    }

                    if (file_exists(public_path('landing_page'))) {
                        File::deleteDirectory(public_path('landing_page'));
                    }

                    $langDirectories = [
                        resource_path('lang/ar'),
                        resource_path('lang/en'),
                        resource_path('lang/es'),
                        resource_path('lang/id'),
                        resource_path('lang/pt_br'),
                        resource_path('lang/ru'),
                        resource_path('lang/tr'),
                        resource_path('lang/zh-CN'),
                    ];
                    foreach ($langDirectories as $langDirectory) {
                        if (file_exists($langDirectory)) {
                            File::deleteDirectory($langDirectory);
                        }
                    }

                    if (file_exists(config_path('crudbooster.php'))) {
                        File::delete(config_path('crudbooster.php'));
                    }

                }
            }

            if (file_exists(app_path('Http/Controllers/AdminCmsUsersController.php'))) {
                File::delete(app_path('Http/Controllers/AdminCmsUsersController.php'));
            }

            if (!file_exists(public_path('vendor'))) {
                mkdir(public_path('vendor'), 0777);
                mkdir(public_path('vendor/filemanager'), 0777);
                mkdir(public_path('vendor/filemanager/img'), 0777);
                mkdir(public_path('vendor/filemanager/images'), 0777);
                mkdir(public_path('vendor/filemanager/css'), 0777);
                mkdir(public_path('vendor/filemanager/js'), 0777);
                mkdir(public_path('vendor/filemanager/lang'), 0777);
                mkdir(public_path('vendor/filemanager/svg'), 0777);
            }

            if (!file_exists(public_path('landing_page_builder'))) {
                mkdir(public_path('landing_page_builder'), 0777);
            }

            if (!file_exists(public_path('landing_page'))) {
                mkdir(public_path('landing_page'), 0777);
            }

            if (!file_exists(resource_path('landing_page_builder'))) {
                mkdir(resource_path('landing_page_builder'), 0777);
            }

            if (!file_exists(app_path('Rules'))) {
                mkdir(app_path('Rules'), 0777);
            }

            $this->info('Publishing crudbooster assets...');
            $this->call('vendor:publish', ['--provider' => 'crocodicstudio\crudbooster\CRUDBoosterServiceProvider']);

            $this->info('Dumping the autoloaded files and reloading all new files...');
            $composer = $this->findComposer();

            $process = (app()->version() >= 7.0)
            ? new Process([$composer . ' dumpautoload'])
            : new Process($composer . ' dumpautoload');

            $process->setWorkingDirectory(base_path())->run();

            $this->info('Migrating database...');

            if (!class_exists('CBSeeder')) {
                require_once __DIR__ . '/../database/seeds/CBSeeder.php';
            }

            $this->call('migrate');
            $this->call('db:seed', ['--class' => 'CBSeeder']);
            $this->call('config:clear');
            if (app()->version() < 5.6) {
                $this->call('optimize');
            }

            $this->info('Installing CRUDBooster Is Completed ! Thank You :)');
        } else {
            $this->info('Setup Aborted !');
            $this->info('Please setting the database configuration for first !');
        }

        $this->footer();
    }

    private function header()
    {
        $this->info("
#     __________  __  ______  ____                   __
#    / ____/ __ \/ / / / __ \/ __ )____  ____  _____/ /____  _____
#   / /   / /_/ / / / / / / / __  / __ \/ __ \/ ___/ __/ _ \/ ___/
#  / /___/ _, _/ /_/ / /_/ / /_/ / /_/ / /_/ (__  ) /_/  __/ /
#  \____/_/ |_|\____/_____/_____/\____/\____/____/\__/\___/_/
#
			");
        $this->info('--------- :===: Thanks for choosing CRUDBooster :==: ---------------');
        $this->info('====================================================================');
    }

    private function checkRequirements()
    {
        $this->info('System Requirements Checking:');
        $system_failed = 0;
        $laravel = app();

        $this->info("Your laravel version: " . $laravel::VERSION);
        $this->info("---");

        if (version_compare($laravel::VERSION, "6.0", ">=")) {
            $this->info('Laravel Version (>= 6.x): [Good]');
        } else {
            $this->info('Laravel Version (>= 6.x): [Bad]');
            $system_failed++;
        }

        if (version_compare(phpversion(), '7.2.0', '>=')) {
            $this->info('PHP Version (>= 7.2.*): [Good]');
        } else {
            $this->info('PHP Version (>= 7.2.*): [Bad] Yours: ' . phpversion());
            $system_failed++;
        }

        if (extension_loaded('mbstring')) {
            $this->info('Mbstring extension: [Good]');
        } else {
            $this->info('Mbstring extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('openssl')) {
            $this->info('OpenSSL extension: [Good]');
        } else {
            $this->info('OpenSSL extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('pdo')) {
            $this->info('PDO extension: [Good]');
        } else {
            $this->info('PDO extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('tokenizer')) {
            $this->info('Tokenizer extension: [Good]');
        } else {
            $this->info('Tokenizer extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('xml')) {
            $this->info('XML extension: [Good]');
        } else {
            $this->info('XML extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('gd')) {
            $this->info('GD extension: [Good]');
        } else {
            $this->info('GD extension: [Bad]');
            $system_failed++;
        }

        if (extension_loaded('fileinfo')) {
            $this->info('PHP Fileinfo extension: [Good]');
        } else {
            $this->info('PHP Fileinfo extension: [Bad]');
            $system_failed++;
        }

        if (is_writable(base_path('public'))) {
            $this->info('/public dir is writable: [Good]');
        } else {
            $this->info('/public dir is writable: [Bad]');
            $system_failed++;
        }

        if ($system_failed != 0) {
            $this->info('Sorry unfortunately your system is not meet with our requirements !');
            $this->footer(false);
        }
        $this->info('--');
    }

    private function footer($success = true)
    {
        $this->info('--');
        $this->info('Homepage : http://www.crudbooster.com');
        $this->info('Github : https://github.com/crocodic-studio/crudbooster');
        $this->info('Documentation : https://github.com/crocodic-studio/crudbooster/blob/master/docs/en/index.md');
        $this->info('====================================================================');
        if ($success == true) {
            $this->info('------------------- :===: Completed !! :===: ------------------------');
        } else {
            $this->info('------------------- :===: Failed !!    :===: ------------------------');
        }
        exit;
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd() . '/composer.phar')) {
            return '"' . PHP_BINARY . '" ' . getcwd() . '/composer.phar';
        }

        return 'composer';
    }
}
