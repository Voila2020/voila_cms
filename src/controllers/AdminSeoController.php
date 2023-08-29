<?php

namespace crocodicstudio\crudbooster\controllers;

use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AdminSeoController extends \crocodicstudio\crudbooster\controllers\CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "name";
        $this->limit = "20";
        $this->orderby = "sorting,asc";
        $this->global_privilege = true;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_dropdown";
        $this->button_add = true;
        $this->button_edit = true;
        $this->button_delete = true;
        $this->button_detail = true;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = false;
        $this->button_export = false;
        $this->table = "cms_seo";

        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
        //$this->form[] = ['label'=>'Send To','name'=>'send_to','type'=>'email','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
        //$this->form[] = ['label'=>'Row Type','name'=>'row_type','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
        //$this->form[] = ['label'=>'Response','name'=>'response','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
        # OLD END FORM

        $this->sub_module = array();

        $this->addaction = array();

        $this->button_selected = array();

        $this->alert = array();

        $this->index_button = array();

        $this->table_row_color = array();

        $this->table_row_color[] = ["condition" => "[active]==1", "color" => "success"];
        $this->table_row_color[] = ["condition" => "[active]==0", "color" => "danger"];

        $this->index_statistic = array();

        $this->script_js = "";

        $this->pre_index_html = null;

        $this->post_index_html = null;

        $this->load_js = array();

        $this->style_css = null;

        $this->load_css = array();

        $this->addaction[] = ['label' => 'Show', 'title' => 'Show', 'url' => CRUDBooster::mainpath("show-form/[id]"), 'icon' => 'fa fa-web', 'color' => 'success', 'showIf' => "true"];

        $this->addaction[] = ['label' => 'applications', 'title' => 'applications', 'url' => CRUDBooster::mainpath("form-applications/[id]"), 'icon' => 'fa fa-web', 'color' => 'info', 'showIf' => "true"];
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for button selected
    | ----------------------------------------------------------------------
    | @id_selected = the id selected
    | @button_name = the name of button
    |
     */
    public function actionButtonSelected($id_selected, $button_name)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate query of index result
    | ----------------------------------------------------------------------
    | @query = current sql query
    |
     */
    public function hook_query_index(&$query)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate row of index table html
    | ----------------------------------------------------------------------
    |
     */
    public function hook_row_index($column_index, &$column_value)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
     */
    public function hook_before_add(&$postdata)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @id = last insert id
    |
     */
    public function hook_after_add($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @postdata = input post data
    | @id       = current id
    |
     */
    public function hook_before_edit(&$postdata, $id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
     */
    public function hook_after_edit($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
     */
    public function hook_before_delete($id)
    {
        //Your code here

    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
     */
    public function hook_after_delete($id)
    {
        //Your code here

    }

    //By the way, you can still create your own method in here... :)
    public function getIndex()
    {
        $page = Request::input('page') ?: 'home';
        $page_id = Request::input('page_id') ?: null;
        $conditions = array();
        $languages = DB::table('languages')->get();
        if ($page_id != null) {
            array_push($conditions, ['page', '=', $page]);
            array_push($conditions, ['page_id', '=', $page_id]);
            $data = DB::table('cms_seo')->where($conditions)->get()->toArray();
        } else {
            array_push($conditions, ['page', '=', $page]);
            array_push($conditions, ['page_id', '=', null]);
            $data = DB::table('cms_seo')->where($conditions)->get()->toArray();
        }
        $keys = array_keys($data);
        foreach ($keys as $key) {
            $newKey = $data[$key]->language;
            $data[$newKey] = $data[$key];
            unset($data[$key]);
        }
        return view('crudbooster::seo', array('data' => $data, 'type' => $page, 'id' => $page_id, 'languages' => $languages));
    }

    public function postSeoStore(Request $request)
    {
        $data = Request::all();
        $languages = DB::table('languages')->get();

        foreach ($languages as $lang) {
            $conditions = array();
            array_push($conditions, ['page', '=', Request::input('page')]);
            array_push($conditions, ['page_id', '=', Request::input('page_id') ?: null]);
            array_push($conditions, ['language', '=', $lang->code]);
            $oldSEO = DB::table('cms_seo')->where($conditions)->first();

            if ($oldSEO) {
                DB::table('cms_seo')->where($conditions)->update([
                    'title' => $data['title_' . $lang->code],
                    'description' => $data['description_' . $lang->code],
                    'keywords' => $data['keywords_' . $lang->code],
                    'author' => $data['author_' . $lang->code],
                ]);
            } else {
                DB::table('cms_seo')->insert([
                    'title' => $data['title_' . $lang->code],
                    'description' => $data['description_' . $lang->code],
                    'keywords' => $data['keywords_' . $lang->code],
                    'author' => $data['author_' . $lang->code],
                    'page_id' => (Request::input('page_id')) ? Request::input('page_id') : null,
                    'page' => Request::input('page'),
                    'language' => $lang->code,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'active' => 1,
                ]);
            }
        }
        if(Request::input("back_url"))
            return redirect(Request::input("back_url"))->with(['message' => cbLang("alert_update_seo_success"), 'message_type' => 'success']);
        $module = DB::table('cms_moduls')->where('path', Request::input('page'))->first();
        if ($module) {
            return redirect(CRUDBooster::adminPath(Request::input('page')))->with(['message' => cbLang("alert_update_seo_success"), 'message_type' => 'success']);
        }

        return CRUDBooster::redirectBack(cbLang("alert_update_seo_success"), 'success');

    }
}
