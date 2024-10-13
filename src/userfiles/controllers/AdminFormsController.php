<?php

namespace App\Http\Controllers;

use App\Rules\ReCaptcha;
use Carbon\Carbon;
use crocodicstudio\crudbooster\controllers\CBController;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadeRequest;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use crocodicstudio\crudbooster\export\DefaultExportXls;
use Maatwebsite\Excel\Facades\Excel;

class AdminFormsController extends CBController
{

    public function cbInit()
    {
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "name";
        $this->limit = "20";
        $this->orderby = "sorting,asc";
        $this->global_privilege = false;
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
        $this->table = "forms";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = ["label" => "Send To", "name" => "send_to"];
        $this->col[] = ["label" => "Response", "name" => "response"];
        $this->col[] = ["label" => "active", "name" => "active", "switch" => true];

        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|max:70', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
        $this->form[] = ['label' => 'Send To', 'name' => 'send_to', 'type' => 'email', 'validation' => 'required|min:1|max:255', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Row Type', 'name' => 'row_type', 'type' => 'select', 'width' => 'col-sm-10', 'dataenum' => 'col-lg-12|col 1 ;col-lg-6|col 2;col-lg-9|col 3'];
        $this->form[] = ['label' => 'Response', 'name' => 'response', 'type' => 'textarea', 'validation' => 'required|string|min:5|max:5000', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Active', 'name' => 'active', 'type' => 'switch', 'width' => 'col-sm-9'];

        $columns[] = ['label' => 'Fileds', 'name' => 'field_id', 'type' => 'select', 'datatable' => 'fields,title'];
        $columns[] = ['label' => 'Label Name', 'name' => 'label_name', 'type' => 'text', 'required' => true];
        $columns[] = ['label' => 'Unique Name', 'name' => 'label_filed', 'type' => 'text', 'required' => true];
        $columns[] = ['label' => 'required', 'name' => 'required_filed', "type" => "switch", 'required' => true];
        $columns[] = ['label' => 'Unique', 'name' => 'unique_field', "type" => "switch", 'required' => true];
        // $columns[] = ['label' => 'values', 'name' => 'values', 'formula' => "[values]", "type" => "text"];
        $columns[] = ['label' => 'values', 'name' => 'values', "type" => "multitext"];
        $this->form[] = ['label' => 'Fileds', 'name' => 'form_field', 'type' => 'child', 'columns' => $columns, 'table' => 'form_field', 'foreign_key' => 'form_id'];

        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label'=>'Name','name'=>'name','type'=>'text','validation'=>'required|string|max:70','width'=>'col-sm-10','placeholder'=>'You can only enter the letter only'];
        //$this->form[] = ['label'=>'Send To','name'=>'send_to','type'=>'email','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
        //$this->form[] = ['label'=>'Row Type','name'=>'row_type','type'=>'text','validation'=>'required|min:1|max:255','width'=>'col-sm-10'];
        //$this->form[] = ['label'=>'Response','name'=>'response','type'=>'textarea','validation'=>'required|string|min:5|max:5000','width'=>'col-sm-10'];
        # OLD END FORM

        /*
        | ----------------------------------------------------------------------
        | Sub Module
        | ----------------------------------------------------------------------
        | @label          = Label of action
        | @path           = Path of sub module
        | @foreign_key       = foreign key of sub table/module
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
        | @color        = Default is primary. (primary, warning, succecss, info)
        | @showIf        = If condition when action show. Use field alias. e.g : [id] == 1
        |
         */
        $this->addaction = array();

        /*
        | ----------------------------------------------------------------------
        | Add More Button Selected
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @icon        = Icon from fontawesome
        | @name        = Name of button
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
        | FESAL VOILA DONT REMOVE THIS LINE
        | ----------------------------------------------------------------------
        | IF NOT SUCCESS ADD  $this->col[] = ["label"=>"active","name"=>"active"]; IN COLUMNS
        |
         */

        $this->table_row_color[] = ["condition" => "[active]==1", "color" => "success"];
        $this->table_row_color[] = ["condition" => "[active]==0", "color" => "danger"];

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
        $this->script_js = "";

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
        $this->style_css = null;

        /*
        | ----------------------------------------------------------------------
        | Include css File
        | ----------------------------------------------------------------------
        | URL of your css each array
        | $this->load_css[] = asset("myfile.css");
        |
         */
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
    public function getFormHtml(Request $request)
    {
        $id = $request->id;
        if (!CRUDBooster::myId()) {
            return redirect(config('crudbooster.ADMIN_PATH') . '/login');
        }

        $form = DB::table('forms')->find($id);
        $element_form = "";
        if ($form) {
            $element_form .= "<form method='POST' action='" . url('submit-form/' . $form->id) . "' enctype='multipart/form-data' class=' well' style='background:#FFF' >";
            $element_form .= csrf_field();
            $fields = DB::table('form_field')->select(
                'form_field.*',
                'fields.title',
                'forms.*'
            )
                ->join('fields', 'form_field.field_id', '=', 'fields.id')
                ->join('forms', 'form_field.form_id', '=', 'forms.id')
                ->where('form_field.form_id', $form->id)->orderBy('form_field.sorting', 'asc')->get();

            if ($fields) {
                foreach ($fields as $item) {
                    $req = ($item->required_filed == 'Yes') ? "required" : "";
                    $element_form .= "<div class='form-group'>";
                    // $element_form .= "<label>" . $item->label_filed . ":</label>";
                    if ($item->title == 'email' || $item->title == 'text') {
                        $element_form .= "<input type='" . $item->title . "' class='form-control' placeholder='" . $item->label_name . "' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    } else if ($item->title == 'checkbox') {
                        $array_values = explode('|', $item->values);
                        foreach ($array_values as $filed) {
                            $element_form .= "<label><input type='" . $item->title . "'  value='" . $filed . "' name='" . $this->stripSpace($item->label_filed) . "[]' " . $req . " />" . $filed . "</label><br>";
                        }
                    } else if ($item->title == 'radio') {
                        $array_values = explode('|', $item->values);
                        foreach ($array_values as $filed) {
                            $element_form .= "<label><input type='" . $item->title . "'  value='" . $filed . "' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />" . $filed . "</label><br>";
                        }
                    } else if ($item->title == 'select') {
                        $array_values = explode('|', $item->values);
                        $element_form .= "<select name='" . $this->stripSpace($item->label_filed) . "' class='form-control' >";

                        foreach ($array_values as $filed) {
                            $element_form .= "<option value='" . $filed . "'>" . $filed . "</option>";
                        }
                        $element_form .= "</select>";
                    } else if ($item->title == 'number') {
                        $element_form .= "<input type='" . $item->title . "' class='form-control' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    } else if ($item->title == 'file') {
                        $element_form .= "<input type='" . $item->title . "' class='form-control' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    }

                    $element_form .= "</div>";
                }
                $recaptcha_secret_key = CRUDBooster::getSetting('recaptcha_secret_key');

                if ($recaptcha_secret_key) {
                    $element_form .= "
                <input id='g-recaptcha-response' type='hidden' name='g-recaptcha-response'
                    data-sitekey='{{ $recaptcha_secret_key }}' />";
                }
                $element_form .= "<div class='form-group'>";
                $element_form .= "<input type='hidden' name='landing_page_id' value='" . optional($request)->landing_page_id . "' />";
                $element_form .= "<input type='submit' class='btn btn-primary' value='SEND' />";
                $element_form .= "</div>";

                $element_form .= "</form>";
            }
            return $element_form;
            // return view('form', array('data' => $element_form));
        }
    }

    public function getAllForms(Request $request)
    {
        $forms = DB::table('forms')->get();
        foreach ($forms as $form) {
            $request->id = $form->id;
            $form->html = $this->getFormHtml($request);
        }
        return response()->json($forms);
    }

    private function stripSpace($string)
    {
        return $string = str_replace(' ', '', trim($string));
    }

    public function getFormApplications($id, Request $request)
    {
        $applications = DB::table('applications')->where('form_id', $id)->get();
        $applicationData = collect([]);

        //---------------------------------------------------//

        foreach ($applications as $application) {
            $fields = DB::table("applications_fields")
                ->join("form_field", "applications_fields.field_id", "=", "form_field.id")
                ->where("applications_fields.application_id", $application->id)
                ->get();

            // get all fields with values
            $applicationFields = $fields->map(function ($field) {
                return [
                    'label_name' => $field->label_name,
                    'value' => $field->value,
                ];
            });

            $applicationData->push([
                'created_at' => $application->created_at,
                'ip' => $application->ip,
                'fields' => $applicationFields,
            ]);
        }


        //---------------------------------------------------//
        $result = [
            "items" => $applicationData->map(function ($application) {
                $item = [
                    "created_at" => $application['created_at'],
                    "ip" => $application['ip'],
                ];
                //added fields to values to result
                foreach ($application['fields'] as $field) {
                    $item[$field['label_name']] = $field['value'];
                }

                return $item;
            })->all(),
        ];

        $result = json_decode(json_encode($result));

        //---------------------------------------------------//

        $columns = Schema::getColumnListing("applications");
        $columns = array_reverse($columns);

        $formattedColumns = [];
        //added fields to columns
        foreach ($applicationData as $application) {
            foreach ($application['fields'] as $field) {
                $formattedColumns[] = [
                    "label" => $field['label_name'],
                    "name" => $field['label_name'],
                    "field" => $field['label_name'],
                ];
            }
            break;
        }
        foreach ($columns as $col) {
            $formattedColumns[] = [
                "label" => $col,
                "name" => $col,
                "field" => $col,
            ];
        }

        $filteredColumns = array_filter($formattedColumns, function ($column) {
            return $column["label"] !== "response" && $column["label"] !== "updated_at" && $column["label"] !== "id" && $column["label"] !== "form_id" && $column["label"] !== "landing_page_id" && $column["label"] !== "active";
        });

        $filteredColumns = array_values($filteredColumns);
        //---------------------------------------------------//

        return view('crudbooster::form_builder.submits', array('data' => $applications, 'export_data_columns' => $filteredColumns, 'export_data_result' => $result));
    }

    public function getShowForm($id, Request $request)
    {

        if (!CRUDBooster::myId()) {
            return redirect(config('crudbooster.ADMIN_PATH') . '/login');
        }

        $form = DB::table('forms')->find($id);
        $elemnt_form = "";
        if ($form) {
            $elemnt_form .= "<form method='POST' action='" . CRUDBooster::mainpath('submit') . '/' . $form->id . "' enctype='multipart/form-data' class=' well' style='background:#FFF' >";
            $elemnt_form .= csrf_field();
            $fields = DB::table('form_field')->select(
                'form_field.*',
                'fields.title',
                'forms.*'
            )
                ->join('fields', 'form_field.field_id', '=', 'fields.id')
                ->join('forms', 'form_field.form_id', '=', 'forms.id')
                ->where('form_field.form_id', $form->id)->orderBy('form_field.sorting', 'asc')->get();
            // return  response()->json($fields, 200);

            if ($fields) {
                foreach ($fields as $item) {
                    $req = ($item->required_filed == 'Yes') ? "required" : "";
                    $elemnt_form .= "<div class='form-group'>";
                    $elemnt_form .= "<label>" . $item->label_name . ":</label>";
                    if ($item->title == 'email' || $item->title == 'text') {
                        $elemnt_form .= "<input type='" . $item->title . "' class='form-control' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    } else if ($item->title == 'checkbox') {
                        $array_values = explode('|', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form .= "<label><input type='" . $item->title . "'  value='" . $filed . "' name='" . $this->stripSpace($item->label_filed) . "[]' " . $req . " />" . $filed . "</label><br>";
                        }
                    } else if ($item->title == 'radio') {
                        $array_values = explode('|', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form .= "<label><input type='" . $item->title . "'  value='" . $filed . "' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />" . $filed . "</label><br>";
                        }
                    } else if ($item->title == 'select') {
                        $array_values = explode('|', $item->values);
                        $elemnt_form .= "<select name='" . $this->stripSpace($item->label_filed) . "' class='form-control' >";

                        foreach ($array_values as $filed) {
                            $elemnt_form .= "<option value='" . $filed . "'>" . $filed . "</option>";
                        }
                        $elemnt_form .= "</select>";
                    } else if ($item->title == 'number') {
                        $elemnt_form .= "<input type='" . $item->title . "' class='form-control' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    } else if ($item->title == 'file') {
                        $elemnt_form .= "<input type='" . $item->title . "' class='form-control' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    }

                    $elemnt_form .= "</div>";
                }
                $recaptcha_secret_key = CRUDBooster::getSetting('recaptcha_secret_key');
                if ($recaptcha_secret_key) {
                    $elemnt_form .= "
                <input id='g-recaptcha-response' type='hidden' name='g-recaptcha-response'
                    data-sitekey='{{ $recaptcha_secret_key }}' />";
                }

                $elemnt_form .= "<div class='form-group'>";

                $elemnt_form .= "<input type='submit' class='btn btn-primary' value='SEND' />";
                $elemnt_form .= "</div>";

                $elemnt_form .= "</form>";
            }
            return view('crudbooster::form_builder.form', array('data' => $elemnt_form));
        }
    }

    public function postSubmit(Request $request, $id)
    {
        $form = DB::table('forms')->find($id);
        $fields = DB::table('form_field')->select(
            'form_field.*',
            'fields.title',
            'fields.id as field_id'
        )
            ->join('fields', 'form_field.field_id', '=', 'fields.id')
            ->join('forms', 'form_field.form_id', '=', 'forms.id')
            ->where('form_field.form_id', $form->id)->orderBy('form_field.sorting', 'asc')->get();

        $validator = Validator::make($request->all(), [
            "g-recaptcha-response" => ['required', new ReCaptcha],
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $validations = [];

        foreach ($fields as $item) {
            $valid = ($item->required_filed == 'Yes') ? 'required' : null;
            if ($valid) {
                $validations[$this->stripSpace($item->label_filed)] = $valid;
            }
        }
        $request->validate($validations);

        //--------------------------------------//

        $uniqueFields = DB::table('form_field')->where('form_id', $id)->where('unique_field', 1)->get();
        foreach ($uniqueFields as $field) {

            $applicationField = DB::table('applications_fields')
                ->where("form_id", $id)
                ->where("field_id", $field->id)
                ->where("landing_page_id", $request->landing_page_id)
                ->where("value", $request->input($this->stripSpace($field->label_filed)))
                ->get()
                ->count();
            if ($applicationField) {
                $landingPage = DB::table('landing_pages')
                    ->where('id', $request->landing_page_id)->first();
                if ($landingPage->is_rtl) {
                    App::setlocale("ar");
                }
                return redirect()->back()->with("error", $field->label_filed . " " . __("already_exists"));
            }
        }

        //--------------------------------------//

        $applicationID = DB::table('applications')->insertGetId([
            'form_id' => $form->id,
            'ip' => request()->ip(),
            'landing_page_id' => $request->landing_page_id,
            'active' => 1,
            'updated_at' => Carbon::now(),
        ]);

        $submit = "<table class='table'><thead><tr>";
        foreach ($fields as $item) {
            $submit .= "<th>" . $item->label_filed . "</th>";
        }
        $submit .= "</tr></thead><body><tr>";
        foreach ($fields as $item) {
            if ($item->title == "file") {
                $key = $item->label_filed;
                $submit .= "<td><a target='_blank' href=\"" . config('app.url') . '/files/' . $form->name . '/' . $request->$key->getClientOriginalName() . "\">" . $request->$key->getClientOriginalName() . "</a></td>";
            } else if (is_array($request->input($this->stripSpace($item->label_filed)))) {
                $submit .= "<td>";
                foreach ($request->input($this->stripSpace($item->label_filed)) as $val) {
                    $submit .= $val . ",";
                }
                $submit .= "</td>";
            } else {
                $submit .= "<td>" . $request->input($this->stripSpace($item->label_filed)) . "</td>";
            }

            //-------------------------------------------//
            if ($item->title == "file") {
                $key = $item->label_filed;
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $fileName = $file->getClientOriginalName();
                    $file->move(public_path('files/' . $form->name), $fileName);
                }

                $applicationField = DB::table('applications_fields')->insert([
                    'application_id' => $applicationID,
                    'form_id' => $item->form_id,
                    'field_id' => $item->id,
                    'landing_page_id' => $request->landing_page_id,
                    'value' => 'files/' . $form->name . $fileName
                ]);
            } else {
                $applicationField = DB::table('applications_fields')->insert([
                    'application_id' => $applicationID,
                    'form_id' => $item->form_id,
                    'field_id' => $item->id,
                    'landing_page_id' => $request->landing_page_id,
                    'value' => $request->input($this->stripSpace($item->label_filed)),
                ]);
            }
            //-------------------------------------------//
        }

        $submit .= "</tr></tbody></table>";

        DB::table('applications')->where('id', $applicationID)->update([
            'response' => $submit,
        ]);

        if ($request->landing_page_id) {
            $landingPage = DB::table('landing_pages')->find($request->landing_page_id);
            try {
                CRUDBooster::sendEmail([
                    'to' => $landingPage->send_email_to,
                    'data' => [
                        'response' => $submit,
                    ],
                    'template' => 'admin-landing-page',
                    'attachments' => [],
                ]);
            } catch (Exception $e) {
                Log::log("error", "Log error $e");
            }

            try {
                CRUDBooster::sendEmail([
                    'to' => $request->email,
                    'data' => [
                        'response' => $landingPage->response,
                    ],
                    'template' => 'customer-landing-page',
                    'attachments' => [],
                ]);
            } catch (Exception $e) {
                Log::log("error", "Log error $e");
            }

            return redirect()->to("thankyou/" . $request->landing_page_id);
        }

        return back()->with('success', $form->response);
    }

    public function postExportData()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);

        $this->limit = facadeRequest::input('limit');
        $this->index_return = true;
        $filetype = facadeRequest::input('fileformat');
        $filename = facadeRequest::input('filename');
        $papersize = facadeRequest::input('page_size');
        $paperorientation = facadeRequest::input('page_orientation');
        $response = $this->getIndex();

        if (facadeRequest::input('export_data_columns')) {
            $columns = json_decode(facadeRequest::input('export_data_columns'), true);
            $result = json_decode(facadeRequest::input('export_data_result'));

            $response = [
                "columns" => $columns,
                "result" => $result->items,
            ];
        }

        if (facadeRequest::input('default_paper_size')) {
            DB::table('cms_settings')->where('name', 'default_paper_size')->update(['content' => $papersize]);
        }
        switch ($filetype) {
            case "pdf":
                $view = view('crudbooster::export', $response)->render();
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                $pdf->setPaper($papersize, $paperorientation);

                return $pdf->stream($filename . '.pdf');
                break;
            case 'xls':
                return Excel::download(new DefaultExportXls($response), $filename . ".xls");
                break;
            case 'csv':

                return Excel::download(new DefaultExportXls($response), $filename . ".csv");
                break;
        }
    }
}
