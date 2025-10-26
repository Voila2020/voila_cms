<?php

namespace App\Traits;

use App\Models\Language\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Str;

trait ContentBuilderTrait
{
    //generate Content Builder Action Btns 
    public function generateContentBuilderActionBtns(&$addaction,$field,$url){  
        if(CRUDBooster::isUpdate()){
            $website_languages = Language::where('active',1)->get();
            foreach($website_languages as $lang){
                    $code = $lang->code;
                    $upperCode = strtoupper($code);
                    $langName = $lang->name;
                    $addaction[] = [
                        'label'  => "Edit Content ($upperCode)",
                        'title'  => "Edit Content ($langName)",
                        'target' => '_blank',
                        'url'    => CRUDBooster::mainpath('content-builder-iframe') . "/[id]?field=$field&lang=$code&url=$url",
                        'icon'   => 'fa fa-wrench'
                    ];
            }
        }
    }
    //remove body tag from content builder html before store into DB
    public function stripBodyTag($html)
    {
        // Remove opening <body ...>
        $html = preg_replace('/<body[^>]*>/i', '', $html);

        // Remove closing </body>
        $html = preg_replace('/<\/body>/i', '', $html);
        $html = str_replace("null", "", $html);
        $html = str_replace("Null", "", $html);
        return trim($html);
    }

    //add body tag to field html after get from DB
    public function addBodyTag($html)
    {
        // Ensure no duplicate body tags
        $html = $this->stripBodyTag($html);
        // Add back <body> with optional attributes
        return "<body>\n{$html}\n</body>";
    }

    //remove base css from content builder css before store into DB
    public function removeBaseCss($css)
    {
        $baseCssVariants = [
            "*{box-sizing:border-box;}body{margin:0;}",
            "*{box-sizing:border-box;}body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}"
        ];
        // Remove all whitespace differences (normalize strings)
        $normalizedCss = preg_replace('/\s+/', '', $css);

        // If base CSS exists, remove it
        foreach ($baseCssVariants as $variant) {
            $normalizedVariant = preg_replace('/\s+/', '', $variant);
            while (strpos($normalizedCss, $normalizedVariant) !== false) {
                $normalizedCss = str_replace($normalizedVariant, '', $normalizedCss);
            }
        }

        return $normalizedCss;
    }

    //add base css to field css after get from DB
    public function addBaseCss($css)
    {
        $baseCss = "*{box-sizing:border-box;}body{margin:0;}";
        // First remove any duplicates
        $cleanCss = $this->removeBaseCss($css);

        // Add back the base CSS at the start
        return $baseCss . "\n" . $cleanCss;
    }

     public function getContentBuilderIframe(Request $request, $itemId)
     {
        $moduleInfo = CRUDBooster::getCurrentModule();
        $tableName = $moduleInfo->table_name;
        $translationTable = $moduleInfo->translation_table;
        $modulePath = $moduleInfo->path;

        $itemTitle = "";
        $item = DB::table($tableName)->where('id', $itemId)->first();
        if ($translationTable && $translationTable != '') {
            if($request->lang && $request->lang != '') {
                $foreignKeyName = Str::singular($tableName) . '_id';
                $item_info = DB::table($translationTable)->where("$foreignKeyName", $itemId)->where('locale',$request->lang)->first();
                if($item_info->name){
                    $itemTitle = $item_info->name;
                }elseif($item_info->title){
                    $itemTitle = $item_info->title;
                }
            }
        }else{
            if($item->name){
                $itemTitle = $item->name;
            }elseif($item->title){
                $itemTitle = $item->title;
            }
        }
       
        $iframeURL = str_replace('-iframe','',$request->getRequestUri());
       
        $website_languages = Language::where('active',1)->get();
        $content_lang = $request->lang;
        $fieldName = $request->field;

        return view('content_builder.builder-iframe', compact("iframeURL","itemId",'fieldName',"itemTitle","content_lang","website_languages"));
     }
    //open content builder view with module, field information
    public function getContentBuilder(Request $request, $itemId)
    {
        $blocks =  DB::table('custom_blocks')->where('builder_type','content_builder')->orWhereNULL('builder_type')->get();

        $moduleInfo = CRUDBooster::getCurrentModule();
        $tableName = $moduleInfo->table_name;
        $translationTable = $moduleInfo->translation_table;
        $modulePath = $moduleInfo->path;

        $item = DB::table($tableName)->where('id', $itemId)->first();

        $extra_params = "";
        if ($request->field && $request->field != '') {
            $extra_params = "field=" . $request->field;
        }
        if ($request->lang && $request->lang != '') {
            $extra_params .= "&lang=" . $request->lang;
        }

        if ($request->url && $request->url != '') {
            $extra_params .= "&url=" . $request->url;
        }

        $field =  $request->field;
        $content_fields = null;
        if ($translationTable && $translationTable != '') {
            $foreignKeyName = Str::singular($tableName) . '_id';
            $translation_item_info = DB::table($translationTable)->where($foreignKeyName, $itemId)->where('locale', $request->lang)->first();
            if ($translation_item_info) {
                $content_fields = json_decode($translation_item_info->$field);
            }
        } else {
            $content_fields = json_decode($item->$field);
        }

        //dd($content_fields);

        $is_rtl = 0;
        if ($request->lang && $request->lang == 'ar') {
            $is_rtl = 1;
        }

        $itemLink = '#';
        if ($request->url && $request->url != '') {
            $itemLink = $request->url;
        }


        return view('content_builder.builder', compact("modulePath", "itemId", "itemLink", "content_fields", "is_rtl", "extra_params", "blocks"));
    }

    //get content builder content from item info
    public function getContentBuilderContent(Request $request, $id)
    {
        $moduleInfo = CRUDBooster::getCurrentModule();
        $tableName = $moduleInfo->table_name;
        $translationTable = $moduleInfo->translation_table;

        $field = $request->field;
        $lang = $request->lang;

        $item = DB::table($tableName)->find($id);
        if ($item) {
            if ($translationTable != '') {
                $foreignKeyName = Str::singular($tableName) . '_id';
                $translation_item_info = DB::table($translationTable)->where($foreignKeyName, $id)->where('locale', $lang)->first();
                if ($translation_item_info) {
                    if ($translation_item_info->$field != '') {
                        if (self::is_json($translation_item_info->$field)) {
                            $result = json_decode($translation_item_info->$field);
                            return response()->json([
                                "gjs-html" => $this->addBodyTag($result->html),
                                "gjs-styles" => $this->addBaseCss($result->css),
                                "gjs-components" => $result->components == null ? "[]" : $result->components,
                                "variables" => $result->variables,
                            ]);
                        } else {
                            return response()->json([
                                "gjs-html" => $translation_item_info->$field,
                                "gjs-styles" => "",
                                "gjs-components" =>  "[]",
                                "variables" => ''
                            ]);
                        }
                    } else {
                        return response()->json([
                            "gjs-html" => '',
                            "gjs-styles" => '',
                            "gjs-components" => "[]",
                            "variables" => '',
                        ]);
                    }
                } else {
                    return response()->json([
                        "gjs-html" => '',
                        "gjs-styles" => '',
                        "gjs-components" => "[]",
                        "variables" => '',
                    ]);
                }
            } else {
                if ($item->$field != '') {
                    $result = json_decode($item->$field);
                    return response()->json([
                        "gjs-html" => $this->addBodyTag($result->html),
                        "gjs-styles" => $this->addBaseCss($result->css),
                        "gjs-components" => $result->components == null ? "[]" : $result->components,
                        "variables" => $result->variables,
                    ]);
                } else {
                    return response()->json([
                        "gjs-html" => '',
                        "gjs-styles" => '',
                        "gjs-components" => "[]",
                        "variables" => '',
                    ]);
                }
            }
        } else {
            return response()->json([
                "gjs-html" => '',
                "gjs-styles" => '',
                "gjs-components" => "[]",
                "variables" => '',
            ]);
        }
    }

    //store content builder result
    public function postContentBuilder(Request $request)
    {

        if ($request->custom_block_data) {
            DB::insert('insert into custom_blocks (custom_block_data,blockID,block_name) values (?, ?,?)', [$request->custom_block_data,  $request->blockId, $request->name]);

            return response()->json(array("message" => "done", "status" => true));
        }

        $moduleInfo = CRUDBooster::getCurrentModule();
        $tableName = $moduleInfo->table_name;
        $translationTable = $moduleInfo->translation_table;

        $field = $request->field;
        $lang = $request->lang;
        $id = $request->id;
        if ($id != '') {

            $result = [
                'html' => $this->stripBodyTag($request["html"]),
                'css' => $this->removeBaseCss($request["css"]),
                'components' => $request["components"],
                'variables' => $request["variables"],
                'is_rtl' => ($lang == 'ar') ? 1 : 0
            ];

            if ($translationTable != '') {
                $foreignKeyName = Str::singular($tableName) . '_id';
                DB::table($translationTable)->where($foreignKeyName, $id)->where('locale', $lang)->update([
                    "$field" => json_encode($result)
                ]);
            } else {
                DB::table($tableName)->where('id', $id)->update([
                    "$field" => json_encode($result)
                ]);
            }



            return response()->json(array("message" => "done", "status" => true));
        }

        return response()->json(array("message" => "faild", "status" => false));
    }


    //check if field is json object change field content by content builder (html, css)
    public function reGenerateContentBuilderCode($item_info, $field_name)
    {

        if (is_string($item_info->$field_name) && is_array(json_decode($item_info->$field_name, true))) {
            $contentBuilder = json_decode($item_info->$field_name);
            $newContent = "<style>" . $contentBuilder->css . "</style>" . $contentBuilder->html;
            $item_info->$field_name = $newContent;
        }
        return $item_info;
    }

    public static function is_json($value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        json_decode($value);
        return json_last_error() === JSON_ERROR_NONE;
    }
    public function getContentFrom(Request $request){
        $item_id = $request->item_id;
        $field = $request->field;
        $current_lang = $request->current_lang;
        $from_lang = $request->from_lang;

        $moduleInfo = CRUDBooster::getCurrentModule();
        $tableName = $moduleInfo->table_name;
        $translationTable = $moduleInfo->translation_table;

        $item = DB::table($tableName)->find($item_id);
        if ($item) {
            if ($translationTable != '') {
                $foreignKeyName = Str::singular($tableName) . '_id';
                $target_translation_item_info = DB::table($translationTable)->where($foreignKeyName, $item_id)->where('locale', $from_lang)->first();
                if ($target_translation_item_info) {
                    DB::table($translationTable)->where($foreignKeyName, $item_id)->where('locale', $current_lang)->update([
                            "$field" => $target_translation_item_info->$field
                    ]);
                    return response()->json(array("message" => "Get Content Successfully.", "status" => true));
                } else {
                   return response()->json(array("message" => "Failed. Not Content in Target Item.", "status" => false));
                }
            } else {
                return response()->json(array("message" => "Failed. Module don't support translation table.", "status" => false));  
            }
        } else {
            return response()->json(array("message" => "Failed. Item don't exist.", "status" => false));   
        }
    }
}
