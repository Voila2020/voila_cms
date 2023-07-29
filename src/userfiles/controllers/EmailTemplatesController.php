<?php

namespace App\Http\Controllers;

use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;


class EmailTemplatesController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function cbInit()
    {
        $this->table = "cms_email_templates";
        $this->primary_key = "id";
        $this->title_field = "name";
        $this->limit = 20;
        $this->orderby = ["id" => "desc"];
        $this->global_privilege = false;

        $this->button_table_action = true;
        $this->button_action_style = "button_icon";
        $this->button_add = true;
        $this->button_delete = true;
        $this->button_edit = true;
        $this->button_detail = true;
        $this->button_show = true;
        $this->button_filter = true;
        $this->button_export = false;
        $this->button_import = false;
        $this->sortable_table = false;

        $this->col = [];
        $this->col[] = ["label" => "Template Name", "name" => "name"];
        $this->col[] = ["label" => "Slug", "name" => "slug"];

        $this->form = [];
        $this->form[] = [
            "label" => "Template Name", "name" => "name", "type" => "text", "required" => true, "validation" => "required|min:3|max:255|alpha_spaces", "placeholder" => "You can only enter the letter only",
        ];
        $this->form[] = ["label" => "Slug", "type" => "text", "name" => "slug", "required" => true, 'validation' => 'required|unique:cms_email_templates,slug'];
        $this->form[] = ["label" => "Subject", "name" => "subject", "type" => "text", "required" => true, "validation" => "required|min:3|max:255"];
        // $this->form[] = ["label" => "Content", "name" => "content", "type" => "wysiwyg", "required" => true, "validation" => "required"];
        // $this->form[] = ["label" => "Template", "name" => "template", "type" => "hidden", "required" => false, "validation" => "", 'placeholder' => '',];
        $this->form[] = ["label" => "Description", "name" => "description", "type" => "text", "required" => true, "validation" => "required|min:3|max:255"];

        $this->form[] = ["label" => "From Name", "name" => "from_name", "type" => "text", "required" => false, "width" => "col-sm-6", 'placeholder' => 'Optional'];
        $this->form[] = ["label" => "From Email", "name" => "from_email", "type" => "email", "required" => false, "validation" => "email", "width" => "col-sm-6", 'placeholder' => 'Optional'];

        $this->form[] = ["label" => "Cc Email", "name" => "cc_email", "type" => "email", "required" => false, "validation" => "email", 'placeholder' => 'Optional'];
        $this->form[] = ['label' => 'Is Important', 'name' => 'priority', 'type' => 'radio', "required" => true, 'dataenum' => '1|Yes;3|No'];
        # Actions
        $this->addaction = [];
        $this->addaction[] = ['label' => 'Build', 'title' => 'Build', 'target' => '_blank', 'url' => CRUDBooster::mainpath('email-builder') . '/[id]?lang=en', 'icon' => 'fa fa-wrench'];
        // $this->addaction[] = ['label' => 'Build Arabic', 'title' => 'Build', 'target' => '_blank', 'url' => CRUDBooster::mainpath('email-builder') . '/[id]?lang=ar', 'icon' => 'fa fa-wrench'];
    }
    //By the way, you can still create your own method in here... :)

    public function getEmailBuilder(Request $request, $id)
    {
        $email_template = DB::table('cms_email_templates')->where('id', $id)->first();
        $button_lang = Request::input('lang');
        return view('crudbooster::email_builder.templates_builder', compact("id", "email_template", "button_lang"));
    }


    public function getEmailBuilderContent(Request $request, $id)
    {

        if (Request::input('lang') == "en") {
            $email_template = DB::table('cms_email_templates')->find($id);
            if (!$email_template->content && $id) {
                $email_template = DB::table('cms_email_templates')->find($id);

                return response()->json([
                    "gjs-html" => ($email_template->content === (null)) ? '<body><table id="idvv"><tbody><tr><td id="ithb"></td></tr></tbody></table></body>' : $email_template->content,
                    "gjs-styles" => ($email_template->css === (null)) ? '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}#idvv{height:550px;margin:0 auto 10px auto;padding:5px 5px 5px 5px;width:150%;max-width:550px;}#ithb{padding:0;margin:0;vertical-align:top;}' : $email_template->css,
                    "gjs-components" => ($email_template->template === (null)) ? '[{"type":"table","droppable":["tbody","thead","tfoot"],"attributes":{"id":"idvv"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"components":[{"type":"cell","draggable":["tr"],"attributes":{"id":"ithb"}}]}]}]}]' : $email_template->template,

                ]);
            }

            return response()->json([
                "gjs-html" => ($email_template->content === (null)) ? '<body><table id="idvv"><tbody><tr><td id="ithb"></td></tr></tbody></table></body>' : $email_template->content,
                "gjs-styles" => ($email_template->css === (null)) ? '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}#idvv{height:550px;margin:0 auto 10px auto;padding:5px 5px 5px 5px;width:150%;max-width:550px;}#ithb{padding:0;margin:0;vertical-align:top;}' : $email_template->css,
                "gjs-components" => ($email_template->template === (null)) ? '[{"type":"table","droppable":["tbody","thead","tfoot"],"attributes":{"id":"idvv"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"components":[{"type":"cell","draggable":["tr"],"attributes":{"id":"ithb"}}]}]}]}]' : $email_template->template,

            ]);
        }

        if (Request::input('lang') == "ar") {
            $email_template = DB::table('cms_email_templates')->find($id);
            if (!$email_template->content_ar && $id) {
                $email_template = DB::table('cms_email_templates')->find($id);

                return response()->json([
                    "gjs-html" => ($email_template->content_ar === (null)) ? '<body><table id="idvv"><tbody><tr><td id="ithb"></td></tr></tbody></table></body>' : $email_template->content_ar,
                    "gjs-styles" => ($email_template->css_ar === (null)) ? '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}#idvv{height:550px;margin:0 auto 10px auto;padding:5px 5px 5px 5px;width:150%;max-width:550px;}#ithb{padding:0;margin:0;vertical-align:top;}' : $email_template->css_ar,
                    "gjs-components" => ($email_template->template_ar === (null)) ? '[{"type":"table","droppable":["tbody","thead","tfoot"],"attributes":{"id":"idvv"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"components":[{"type":"cell","draggable":["tr"],"attributes":{"id":"ithb"}}]}]}]}]' : $email_template->template_ar,

                ]);
            }

            return response()->json([
                "gjs-html" => ($email_template->content_ar === (null)) ? '<body><table id="idvv"><tbody><tr><td id="ithb"></td></tr></tbody></table></body>' : $email_template->content_ar,
                "gjs-styles" => ($email_template->css_ar === (null)) ? '* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}#idvv{height:550px;margin:0 auto 10px auto;padding:5px 5px 5px 5px;width:150%;max-width:550px;}#ithb{padding:0;margin:0;vertical-align:top;}' : $email_template->css_ar,
                "gjs-components" => ($email_template->template_ar === (null)) ? '[{"type":"table","droppable":["tbody","thead","tfoot"],"attributes":{"id":"idvv"},"components":[{"type":"tbody","draggable":["table"],"droppable":["tr"],"components":[{"type":"row","draggable":["thead","tbody","tfoot"],"droppable":["th","td"],"components":[{"type":"cell","draggable":["tr"],"attributes":{"id":"ithb"}}]}]}]}]' : $email_template->template_ar,

            ]);
        }
    }


    public function postSaveTemplate(Request $request, $id)
    {

        if (Request::input('lang') == "en") {

            DB::table('cms_email_templates')
                ->where('id', $id)
                ->update([
                    'content' => Request::input('content'),
                    'html' => Request::input('html'),
                    'template' => Request::input('components'),
                    'css' => Request::input('css'),
                ]);
        }

        if (Request::input('lang') == "ar") {
            DB::table('cms_email_templates')
                ->where('id', $id)
                ->update([
                    'html_ar' => Request::input('html'),
                    'content_ar' => Request::input('content'),
                    'template_ar' => Request::input('components'),
                    'css_ar' => Request::input('css'),
                ]);
        }
    }
}
