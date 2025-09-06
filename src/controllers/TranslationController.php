<?php

namespace crocodicstudio\crudbooster\controllers;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TranslationController extends \crocodicstudio\crudbooster\controllers\CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table = "site_labels";
        $this->translation_table = "site_label_translations";
        $this->title_field = "label_key";
        $this->limit = "20";
        $this->orderby = "sorting,asc";
        $this->sortable_table = false;
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_icon";
        $this->record_seo = false;
        $this->button_add = true;
        $this->button_edit = true;
        $this->button_delete = true;
        $this->button_detail = true;
        $this->pdf_direction = "ltr";
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = false;
        $this->button_export = false;
        $this->page_seo = false;
        $this->form_using_ai_actions = true;
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        // $this->col[] = ["label" => "Title", "name" => "title", "translation" => true];
        $this->col[] = ["label" => "Label Key", "name" => "label_key", "translation" => false];
        $this->col[] = ["label" => "Label Value", "name" => "label_value", "translation" => true];
        //$this->col[] = ["label" => "Active", "name" => "active",'switch'=>true];
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Label Key', 'name' => 'label_key', 'type' => 'slug', 'validation' => 'required', 'translation' => false, 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Label Value', 'name' => 'label_value', 'type' => 'textarea', 'validation' => 'required', 'translation' => true, 'width' => 'col-sm-10'];
        # END FORM DO NOT REMOVE THIS LINE

        /*
           | ----------------------------------------------------------------------
           | Sub Module
           | ----------------------------------------------------------------------
           | @label          = Label of action
           | @path           = Path of sub module
           | @foreign_key 	  = foreign key of sub table/module
           | @button_color   = Bootstrap Class (primary,success,warning,danger)
           | @button_icon    = Font Awesome Class
           | @parent_columns = Sparate with comma, e.g : name,created_at
           |
           */
        $this->sub_module = array();


        /*
            | ----------------------------------------------------------------------
            | Add More Action Button / Menu
            | ----------------------------------------------------------------------
            | @label       = Label of action
            | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
            | @icon        = Font awesome class icon. e.g : fa fa-bars
            | @color 	   = Default is primary. (primary, warning, succecss, info)
            | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
            |
            */
        $this->addaction = array();


        /*
            | ----------------------------------------------------------------------
            | Add More Button Selected
            | ----------------------------------------------------------------------
            | @label       = Label of action
            | @icon 	   = Icon from fontawesome
            | @name 	   = Name of button
            | Then about the action, you should code at actionButtonSelected method
            |
            */
        $this->button_selected = array();


        /*
            | ----------------------------------------------------------------------
            | Add alert message to this module at overheader
            | ----------------------------------------------------------------------
            | @message = Text of message
            | @type    = warning,success,danger,info
            |
            */
        $this->alert = array();



        /*
            | ----------------------------------------------------------------------
            | Add more button to header button
            | ----------------------------------------------------------------------
            | @label = Name of button
            | @url   = URL Target
            | @icon  = Icon from Awesome.
            |
            */
        $this->index_button = array();
        /* using this button to load your site labels from json files */
        // $this->index_button[] = [
        //     'label' => 'load Labels From Json Files',
        //     'url'=>CRUDBooster::adminPath('languages/labels-from-json'),
        //     'icon'=>'fa fa-download'
        // ];


        /*
            | ----------------------------------------------------------------------
            | Customize Table Row Color
            | ----------------------------------------------------------------------
            | @condition = If condition. You may use field alias. E.g : [id] == 1
            | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
            |
            */
        $this->table_row_color = array();


        /*
            | ----------------------------------------------------------------------
            | You may use this bellow array to add statistic at dashboard
            | ----------------------------------------------------------------------
            | @label, @count, @icon, @color
            |
            */
        $this->index_statistic = array();



        /*
            | ----------------------------------------------------------------------
            | Add javascript at body
            | ----------------------------------------------------------------------
            | javascript code in the variable
            | $this->script_js = "function() { ... }";
            |
            */
        $this->script_js = NULL;


        /*
            | ----------------------------------------------------------------------
            | Include HTML Code before index table
            | ----------------------------------------------------------------------
            | html code to display it before index table
            | $this->pre_index_html = "<p>test</p>";
            |
            */
        $this->pre_index_html = null;



        /*
            | ----------------------------------------------------------------------
            | Include HTML Code after index table
            | ----------------------------------------------------------------------
            | html code to display it after index table
            | $this->post_index_html = "<p>test</p>";
            |
            */
        $this->post_index_html = null;



        /*
            | ----------------------------------------------------------------------
            | Include Javascript File
            | ----------------------------------------------------------------------
            | URL of your javascript each array
            | $this->load_js[] = asset("myfile.js");
            |
            */
        $this->load_js = array();



        /*
            | ----------------------------------------------------------------------
            | Add css style at body
            | ----------------------------------------------------------------------
            | css code in the variable
            | $this->style_css = ".style{....}";
            |
            */
        $this->style_css = NULL;



        /*
            | ----------------------------------------------------------------------
            | Include css File
            | ----------------------------------------------------------------------
            | URL of your css each array
            | $this->load_css[] = asset("myfile.css");
            |
            */
        $this->load_css = array();


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

        //update site labels files (resources/lang/*.json) 
        $this->updateSiteLabelsJSONFiles();

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
        | Hook for manipulate data input before update page is open
        | ----------------------------------------------------------------------
        | @row = model object
        | @id  = current id
        |
        */
    public function hook_before_get_edit($id, &$row)
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

        //update site labels files (resources/lang/*.json) 
        $this->updateSiteLabelsJSONFiles();

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
        //update site labels files (resources/lang/*.json) 
        $this->updateSiteLabelsJSONFiles();
    }



    //By the way, you can still create your own method in here... :)

    private function openJSONFile($code)
    {
        $jsonString = [];
        if (File::exists(base_path('resources/lang/' . $code . '.json'))) {
            $jsonString = file_get_contents(base_path('resources/lang/' . $code . '.json'));
            $jsonString = json_decode($jsonString, true);
        }
        return $jsonString;
    }

    private function saveJSONFile($code, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
    }


    public function getLabelsFromJson()
    {

        $languages = DB::table('languages')->where('active', 1)->get();
        $labels_values = [];

        if ($languages->count() > 0) {
            foreach ($languages as $key => $language) {
                $labels_values[$language->code] = $this->openJSONFile($language->code);
            }
        }
        $first_language = $languages->first();

        foreach ($labels_values[$first_language->code] as $key => $val) {

            //check key exist 
            $exist = DB::table('site_labels')->where('label_key', '=', $key)->first();
            //insert key in labels table if not exist
            if (!$exist) {
                $inserted_id = DB::table('site_labels')->insertGetId([
                    'label_key' => $key
                ]);

                foreach ($languages as $lang) {
                    DB::table('site_label_translations')->insert([
                        'label_value' => $labels_values["$lang->code"]["$key"] ?? '',
                        'locale' => "$lang->code",
                        'site_label_id' => $inserted_id
                    ]);
                }

            }

        }

        return response()->json(['success' => 'Done!']);
    }


    public function updateSiteLabelsJSONFiles()
    {

        $languages = DB::table('languages')->where('active', 1)->get();
        if ($languages->count() > 0) {
            foreach ($languages as $lang) {
                $data = DB::table('site_labels')
                    ->join('site_label_translations', 'site_labels.id', '=', 'site_label_translations.site_label_id')
                    ->where('site_label_translations.locale', $lang->code)
                    ->where('site_labels.active', 1)
                    ->pluck('site_label_translations.label_value', 'site_labels.label_key')
                    ->toArray();
                $this->saveJSONFile($lang->code, $data);
            }
        }

        return response()->json(['success' => 'Done!']);
    }


}
