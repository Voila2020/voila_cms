<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-{{ cbLang('left') }} image">
                <img src="{{ CRUDBooster::myPhoto() }}" class="img-circle" alt="{{ cbLang('user_image') }}" />
            </div>
            <div class="pull-{{ cbLang('left') }} info">
                <p>{{ CRUDBooster::myName() }}</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> {{ cbLang('online') }}</a>
            </div>
        </div>


        <div class='main-menu'>

            <!-- Sidebar Menu -->
            <ul class="sidebar-menu">
                <li class="header">{{ cbLang('menu_navigation') }}</li>
                <!-- Optionally, you can add icons to the links -->

                <?php
                $dashboard = CRUDBooster::sidebarDashboard();
                $dashboard_href = CRUDBooster::adminPath();
                if ($dashboard->type == 'Statistic') {
                    $dashboard_href = CRUDBooster::adminPath($dashboard->path);
                }
                ?>
                @if ($dashboard)
                    <li data-id='{{ $dashboard->id }}'
                        class="{{ Request::is(config('crudbooster.ADMIN_PATH')) ? 'active' : '' }}"><a
                            href='{{ $dashboard_href }}'
                            class='{{ $dashboard->color ? 'text-' . $dashboard->color : '' }}'><i
                                class='fa fa-dashboard'></i>
                            <span>{{ cbLang('text_dashboard') }}</span> </a></li>
                @endif
                @foreach (CRUDBooster::sidebarMenu() as $menu)
                    <?php
                    $isActiveLink = '';
                    # check if active link is the current link
                    if (Request::is($menu->url_path)) {
                        $isActiveLink = 'active';
                    }
                    # regular expression to check if the next char is '/' so this route will active the current side bar item menu
                    $matches = [];
                    $regex = '#' . preg_quote($menu->url_path) . '(.)(?=\w)#i'; // Modified regular expression with a lookahead assertion and a delimiter
                    $reg = preg_match($regex, Request::url(), $matches);
                    if ($matches[1] == '/' || $matches[1] == '?') {
                        $isActiveLink = 'active';
                    }
                    ?>
                    <li data-id='{{ $menu->id }}'
                        class='{{ !empty($menu->children) ? 'treeview' : '' }} {{ $isActiveLink }}'>
                        <a href='{{ $menu->is_broken ? "javascript:alert('" . cbLang('controller_route_404') . "')" : $menu->url }}'
                            class='{{ $menu->color ? 'text-' . $menu->color : '' }}'>
                            <i class='{{ $menu->icon }} {{ $menu->color ? 'text-' . $menu->color : '' }}'></i>
                            <span>{{ cbLang($menu->name) }}</span>
                            @if (count($menu->children))
                                <i class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i>
                            @endif
                        </a>
                        {!! getMenuChildren($menu) !!}
                    </li>
                @endforeach

                @if (CRUDBooster::isSuperadmin())
                    <li class="header">{{ cbLang('SUPERADMIN') }}</li>
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-key'></i> <span>{{ cbLang('Privileges_Roles') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class='treeview-menu'>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/privileges/add*') ? 'active' : '' }}">
                                <a href='{{ Route('PrivilegesControllerGetAdd') }}'>{{ $current_path }}<i
                                        class='fa fa-plus'></i>
                                    <span>{{ cbLang('Add_New_Privilege') }}</span></a>
                            </li>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/privileges') ? 'active' : '' }}">
                                <a href='{{ Route('PrivilegesControllerGetIndex') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('List_Privilege') }}</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-users'></i> <span>{{ cbLang('Users_Management') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class='treeview-menu'>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/users/add*') ? 'active' : '' }}">
                                <a href='{{ Route('AdminCmsUsersControllerGetAdd') }}'><i class='fa fa-plus'></i>
                                    <span>{{ cbLang('add_user') }}</span></a>
                            </li>
                            <li class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/users') ? 'active' : '' }}">
                                <a href='{{ Route('AdminCmsUsersControllerGetIndex') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('List_users') }}</span></a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/menu_management*') ? 'active' : '' }}">
                        <a href='{{ Route('MenusControllerGetIndex') }}'><i class='fa fa-bars'></i>
                            <span>{{ cbLang('Menu_Management') }}</span></a>
                    </li>
                    <li class="treeview">
                        <a href="#"><i class='fa fa-wrench'></i> <span>{{ cbLang('settings') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class="treeview-menu">
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/settings/add*') ? 'active' : '' }}">
                                <a href='{{ route('SettingsControllerGetAdd') }}'><i class='fa fa-plus'></i>
                                    <span>{{ cbLang('Add_New_Setting') }}</span></a>
                            </li>
                            <?php
                            $groupSetting = DB::table('cms_settings')->groupby('group_setting')->pluck('group_setting');
                            foreach($groupSetting as $gs):
                            ?>
                            <li class="<?= $gs == Request::get('group') ? 'active' : '' ?>"><a
                                    href='{{ route('SettingsControllerGetShow') }}?group={{ urlencode($gs) }}&m=0'><i
                                        class='fa fa-wrench'></i>
                                    <span>{{ cbLang($gs) }}</span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-th'></i> <span>{{ cbLang('Module_Generator') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class='treeview-menu'>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/module_generator/step1') ? 'active' : '' }}">
                                <a href='{{ Route('ModulsControllerGetStep1') }}'><i class='fa fa-plus'></i>
                                    <span>{{ cbLang('Add_New_Module') }}</span></a>
                            </li>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/module_generator') ? 'active' : '' }}">
                                <a href='{{ Route('ModulsControllerGetIndex') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('List_Module') }}</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/module_status') ? 'active' : '' }}">
                        <a href='{{ Route('ModulsStatusControllerGetIndex') }}'><i class='fa fa-bars'></i>
                            <span>{{ cbLang('Module_Status') }}</span></a>
                    </li>
                    <li
                        class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/website_languages') ? 'active' : '' }}">
                        <a href='{{ CRUDBooster::adminPath('website_languages') }}'><i class='fa fa-language'></i>
                            <span>{{ cbLang('Website_Languages') }}</span></a>
                    </li>
                    <li class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/backup') ? 'active' : '' }}">
                        <a href='{{ CRUDBooster::adminPath('backup') }}'><i class='fa fa-database'></i>
                            <span>{{ cbLang('Backup_Restore_DB') }}</span></a>
                    </li>
                    <li class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/blocked_ips') ? 'active' : '' }}">
                        <a href='{{ Route('LoginAttemptsControllerGetIndex') }}'><i class='fa fa-ban'></i>
                            <span>{{ cbLang('Module_Blocked_IPS') }}</span></a>
                    </li>
                    <li class='treeview'>
                        <a href='#'><i class='fa fa-dashboard'></i>
                            <span>{{ cbLang('Statistic_Builder') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class='treeview-menu'>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/statistic_builder/add') ? 'active' : '' }}">
                                <a href='{{ Route('StatisticBuilderControllerGetAdd') }}'><i class='fa fa-plus'></i>
                                    <span>{{ cbLang('Add_New_Statistic') }}</span></a>
                            </li>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/statistic_builder') ? 'active' : '' }}">
                                <a href='{{ Route('StatisticBuilderControllerGetIndex') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('List_Statistic') }}</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-fire'></i> <span>{{ cbLang('API_Generator') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class='treeview-menu'>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/api_generator/generator*') ? 'active' : '' }}">
                                <a href='{{ Route('ApiCustomControllerGetGenerator') }}'><i class='fa fa-plus'></i>
                                    <span>{{ cbLang('Add_New_API') }}</span></a>
                            </li>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/api_generator') ? 'active' : '' }}">
                                <a href='{{ Route('ApiCustomControllerGetIndex') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('list_API') }}</span></a>
                            </li>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/api_generator/screet-key*') ? 'active' : '' }}">
                                <a href='{{ Route('ApiCustomControllerGetScreetKey') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('Generate_Screet_Key') }}</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class='treeview'>
                        <a href='#'><i class='fa fa-envelope-o'></i>
                            <span>{{ cbLang('Email_Templates') }}</span> <i
                                class="fa fa-angle-{{ cbLang('right') }} pull-{{ cbLang('right') }}"></i></a>
                        <ul class='treeview-menu'>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/email_templates/add*') ? 'active' : '' }}">
                                <a href='{{ Route('EmailTemplatesControllerGetAdd') }}'><i class='fa fa-plus'></i>
                                    <span>{{ cbLang('Add_New_Email') }}</span></a>
                            </li>
                            <li
                                class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/email_templates') ? 'active' : '' }}">
                                <a href='{{ Route('EmailTemplatesControllerGetIndex') }}'><i class='fa fa-bars'></i>
                                    <span>{{ cbLang('List_Email_Template') }}</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="{{ Request::is(config('crudbooster.ADMIN_PATH') . '/logs*') ? 'active' : '' }}"><a
                            href='{{ Route('LogsControllerGetIndex') }}'><i class='fa fa-flag'></i>
                            <span>{{ cbLang('Log_User_Access') }}</span></a></li>
                @endif

            </ul><!-- /.sidebar-menu -->

        </div>

    </section>
    <!-- /.sidebar -->
</aside>
@php
    function getMenuChildren($menu)
    {
        $results = '';
        $listOpen = false;
        if (!empty($menu->children)) {
            $results .= '<ul class="treeview-menu">';
            foreach ($menu->children as $key => $child) {
                $listClass = Request::is(
                    $child->url_path .= !Str::endsWith(Request::decodedPath(), $child->url_path) ? '/*' : '',
                )
                    ? 'active'
                    : '';
                $listClass .= count($child->children) ? 'inner-level-li' : '';
                $aClass = $child->color ? 'text-' . $child->color : '';
                $aHref = $child->is_broken ? "javascript:alert('" . cbLang('controller_route_404') . "')" : $child->url;
                $results .= "<li data-id='{$child->id}' class='$listClass' >";
                $results .= "<a href='$aHref' class='$aClass' >";
                $results .= "<i class='{$child->icon}'></i>";
                $results .= '<span>' . cbLang($child->name) . '</span>';
                if (count($child->children)) {
                    $results .= '<i class="fa fa-angle-' . cbLang('right') . ' pull-' . cbLang('right') . '"></i>';
                }
                $results .= '</a>';
                if (count($child->children)) {
                    $results .= getMenuChildren($child);
                }
                $results .= '</li>';
            }
            $results .= '</ul>';
        }
        return $results;
    }
@endphp
