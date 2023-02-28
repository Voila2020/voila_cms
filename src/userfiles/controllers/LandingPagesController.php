<?php

namespace App\Http\Controllers;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use crocodicstudio\crudbooster\controllers\CBController;
use crocodicstudio\crudbooster\export\DefaultExportXls;
use crocodicstudio\crudbooster\export\LandingPageExport;
use Illuminate\Support\Facades\App;

class LandingPagesController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function cbInit()
    {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->title_field = "name";
        $this->limit = "20";
        $this->orderby = "id,desc";
        $this->global_privilege = false;
        $this->button_table_action = true;
        $this->button_bulk_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = true;
        $this->button_edit = true;
        $this->button_delete = true;
        $this->button_detail = true;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_import = false;
        $this->button_export = false;
        $this->table = "landing_pages";
        # END CONFIGURATION DO NOT REMOVE THIS LINE

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label" => "Name", "name" => "name"];
        $this->col[] = ["label" => "Title", "name" => "title"];
        $this->col[] = ["label" => "Description", "name" => "description"];
        $this->col[] = ["label" => "Is Template", "name" => "is_template"];
        $this->col[] = ["label" => "Url", "name" => "url"];
        $this->col[] = ["label" => "Send Email To", "name" => "send_email_to"];
        $this->col[] = ["label" => "Applications", "name" => "id", "callback" => function ($row) {
            return DB::table('applications')->where("landing_page_id", $row->id)->get()->count();
        }];

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
        $this->form[] = ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|min:3|max:300', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Title', 'name' => 'title', 'type' => 'text', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
        $this->form[] = ['label' => 'Description', 'name' => 'description', 'type' => 'text', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Is Template', 'name' => 'is_template', 'type' => 'radio', 'width' => 'col-sm-10', 'dataenum' => '1|Yes;0|No'];
        $this->form[] = ['label' => 'Url', 'name' => 'url', 'type' => 'text', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Send Email To', 'name' => 'send_email_to', 'type' => 'text', 'width' => 'col-sm-10', 'placeholder' => 'Please enter a valid URL'];
        $this->form[] = ['label' => 'Response Message', 'name' => 'response_message', 'type' => 'wysiwyg', 'width' => 'col-sm-10'];
        $this->form[] = ['label' => 'Active', 'name' => 'active', 'type' => 'radio', 'width' => 'col-sm-10', 'dataenum' => '1|Yes;0|No'];
        $this->form[] = ['label' => 'Is Rtl', 'name' => 'is_rtl', 'type' => 'radio', 'width' => 'col-sm-9', 'dataenum' => '1|Yes;0|No'];
        # END FORM DO NOT REMOVE THIS LINE

        # OLD START FORM
        //$this->form = [];
        //$this->form[] = ['label' => 'Name', 'name' => 'name', 'type' => 'text', 'validation' => 'required|string|min:3|max:300', 'width' => 'col-sm-10'];
        //$this->form[] = ['label' => 'Title', 'name' => 'title', 'type' => 'text', 'width' => 'col-sm-10', 'placeholder' => 'You can only enter the letter only'];
        //$this->form[] = ['label' => 'Description', 'name' => 'description', 'type' => 'text', 'width' => 'col-sm-10'];
        //$this->form[] = ['label' => 'Is Template', 'name' => 'is_template', 'type' => 'radio', 'width' => 'col-sm-10', 'dataenum' => '1|Yes;0|No'];
        //$this->form[] = ['label' => 'Url', 'name' => 'url', 'type' => 'text', 'width' => 'col-sm-10'];
        //$this->form[] = ['label' => 'Send Email To', 'name' => 'send_email_to', 'type' => 'text', 'width' => 'col-sm-10', 'placeholder' => 'Please enter a valid URL'];
        //$this->form[] = ['label' => 'Response Message', 'name' => 'response_message', 'type' => 'wysiwyg', 'width' => 'col-sm-10'];
        //$this->form[] = ['label' => 'Active', 'name' => 'active', 'type' => 'radio', 'width' => 'col-sm-10', 'dataenum' => '1|Yes;0|No'];
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
        $this->addaction[] = ['label' => 'Build', 'title' => 'Build', 'target' => '_blank', 'url' => CRUDBooster::mainpath('builder') . '/[id]', 'icon' => 'fa fa-wrench'];
        $templates = DB::table('landing_pages')->where("is_template", 1)->get()->count();
        if ($templates > 0)
            $this->addaction[] = ['label' => 'Build from Template', 'target' => '_blank', "color" => "primary", 'title' => 'Build from Template', "url" => CRUDBooster::mainpath('builder-template') . '/[id]', 'icon' => 'fa fa-wrench'];
        $this->addaction[] = ['label' => 'Applications', 'title' => 'Applications', 'url' => CRUDBooster::mainpath('applications') . '/[id]', "color" => "info"];
        $this->addaction[] = ['label' => '', 'title' => 'Go TO', 'target' => '_blank', 'url' => url('/') . '/[url]', 'icon' => 'fa fa-search', "color" => "success"];
        $this->addaction[] = ['label' => '', 'title' => 'SEO', 'url' => CRUDBooster::adminPath('seo/pages/[id]'), 'icon' => 'fa fa-globe', 'color' => 'warning', 'showIf' => "true"];
        $this->addaction[] = ['label' => 'Export Applications', 'target' => '_blank', 'title' => 'Export Applications', 'url' => CRUDBooster::mainpath('export-excel/[id]'), 'icon' => 'fa fa-excel', 'color' => 'success', 'showIf' => "true"];

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
        $this->script_js = null;
        $this->script_js = "
            $(function() {
                    $(document).on('click','.selectTemplates',function() {
                    $('#selectTemplatesModal').show();
                });
            });
        ";

        /*
        | ----------------------------------------------------------------------
        | Include HTML Code before index table
        | ----------------------------------------------------------------------
        | html code to display it before index table
        | $this->pre_index_html = "<p>test</p>";
        |
         */
        $this->pre_index_html = null;
        $templates = DB::table('landing_pages')->where("is_template", 1)->get();
        $html = "";
        foreach ($templates as $template) {
            $html .= `<option value="{$template->id}">{$template->name}</option>`;
        }
        $this->pre_index_html = `
        <!-- The modal Start -->
        <div class="modal fade" id="selectTemplatesModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="modalLabel">Select Template</h4>
                    </div>
                    <div class="modal-body">
                        <select name="templateId" id="templateId">{$html}</select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- The modal END -->

`;

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

    public function getBuilder($landingPageId)
    {
        $landingPage = DB::table('landing_pages')->find($landingPageId);
        return view('crudbooster::landing_page_builder.index', compact("landingPageId", "landingPage"));
    }

    public function getBuilderTemplate($landingPageId)
    {
        $landingPage = DB::table('landing_pages')->find($landingPageId);
        $templates = DB::table('landing_pages')->where("is_template", 1)->get();
        return view("crudbooster::landing_page_builder.templates", compact("landingPageId", "landingPage", "templates"));
    }

    public function postLandingPage(Request $request)
    {
        if (!$request->id) {
            return response()->json(["message" => "Error No Landing Page"], 500);
        }

        $landingPage = DB::table('landing_pages')->where('id', $request->id)
            ->update([
                'html' => $request["gjs-html"],
                'css' => $request["gjs-css"],
                'js' => $request["gjs-js"],
                'components' => $request["gjs-components"],
                'variables' => $request["variables"]
            ]);

        return response()->json();
    }

    public function getLandingPage(Request $request)
    {
        $landingPage = DB::table('landing_pages')->find($request->id);
        if (!$landingPage->html && $request->template_id) {
            $landingPage = DB::table('landing_pages')->find($request->template_id);
            return response()->json([
                "gjs-html" => $landingPage->html,
                "gjs-css" => $landingPage->css,
                "gjs-js" => $landingPage->js,
                "gjs-components" => $landingPage->components,
                "variables" => $landingPage->variables,
            ]);
        }
        return response()->json([
            "gjs-html" => $landingPage->html,
            "gjs-css" => $landingPage->css,
            "gjs-js" => $landingPage->js,
            "gjs-components" => $landingPage->components,
            "variables" => $landingPage->variables,
        ]);
    }

    public function getApplications($landingPageId)
    {
        $applications = DB::table('applications')->where('landing_page_id', $landingPageId)->get();
        return view('crudbooster::form_builder.submits', array('data' => $applications));
    }

    public function getExportExcel($id)
    {
        $landingPage = DB::table('landing_pages')->where('id', $id)->first();
        $applications = DB::table('applications')->where('landing_page_id', $id)->get();
        $columns = [];
        if ($applications) {
            $columns = DB::table('form_field')->where("form_id", $applications[0]->form_id)->get();
        }

        foreach ($applications as $application) {
            $application->fields = DB::table('applications_fields')->where("application_id", $application->id)->pluck("value", "field_id")->toArray();
        }
        return Excel::download(new LandingPageExport($applications, $columns), $landingPage->name . ".xls");
    }

    public function postSetTemplate(Request $request)
    {
        $templateLandingPage = DB::table('landing_pages')->find($request->templateId);
        $landingPage = DB::table('landing_pages')->find($request->landingPageId);
        if (!$templateLandingPage || !$landingPage) {
            return response()->json([], 500);
        }

        //--- HTML
        $html = $templateLandingPage->html;
        $html = str_replace('name="landing_page_id" value="' . $request->templateId . '"', 'name="landing_page_id" value="' . $request->landingPageId . '"', $html);
        //--------------------------------//
        $landingPage = DB::table('landing_pages')->where('id', $request->templateId)->update([
            'html' => $html,
            'css' => $templateLandingPage->css,
            'js' => $templateLandingPage->js,
            'variables' => $templateLandingPage->variables,
            'components' => $templateLandingPage->components,
            'is_rtl' => $templateLandingPage->is_rtl
        ]);
        return response()->json([], 200);
    }

    public function catchView($url)
    {
        $landingPage = DB::table('landing_pages')->where("url", $url)->first();
        if ($landingPage) {
            $landingPageSeo  = DB::table('cms_seo')->where("model", "pages")->where("model_id", $landingPage->id)->first();
            if (!$landingPageSeo) {
                $landingPageSeo  = DB::table('cms_seo')->where("model", "home")->first();
            }
            if ($landingPage->is_rtl) {
                App::setlocale("ar");
            }
            return response()->view("landing_page_builder.view", compact("landingPage", "landingPageSeo"));
        }
        abort(404);
    }
}
