<?php

namespace App\Http\Controllers;

use crocodicstudio\crudbooster\controllers\CBController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminCmsUsersController extends CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table = 'cms_users';
        $this->primary_key = 'id';
        $this->title_field = "name";
        $this->button_action_style = 'button_icon';
        $this->button_import = false;
        $this->button_export = false;
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = array();
        $this->col[] = array("label" => "Name", "name" => "name");
        $this->col[] = array("label" => "Email", "name" => "email");
        $this->col[] = array("label" => "Privilege", "name" => "id_cms_privileges", "join" => "cms_privileges,name");
        $this->col[] = array("label" => "Photo", "name" => "photo", "image" => 1);
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = array();
        $this->form[] = array("label" => "Name", "name" => "name", 'required' => true, 'validation' => 'required|alpha_spaces|min:3');
        $this->form[] = array("label" => "Email", "name" => "email", 'required' => true, 'type' => 'email', 'validation' => 'required|email|unique:cms_users,email,' . CRUDBooster::getCurrentId());
        $this->form[] = array("label" => "Photo", "name" => "photo", "type" => "upload", "help" => "Recommended resolution is 200x200px", 'required' => true, 'validation' => 'required|image|max:1000', 'resize_width' => 90, 'resize_height' => 90);
        $this->form[] = array("label" => "Privilege", "name" => "id_cms_privileges", "type" => "select", "datatable" => "cms_privileges,name", 'required' => true);
        // $this->form[] = array("label"=>"Password","name"=>"password","type"=>"password","help"=>"Please leave empty if not change");
        $this->form[] = array("label" => "Password", "name" => "password", "type" => "password", "help" => "Please leave empty if not change");
        $this->form[] = array("label" => "Password Confirmation", "name" => "password_confirmation", "type" => "password", "help" => "Please leave empty if not change");
        # END FORM DO NOT REMOVE THIS LINE

    }

    public function getProfile()
    {
        $this->button_addmore = false;
        $this->button_cancel = false;
        $this->button_show = false;
        $this->button_add = false;
        $this->button_delete = false;
        $this->button_sortable = false;
        // $this->hide_form       = ['id_cms_privileges'];

        $data['page_title'] = cbLang("label_button_profile");
        $data['row'] = CRUDBooster::first('cms_users', CRUDBooster::myId());

        return $this->view('crudbooster::default.form', $data);
    }
    public function hook_before_edit(&$postdata, $id)
    {
        unset($postdata['password_confirmation']);
    }
    public function hook_after_edit($id)
    {
        $user = DB::table('cms_users')->where('id', $id)->first();
        Session::put('admin_photo', asset($user->photo));
    }
    public function hook_before_add(&$postdata)
    {
        unset($postdata['password_confirmation']);
    }
}
