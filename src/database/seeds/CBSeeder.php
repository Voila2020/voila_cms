<?php
//namespace Database\Seeders; Fix: Target class [CBSeeder] does not exist.

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Please wait updating the data...');
        # User
        if (DB::table('cms_users')->count() == 0) {
            $password = Hash::make('123456');
            $cms_users = DB::table('cms_users')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Super Admin',
                'email' => 'superadmin@voila.digital',
                'password' => $password,
                'id_cms_privileges' => 1,
                'status' => 'Active',
            ]);
            $cms_users = DB::table('cms_users')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Ahmad Voila',
                'email' => 'ahmad@voila.digital',
                'password' => $password,
                'id_cms_privileges' => 1,
                'status' => 'Active',
            ]);
        }
        $this->command->info("Create users completed");
        # User End

        # Email Templates
        $pass_temp_email = DB::table('cms_email_templates')->where('slug', 'forgot_password_backend')->get();
        if (!count($pass_temp_email)) {
            DB::table('cms_email_templates')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Email Template Forgot Password Backend',
                'slug' => 'forgot_password_backend',
                'content' => '<p>Hi,</p><p>Someone requested forgot password,</p><p>[link]</p><p><br></p><p>--</p><p>Regards,</p><p>Admin</p>',
                'template' => '<mjml><mj-body id="irdi"></mj-body></mjml>',
                'description' => 'Forgot Password',
                'from_name' => 'Voila System',
                'from_email' => 'test@voila.digital',
                'cc_email' => null,
                'priority' => 3,
            ]);
            $this->command->info("Create email templates completed");
        }

        # CB Modules
        $data = [
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Notifications',
                'icon' => 'fa fa-cog',
                'path' => 'notifications',
                'table_name' => 'cms_notifications',
                'controller' => 'NotificationsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Privileges',
                'icon' => 'fa fa-cog',
                'path' => 'privileges',
                'table_name' => 'cms_privileges',
                'controller' => 'PrivilegesController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Privileges_Roles',
                'icon' => 'fa fa-cog',
                'path' => 'privileges_roles',
                'table_name' => 'cms_privileges_roles',
                'controller' => 'PrivilegesController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Users_Management',
                'icon' => 'fa fa-users',
                'path' => 'users',
                'table_name' => 'cms_users',
                'controller' => 'AdminCmsUsersController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'settings',
                'icon' => 'fa fa-cog',
                'path' => 'settings',
                'table_name' => 'cms_settings',
                'controller' => 'SettingsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Module_Generator',
                'icon' => 'fa fa-database',
                'path' => 'module_generator',
                'table_name' => 'cms_moduls',
                'controller' => 'ModulsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Module_Status',
                'icon' => 'fa fa-database',
                'path' => 'module_status',
                'table_name' => 'cms_moduls',
                'controller' => 'ModulsStatusController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Website_Languages',
                'icon' => 'fa fa-database',
                'path' => 'website_languages',
                'table_name' => 'cms_moduls',
                'controller' => 'WebsiteLanguagesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Menu_Management',
                'icon' => 'fa fa-bars',
                'path' => 'menu_management',
                'table_name' => 'cms_menus',
                'controller' => 'MenusController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [

                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Email_Templates',
                'icon' => 'fa fa-envelope-o',
                'path' => 'email_templates',
                'table_name' => 'cms_email_templates',
                'controller' => 'EmailTemplatesController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Statistic_Builder',
                'icon' => 'fa fa-dashboard',
                'path' => 'statistic_builder',
                'table_name' => 'cms_statistics',
                'controller' => 'StatisticBuilderController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'API_Generator',
                'icon' => 'fa fa-cloud-download',
                'path' => 'api_generator',
                'table_name' => '',
                'controller' => 'ApiCustomController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Log_User_Access',
                'icon' => 'fa fa-flag-o',
                'path' => 'logs',
                'table_name' => 'cms_logs',
                'controller' => 'LogsController',
                'is_protected' => 1,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Forms',
                'icon' => 'fa fa-mail-forward',
                'path' => 'forms',
                'table_name' => 'forms',
                'controller' => 'AdminFormsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Pages',
                'icon' => 'fa fa-users',
                'path' => 'landing-pages',
                'table_name' => 'landing_pages',
                'controller' => 'LandingPagesController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Blocked IPS',
                'icon' => 'fa fa-ban',
                'path' => 'blocked_ips',
                'table_name' => 'cms_login_attempts',
                'controller' => 'LoginAttemptsController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Backup_Restore_DB',
                'icon' => 'fa fa-database',
                'path' => 'backup',
                'table_name' => null,
                'controller' => 'BackupRestoreDatabaseController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'SEO',
                'icon' => 'fa fa-language',
                'path' => 'seo',
                'table_name' => null,
                'controller' => 'AdminSeoController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Labels Translation',
                'icon' => 'fa fa-language',
                'path' => 'languages',
                'table_name' => null,
                'controller' => 'TranslationController',
                'is_protected' => 0,
                'is_active' => 1,
            ],
        ];

        foreach ($data as $k => $d) {
            if (DB::table('cms_moduls')->where('name', $d['name'])->count()) {
                unset($data[$k]);
            }
        }

        DB::table('cms_moduls')->insert($data);
        $this->command->info("Create default cb modules completed");
        # CB Modules End

        # CB Setting
        $data = [

            //LOGIN REGISTER STYLE
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'login_background_color',
                'label' => 'Login Background Color',
                'content' => null,
                'content_input_type' => 'text',
                'group_setting' => 'login_register_style',
                'dataenum' => null,
                'helper' => 'Input hexacode',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'login_font_color',
                'label' => 'Login Font Color',
                'content' => null,
                'content_input_type' => 'text',
                'group_setting' => 'login_register_style',
                'dataenum' => null,
                'helper' => 'Input hexacode',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'login_background_image',
                'label' => 'Login Background Image',
                'content' => null,
                'content_input_type' => 'upload_image',
                'group_setting' => 'login_register_style',
                'dataenum' => null,
                'helper' => null,
            ],

            //EMAIL SETTING
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'email_sender',
                'label' => 'email_sender',
                'content' => 'test@voila.digital',
                'content_input_type' => 'text',
                'group_setting' => 'email_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'smtp_driver',
                'label' => 'mail_driver',
                'content' => 'smtp',
                'content_input_type' => 'select',
                'group_setting' => 'email_setting',
                'dataenum' => 'smtp,mail,sendmail',
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'smtp_host',
                'label' => 'smtp_host',
                'content' => 'mail.voilahost.com',
                'content_input_type' => 'text',
                'group_setting' => 'email_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'smtp_port',
                'label' => 'smtp_port',
                'content' => '2525',
                'content_input_type' => 'text',
                'group_setting' => 'email_setting',
                'dataenum' => null,
                'helper' => 'default 25',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'smtp_username',
                'label' => 'smtp_username',
                'content' => 'test@voila.digital',
                'content_input_type' => 'text',
                'group_setting' => 'email_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'smtp_password',
                'label' => 'smtp_password',
                'content' => 'a84yAe0OL=',
                'content_input_type' => 'text',
                'group_setting' => 'email_setting',
                'dataenum' => null,
                'helper' => null,
            ],

            //APPLICATION SETTING
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'appname',
                'label' => 'application_name',
                'group_setting' => 'application_setting',
                'content' => 'Voila CMS',
                'content_input_type' => 'text',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'default_paper_size',
                'label' => 'default_paper_size',
                'group_setting' => 'application_setting',
                'content' => 'Legal',
                'content_input_type' => 'text',
                'dataenum' => null,
                'helper' => 'Paper size, ex : A4, Legal, etc',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'default_img',
                'label' => 'uploaded_default_image',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'application_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'default_img_compression',
                'label' => 'image_compression_value',
                'content' => '',
                'content_input_type' => 'text',
                'group_setting' => 'application_setting',
                'dataenum' => null,
                'helper' => 'def_img_quality',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'favicon',
                'label' => 'favicon',
                'content' => '',
                'content_input_type' => 'upload_image',
                'group_setting' => 'application_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'api_debug_mode',
                'label' => 'api_debug_mode',
                'content' => 'true',
                'content_input_type' => 'select',
                'group_setting' => 'application_setting',
                'dataenum' => 'true,false',
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'google_api_key',
                'label' => 'google_api_key',
                'content' => '',
                'content_input_type' => 'text',
                'group_setting' => 'application_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'google_fcm_key',
                'label' => 'google_fcm_key',
                'content' => '',
                'content_input_type' => 'text',
                'group_setting' => 'application_setting',
                'dataenum' => null,
                'helper' => null,
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'activate_notifications',
                'label' => 'activate_notifications',
                'content' => '',
                'content_input_type' => 'select',
                'group_setting' => 'application_setting',
                'dataenum' => 'true,false',
                'helper' => 'notifications_activity',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'default_language',
                'label' => 'default_cms_language',
                'content_input_type' => 'select',
                'group_setting' => 'language_setting',
                'dataenum' => 'العربية,english',
                'content' => 'english',
                'helper' => 'default_language',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'tow_languages_active',
                'label' => 'Activate Tow Languages',
                'content' => 'no',
                'content_input_type' => 'select',
                'group_setting' => 'language_setting',
                'dataenum' => 'yes,no',
                'helper' => 'activate_tow_languages',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'block_ip_in_hours',
                'label' => 'block_ip_in_hours',
                'content' => "24",
                'content_input_type' => 'text',
                'group_setting' => 'block_users_setting',
                'dataenum' => '',
                'helper' => 'block_ip_in_hours_helper',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'max_failed_login_trying',
                'label' => 'max_failed_login_trying',
                'content' => "5",
                'content_input_type' => 'text',
                'group_setting' => 'block_users_setting',
                'dataenum' => '',
                'helper' => 'max_failed_login_trying',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'recaptcha_site_key',
                'label' => 'recaptcha_site_key',
                'content' => '',
                'content_input_type' => 'text',
                'group_setting' => 'reCAPTCHA_setting',
            ],
            [
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'recaptcha_secret_key',
                'label' => 'recaptcha_secret_key',
                'content' => '',
                'content_input_type' => 'text',
                'group_setting' => 'reCAPTCHA_setting',
            ],
        ];

        foreach ($data as $row) {
            $count = DB::table('cms_settings')->where('name', $row['name'])->count();
            if ($count) {
                if ($count > 1) {
                    $newsId = DB::table('cms_settings')->where('name', $row['name'])->orderby('id', 'asc')->take(1)->first();
                    DB::table('cms_settings')->where('name', $row['name'])->where('id', '!=', $newsId->id)->delete();
                }
                continue;
            }
            DB::table('cms_settings')->insert($row);
        }
        $this->command->info("Create cb settings completed");
        # CB Setting End

        # CB Privilege
        if (DB::table('cms_privileges')->where('name', 'Super Administrator')->count() == 0) {
            DB::table('cms_privileges')->insert([
                'created_at' => date('Y-m-d H:i:s'),
                'name' => 'Super Administrator',
                'is_superadmin' => 1,
                'theme_color' => 'skin-red',
            ]);
        }
        if (DB::table('cms_privileges_roles')->count() == 0) {
            $modules = DB::table('cms_moduls')->get();
            $i = 1;
            foreach ($modules as $module) {

                $is_visible = 1;
                $is_create = 1;
                $is_read = 1;
                $is_edit = 1;
                $is_delete = 1;

                switch ($module->table_name) {
                    case 'cms_logs':
                        $is_create = 0;
                        $is_edit = 0;
                        break;
                    case 'cms_privileges_roles':
                        $is_visible = 0;
                        break;
                    case 'cms_apicustom':
                        $is_visible = 0;
                        break;
                    case 'cms_notifications':
                        $is_create = $is_read = $is_edit = $is_delete = 0;
                        break;
                }

                DB::table('cms_privileges_roles')->insert([
                    'created_at' => date('Y-m-d H:i:s'),
                    'is_visible' => $is_visible,
                    'is_create' => $is_create,
                    'is_edit' => $is_edit,
                    'is_delete' => $is_delete,
                    'is_read' => $is_read,
                    'id_cms_privileges' => 1,
                    'id_cms_moduls' => $module->id,
                ]);
                $i++;
            }
        }
        $this->command->info("Create roles completed");
        # CB Privilege End

        # Voia Seeder Start
        if (DB::table('landing_pages')->count() == 0) {
            $data = [
                'name' => 'Test',
                'title' => 'Test',
                'response_message' => '<p>thank you</p>',
                'is_template' => 0,
                'url' => 'test_page',
                'send_email_to' => 'test@voila.digital',
                'active' => 1,
                'is_rtl' => 0,
                'html' => '<body>
                <header class="header-banner">
                  <div class="container-width">
                    <div class="logo-container">
                      <div class="logo">GrapesJS
                      </div>
                    </div>
                    <nav class="menu">
                      <div class="menu-item">BUILDER
                      </div>
                      <div class="menu-item">TEMPLATE
                      </div>
                      <div class="menu-item">WEB
                      </div>
                    </nav>
                    <div class="clearfix">
                    </div>
                    <div class="lead-title">Build your templates without coding
                    </div>
                    <div class="sub-lead-title">All text blocks could be edited easily with double clicking on it. You can create new text blocks with the command from the left panel
                    </div>
                    <div class="lead-btn">Hover me
                    </div>
                  </div>
                </header>
                <section class="flex-sect">
                  <div class="container-width">
                    <div class="flex-title">Flex is the new black
                    </div>
                    <div class="flex-desc">With flexbox system you\'re able to build complex layouts easily and with free responsivity
                    </div>
                    <div class="cards">
                      <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                          <div class="card-title">Title one
                          </div>
                          <div class="card-sub-title">Subtitle one
                          </div>
                          <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header ch2">
                        </div>
                        <div class="card-body">
                          <div class="card-title">Title two
                          </div>
                          <div class="card-sub-title">Subtitle two
                          </div>
                          <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header ch3">
                        </div>
                        <div class="card-body">
                          <div class="card-title">Title three
                          </div>
                          <div class="card-sub-title">Subtitle three
                          </div>
                          <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header ch4">
                        </div>
                        <div class="card-body">
                          <div class="card-title">Title four
                          </div>
                          <div class="card-sub-title">Subtitle four
                          </div>
                          <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header ch5">
                        </div>
                        <div class="card-body">
                          <div class="card-title">Title five
                          </div>
                          <div class="card-sub-title">Subtitle five
                          </div>
                          <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                          </div>
                        </div>
                      </div>
                      <div class="card">
                        <div class="card-header ch6">
                        </div>
                        <div class="card-body">
                          <div class="card-title">Title six
                          </div>
                          <div class="card-sub-title">Subtitle six
                          </div>
                          <div class="card-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <section class="am-sect">
                  <div class="container-width">
                    <div class="am-container">
                      <img src="./img/phone-app.png" class="img-phone"/>
                      <div class="am-content">
                        <div class="am-pre">ASSET MANAGER
                        </div>
                        <div class="am-title">Manage your images with Asset Manager
                        </div>
                        <div class="am-desc">You can create image blocks with the command from the left panel and edit them with double click
                        </div>
                        <div class="am-post">Image uploading is not allowed in this demo
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <section class="blk-sect">
                  <div class="container-width">
                    <div class="blk-title">Blocks
                    </div>
                    <div class="blk-desc">Each element in HTML page could be seen as a block. On the left panel you could find different kind of blocks that you can create, move and style
                    </div>
                    <div class="price-cards">
                      <div class="price-card-cont">
                        <div class="price-card">
                          <div class="pc-title">Starter
                          </div>
                          <div class="pc-desc">Some random list
                          </div>
                          <div class="pc-feature odd-feat">+ Starter feature 1
                          </div>
                          <div class="pc-feature">+ Starter feature 2
                          </div>
                          <div class="pc-feature odd-feat">+ Starter feature 3
                          </div>
                          <div class="pc-feature">+ Starter feature 4
                          </div>
                          <div class="pc-amount odd-feat">$ 9,90/mo
                          </div>
                        </div>
                      </div>
                      <div class="price-card-cont">
                        <div class="price-card pc-regular">
                          <div class="pc-title">Regular
                          </div>
                          <div class="pc-desc">Some random list
                          </div>
                          <div class="pc-feature odd-feat">+ Regular feature 1
                          </div>
                          <div class="pc-feature">+ Regular feature 2
                          </div>
                          <div class="pc-feature odd-feat">+ Regular feature 3
                          </div>
                          <div class="pc-feature">+ Regular feature 4
                          </div>
                          <div class="pc-amount odd-feat">$ 19,90/mo
                          </div>
                        </div>
                      </div>
                      <div class="price-card-cont">
                        <div class="price-card pc-enterprise">
                          <div class="pc-title">Enterprise
                          </div>
                          <div class="pc-desc">Some random list
                          </div>
                          <div class="pc-feature odd-feat">+ Enterprise feature 1
                          </div>
                          <div class="pc-feature">+ Enterprise feature 2
                          </div>
                          <div class="pc-feature odd-feat">+ Enterprise feature 3
                          </div>
                          <div class="pc-feature">+ Enterprise feature 4
                          </div>
                          <div class="pc-amount odd-feat">$ 29,90/mo
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <section class="bdg-sect">
                  <div class="container-width">
                    <h1 class="bdg-title">The team
                    </h1>
                    <div class="badges">
                      <div class="badge">
                        <div class="badge-header">
                        </div>
                        <img src="img/team1.jpg" class="badge-avatar"/>
                        <div class="badge-body">
                          <div class="badge-name">Adam Smith
                          </div>
                          <div class="badge-role">CEO
                          </div>
                          <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit
                          </div>
                        </div>
                        <div class="badge-foot">
                          <span class="badge-link">f</span>
                          <span class="badge-link">t</span>
                          <span class="badge-link">ln</span>
                        </div>
                      </div>
                      <div class="badge">
                        <div class="badge-header">
                        </div>
                        <img src="img/team2.jpg" class="badge-avatar"/>
                        <div class="badge-body">
                          <div class="badge-name">John Black
                          </div>
                          <div class="badge-role">Software Engineer
                          </div>
                          <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit
                          </div>
                        </div>
                        <div class="badge-foot">
                          <span class="badge-link">f</span>
                          <span class="badge-link">t</span>
                          <span class="badge-link">ln</span>
                        </div>
                      </div>
                      <div class="badge">
                        <div class="badge-header">
                        </div>
                        <img src="img/team3.jpg" class="badge-avatar"/>
                        <div class="badge-body">
                          <div class="badge-name">Jessica White
                          </div>
                          <div class="badge-role">Web Designer
                          </div>
                          <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit
                          </div>
                        </div>
                        <div class="badge-foot">
                          <span class="badge-link">f</span>
                          <span class="badge-link">t</span>
                          <span class="badge-link">ln</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
                <footer class="footer-under">
                  <div class="container-width">
                    <div class="footer-container">
                      <div class="foot-lists">
                        <div class="foot-list">
                          <div class="foot-list-title">About us
                          </div>
                          <div class="foot-list-item">Contact
                          </div>
                          <div class="foot-list-item">Events
                          </div>
                          <div class="foot-list-item">Company
                          </div>
                          <div class="foot-list-item">Jobs
                          </div>
                          <div class="foot-list-item">Blog
                          </div>
                        </div>
                        <div class="foot-list">
                          <div class="foot-list-title">Services
                          </div>
                          <div class="foot-list-item">Education
                          </div>
                          <div class="foot-list-item">Partner
                          </div>
                          <div class="foot-list-item">Community
                          </div>
                          <div class="foot-list-item">Forum
                          </div>
                          <div class="foot-list-item">Download
                          </div>
                          <div class="foot-list-item">Upgrade
                          </div>
                        </div>
                        <div class="clearfix">
                        </div>
                      </div>
                      <div class="form-sub">
                        <div class="foot-form-cont">
                          <div class="foot-form-title">Subscribe
                          </div>
                          <div class="foot-form-desc">Subscribe to our newsletter to receive exclusive offers and the latest news
                          </div>
                          <input type="text" name="name" placeholder="Name" class="sub-input"/>
                          <input type="text" name="email" placeholder="Email" class="sub-input"/>
                          <button type="button" class="sub-btn">Submit</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="copyright">
                    <div class="container-width">
                      <div class="made-with">
                        made with GrapesJS
                      </div>
                      <div class="foot-social-btns">facebook twitter linkedin mail
                      </div>
                      <div class="clearfix">
                      </div>
                    </div>
                  </div>
                </footer>
              </body>',
                'css' => '* {
                    box-sizing: border-box;
                    }
                    body {
                    margin: 0;
                    }
                    .clearfix{
                    clear:both;
                    }
                    .header-banner{
                    padding-top:35px;
                    padding-bottom:100px;
                    color:#ffffff;
                    font-family:Helvetica, serif;
                    font-weight:100;
                    background-image:url("https://grapesjs.com/img/bg-gr-v.png"), url("https://grapesjs.com/img/work-desk.jpg");
                    background-attachment:scroll, scroll;
                    background-position:left top, center center;
                    background-repeat:repeat-y, no-repeat;
                    background-size:contain, cover;
                    }
                    .container-width{
                    width:90%;
                    max-width:1150px;
                    margin:0 auto;
                    }
                    .logo-container{
                    float:left;
                    width:50%;
                    }
                    .logo{
                    background-color:#fff;
                    border-radius:5px;
                    width:130px;
                    padding:10px;
                    min-height:30px;
                    text-align:center;
                    line-height:30px;
                    color:#4d114f;
                    font-size:23px;
                    }
                    .menu{
                    float:right;
                    width:50%;
                    }
                    .menu-item{
                    float:right;
                    font-size:15px;
                    color:#eee;
                    width:130px;
                    padding:10px;
                    min-height:50px;
                    text-align:center;
                    line-height:30px;
                    font-weight:400;
                    }
                    .lead-title{
                    margin:150px 0 30px 0;
                    font-size:40px;
                    }
                    .sub-lead-title{
                    max-width:650px;
                    line-height:30px;
                    margin-bottom:30px;
                    color:#c6c6c6;
                    }
                    .lead-btn{
                    margin-top:15px;
                    padding:10px;
                    width:190px;
                    min-height:30px;
                    font-size:20px;
                    text-align:center;
                    letter-spacing:3px;
                    line-height:30px;
                    background-color:#d983a6;
                    border-radius:5px;
                    transition:all 0.5s ease;
                    cursor:pointer;
                    }
                    .lead-btn:hover{
                    background-color:#ffffff;
                    color:#4c114e;
                    }
                    .lead-btn:active{
                    background-color:#4d114f;
                    color:#fff;
                    }
                    .flex-sect{
                    background-color:#fafafa;
                    padding:100px 0;
                    font-family:Helvetica, serif;
                    }
                    .flex-title{
                    margin-bottom:15px;
                    font-size:2em;
                    text-align:center;
                    font-weight:700;
                    color:#555;
                    padding:5px;
                    }
                    .flex-desc{
                    margin-bottom:55px;
                    font-size:1em;
                    color:rgba(0, 0, 0, 0.5);
                    text-align:center;
                    padding:5px;
                    }
                    .cards{
                    padding:20px 0;
                    display:flex;
                    justify-content:space-around;
                    flex-flow:wrap;
                    }
                    .card{
                    background-color:white;
                    height:300px;
                    width:300px;
                    margin-bottom:30px;
                    box-shadow:0 1px 2px 0 rgba(0, 0, 0, 0.2);
                    border-radius:2px;
                    transition:all 0.5s ease;
                    font-weight:100;
                    overflow:hidden;
                    }
                    .card:hover{
                    margin-top:-5px;
                    box-shadow:0 20px 30px 0 rgba(0, 0, 0, 0.2);
                    }
                    .card-header{
                    height:155px;
                    background-image:url("https://via.placeholder.com/350x250/78c5d6/fff");
                    background-size:cover;
                    background-position:center center;
                    }
                    .card-header.ch2{
                    background-image:url("https://via.placeholder.com/350x250/459ba8/fff");
                    }
                    .card-header.ch3{
                    background-image:url("https://via.placeholder.com/350x250/79c267/fff");
                    }
                    .card-header.ch4{
                    background-image:url("https://via.placeholder.com/350x250/c5d647/fff");
                    }
                    .card-header.ch5{
                    background-image:url("https://via.placeholder.com/350x250/f28c33/fff");
                    }
                    .card-header.ch6{
                    background-image:url("https://via.placeholder.com/350x250/e868a2/fff");
                    }
                    .card-body{
                    padding:15px 15px 5px 15px;
                    color:#555;
                    }
                    .card-title{
                    font-size:1.4em;
                    margin-bottom:5px;
                    }
                    .card-sub-title{
                    color:#b3b3b3;
                    font-size:1em;
                    margin-bottom:15px;
                    }
                    .card-desc{
                    font-size:0.85rem;
                    line-height:17px;
                    }
                    .am-sect{
                    padding-top:100px;
                    padding-bottom:100px;
                    font-family:Helvetica, serif;
                    }
                    .img-phone{
                    float:left;
                    }
                    .am-container{
                    display:flex;
                    flex-wrap:wrap;
                    align-items:center;
                    justify-content:space-around;
                    }
                    .am-content{
                    float:left;
                    padding:7px;
                    width:490px;
                    color:#444;
                    font-weight:100;
                    margin-top:50px;
                    }
                    .am-pre{
                    padding:7px;
                    color:#b1b1b1;
                    font-size:15px;
                    }
                    .am-title{
                    padding:7px;
                    font-size:25px;
                    font-weight:400;
                    }
                    .am-desc{
                    padding:7px;
                    font-size:17px;
                    line-height:25px;
                    }
                    .am-post{
                    padding:7px;
                    line-height:25px;
                    font-size:13px;
                    }
                    .blk-sect{
                    padding-top:100px;
                    padding-bottom:100px;
                    background-color:#222222;
                    font-family:Helvetica, serif;
                    }
                    .blk-title{
                    color:#fff;
                    font-size:25px;
                    text-align:center;
                    margin-bottom:15px;
                    }
                    .blk-desc{
                    color:#b1b1b1;
                    font-size:15px;
                    text-align:center;
                    max-width:700px;
                    margin:0 auto;
                    font-weight:100;
                    }
                    .price-cards{
                    margin-top:70px;
                    display:flex;
                    flex-wrap:wrap;
                    align-items:center;
                    justify-content:space-around;
                    }
                    .price-card-cont{
                    width:300px;
                    padding:7px;
                    float:left;
                    }
                    .price-card{
                    margin:0 auto;
                    min-height:350px;
                    background-color:#d983a6;
                    border-radius:5px;
                    font-weight:100;
                    color:#fff;
                    width:90%;
                    }
                    .pc-title{
                    font-weight:100;
                    letter-spacing:3px;
                    text-align:center;
                    font-size:25px;
                    background-color:rgba(0, 0, 0, 0.1);
                    padding:20px;
                    }
                    .pc-desc{
                    padding:75px 0;
                    text-align:center;
                    }
                    .pc-feature{
                    color:rgba(255,255,255,0.5);
                    background-color:rgba(0, 0, 0, 0.1);
                    letter-spacing:2px;
                    font-size:15px;
                    padding:10px 20px;
                    }
                    .pc-feature:nth-of-type(2n){
                    background-color:transparent;
                    }
                    .pc-amount{
                    background-color:rgba(0, 0, 0, 0.1);
                    font-size:35px;
                    text-align:center;
                    padding:35px 0;
                    }
                    .pc-regular{
                    background-color:#da78a0;
                    }
                    .pc-enterprise{
                    background-color:#d66a96;
                    }
                    .footer-under{
                    background-color:#312833;
                    padding-bottom:100px;
                    padding-top:100px;
                    min-height:500px;
                    color:#eee;
                    position:relative;
                    font-weight:100;
                    font-family:Helvetica,serif;
                    }
                    .copyright{
                    background-color:rgba(0, 0, 0, 0.15);
                    color:rgba(238, 238, 238, 0.5);
                    bottom:0;
                    padding:1em 0;
                    position:absolute;
                    width:100%;
                    font-size:0.75em;
                    }
                    .made-with{
                    float:left;
                    width:50%;
                    padding:5px 0;
                    }
                    .foot-social-btns{
                    display:none;
                    float:right;
                    width:50%;
                    text-align:right;
                    padding:5px 0;
                    }
                    .footer-container{
                    display:flex;
                    flex-wrap:wrap;
                    align-items:stretch;
                    justify-content:space-around;
                    }
                    .foot-list{
                    float:left;
                    width:200px;
                    }
                    .foot-list-title{
                    font-weight:400;
                    margin-bottom:10px;
                    padding:0.5em 0;
                    }
                    .foot-list-item{
                    color:rgba(238, 238, 238, 0.8);
                    font-size:0.8em;
                    padding:0.5em 0;
                    }
                    .foot-list-item:hover{
                    color:rgba(238, 238, 238, 1);
                    }
                    .foot-form-cont{
                    width:300px;
                    float:right;
                    }
                    .foot-form-title{
                    color:rgba(255,255,255,0.75);
                    font-weight:400;
                    margin-bottom:10px;
                    padding:0.5em 0;
                    text-align:right;
                    font-size:2em;
                    }
                    .foot-form-desc{
                    font-size:0.8em;
                    color:rgba(255,255,255,0.55);
                    line-height:20px;
                    text-align:right;
                    margin-bottom:15px;
                    }
                    .sub-input{
                    width:100%;
                    margin-bottom:15px;
                    padding:7px 10px;
                    border-radius:2px;
                    color:#fff;
                    background-color:#554c57;
                    border:none;
                    }
                    .sub-btn{
                    width:100%;
                    margin:15px 0;
                    background-color:#785580;
                    border:none;
                    color:#fff;
                    border-radius:2px;
                    padding:7px 10px;
                    font-size:1em;
                    cursor:pointer;
                    }
                    .sub-btn:hover{
                    background-color:#91699a;
                    }
                    .sub-btn:active{
                    background-color:#573f5c;
                    }
                    .bdg-sect{
                    padding-top:100px;
                    padding-bottom:100px;
                    font-family:Helvetica, serif;
                    background-color:#fafafa;
                    }
                    .bdg-title{
                    text-align:center;
                    font-size:2em;
                    margin-bottom:55px;
                    color:#555555;
                    }
                    .badges{
                    padding:20px;
                    display:flex;
                    justify-content:space-around;
                    align-items:flex-start;
                    flex-wrap:wrap;
                    }
                    .badge{
                    width:290px;
                    font-family:Helvetica, serif;
                    background-color:white;
                    margin-bottom:30px;
                    box-shadow:0 2px 2px 0 rgba(0, 0, 0, 0.2);
                    border-radius:3px;
                    font-weight:100;
                    overflow:hidden;
                    text-align:center;
                    }
                    .badge-header{
                    height:115px;
                    background-image:url("https://grapesjs.com/img/bg-gr-v.png"), url("https://grapesjs.com/img/work-desk.jpg");
                    background-position:left top, center center;
                    background-attachment:scroll, fixed;
                    overflow:hidden;
                    }
                    .badge-name{
                    font-size:1.4em;
                    margin-bottom:5px;
                    }
                    .badge-role{
                    color:#777;
                    font-size:1em;
                    margin-bottom:25px;
                    }
                    .badge-desc{
                    font-size:0.85rem;
                    line-height:20px;
                    }
                    .badge-avatar{
                    width:100px;
                    height:100px;
                    border-radius:100%;
                    border:5px solid #fff;
                    box-shadow:0 1px 1px 0 rgba(0, 0, 0, 0.2);
                    margin-top:-75px;
                    position:relative;
                    }
                    .badge-body{
                    margin:35px 10px;
                    }
                    .badge-foot{
                    color:#fff;
                    background-color:#a290a5;
                    padding-top:13px;
                    padding-bottom:13px;
                    display:flex;
                    justify-content:center;
                    }
                    .badge-link{
                    height:35px;
                    width:35px;
                    line-height:35px;
                    font-weight:700;
                    background-color:#fff;
                    color:#a290a5;
                    display:block;
                    border-radius:100%;
                    margin:0 10px;
                    }
                    @media (max-width: 768px){
                    .foot-form-cont{
                        width:400px;
                    }
                    .foot-form-title{
                        width:autopx;
                    }
                    }
                    @media (max-width: 480px){
                    .foot-lists{
                        display:none;
                    }
                    }',
            ];
            DB::table('landing_pages')->insert($data);
        }

        $data = [
            [
                'name' => 'SEO',
                'type' => 'Module',
                'path' => 'seo',
                'color' => 'normal',
                'icon' => 'fa fa-language',
                'parent_id' => 0,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'labels_translation',
                'type' => 'Module',
                'path' => 'languages',
                'color' => 'normal',
                'icon' => 'fa fa-language',
                'parent_id' => 0,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'pages_and_forms',
                'type' => 'URL',
                'path' => '#',
                'color' => 'normal',
                'icon' => 'fa fa-th-list',
                'parent_id' => 0,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Forms',
                'type' => 'Module',
                'path' => 'forms',
                'color' => 'normal',
                'icon' => 'fa fa-list-alt',
                'parent_id' => 3,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Pages',
                'type' => 'Module',
                'path' => 'landing-pages',
                'color' => 'normal',
                'icon' => 'fa fa-file-o',
                'parent_id' => 3,
                'is_active' => 1,
                'is_dashboard' => 0,
                'id_cms_privileges' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        foreach ($data as $k => $d) {
            if (DB::table('cms_menus')->where('name', $d['name'])->where('path', $d['path'])->count()) {
                unset($data[$k]);
            }
        }
        DB::table('cms_menus')->insert($data);

        $menus = DB::table('cms_menus')->get();
        foreach ($menus as $menu) {
            $menuPrivilege = DB::table('cms_menus_privileges')
                ->where('id_cms_privileges', 1)
                ->where('id_cms_menus', $menu->id)
                ->first();
            if (!$menuPrivilege) {
                DB::table('cms_menus_privileges')->insert([
                    'id_cms_menus' => $menu->id,
                    'id_cms_privileges' => 1,
                ]);
            }
        }
        if (DB::table('languages')->count() == 0) {
            $data = [
                [
                    'name' => 'English',
                    'code' => 'en',
                    'active' => 1,
                    'default' => 1,
                ],
                [
                    'name' => 'Arabic',
                    'code' => 'ar',
                    'active' => 1,
                    'default' => null,
                ],
            ];
            DB::table('languages')->insert($data);
        }
        # Fields
        if (DB::table('fields')->count() == 0) {
            $data = [
                [
                    'title' => 'text',
                    'multi' => 0,
                ],
                [
                    'title' => 'email',
                    'multi' => 0,
                ],
                [
                    'title' => 'radio',
                    'multi' => 1,
                ],
                [
                    'title' => 'checkbox',
                    'multi' => 1,
                ],
                [
                    'title' => 'select',
                    'multi' => 1,
                ],
            ];
            DB::table('fields')->insert($data);
        }
        # Voila Seeder End

        $this->command->info('All cb seeders completed !');
    }
}
