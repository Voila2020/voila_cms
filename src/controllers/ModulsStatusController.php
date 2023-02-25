<?php

namespace crocodicstudio\crudbooster\controllers;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Excel;
use Illuminate\Support\Facades\PDF;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use crocodicstudio\crudbooster\fonts\Fontawesome;


class ModulsStatusController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function cbInit()
    {
        $this->table = 'cms_moduls';
        $this->primary_key = 'id';
        $this->title_field = "name";
        $this->limit = 100;
        $this->button_add = false;
        $this->button_export = false;
        $this->button_import = false;
        $this->button_filter = false;
        $this->button_detail = false;
        $this->button_bulk_action = true;
        $this->button_table_action = false;
        $this->orderby = ['is_protected' => 'asc', 'name' => 'asc'];

        $this->col = [];
        $this->col[] = ["label" => "ID", "name" => "id"];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = ["label" => "Table", "name" => "table_name"];
        $this->col[] = ["label" => "Path", "name" => "path"];
        $this->col[] = ["label" => "Protected", "name" => "is_protected", "visible" => (CRUDBooster::isSuperAdmin() ? true : false), "switch" => true];
    }

    function hook_query_index(&$query)
    {
        if (!CRUDBooster::isSuperadmin())
            $query->where('is_protected', 0);
        $query->whereNotIn('cms_moduls.controller', ['AdminCmsUsersController', 'ModulsStatusController']);
    }

    function hook_before_delete($id)
    {
        $modul = DB::table('cms_moduls')->where('id', $id)->first();
        $menus = DB::table('cms_menus')->where('path', 'like', '%' . $modul->controller . '%')->delete();
        @unlink(app_path('Http/Controllers/' . $modul->controller . '.php'));
    }
}
