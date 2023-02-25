<?php

namespace crocodicstudio\crudbooster\controllers;

use crocodicstudio\crudbooster\helpers\CRUDBooster;

class LoginAttemptsController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function cbInit()
    {
        $this->table = "login_attempts";
        $this->primary_key = "id";
        $this->limit = 20;
        $this->global_privilege = false;

        $this->button_table_action = false;
        $this->button_action_style = "button_icon";
        $this->button_add = false;
        $this->button_delete = false;
        $this->button_edit = false;
        $this->button_detail = false;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_export = false;
        $this->button_import = false;
        $this->button_sortable = false;
        $this->orderby = ["id" => "desc"];


        $this->col = [];
        $this->col[] = ["label" => "IP Adress", "name" => "ip_address"];
        $this->col[] = ["label" => "Blocked At", "name" => "blocked_at"];

        $this->form = [];
        $this->form[] = ["label" => "IP Adress", "name" => "ip_address", "type" => "text", "required" => true, "width" => "col-sm-6"];
        $this->form[] = ["label" => "Blocked At", "name" => "blocked_at", "type" => "datetime", "required" => true, "width" => "col-sm-6"];

        # Actions
        $this->addaction = [];
    }
    public function hook_before_add(&$arr)
    {
    }

    public function hook_before_edit(&$arr, $id)
    {
    }
}
