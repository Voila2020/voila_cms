<?php

namespace crocodicstudio\crudbooster\controllers;

use App\Seo;
use Illuminate\Support\Facades\Request;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class AIContentGeneratorController extends Controller
{

    public function generate_SEO_By_Ai(Request $request)
    {
        //dd($request::all());

        $page = $request::get('page');
        $page_id = $request::get('page_id');

        //get seo mode (website, module, item, page)
        $seo_mode = "";
        if ($page == null && $page_id == null) {
            $seo_mode = 'website';
        } else {
            if ($page != null && $page_id != null) {
                $seo_mode = 'item';
            } else {
                $seo_mode = 'module';
            }
        }

        $seo_fields_mode = 'with_lang'; //with_lang or without_lang

        if (CRUDBooster::checkMaximumTokensUsageLimit()) {

            $result = [];

            $website = env('APP_URL', url('/'));
            $company_name = CRUDBooster::getAISetting('company_name');
            $theme_type = CRUDBooster::getAISetting('website_type');
            $languages = $this->getWebsiteLanguagesAsJSON();

            if ($seo_mode == 'website') {
                $result = CRUDBooster::generateSEOForWebsite($website, $company_name, $theme_type, $languages);
                $seo_fields_mode = 'with_lang';
            } elseif ($seo_mode == 'module') {

                //get module name
                $module = DB::table('cms_moduls')->where('path', $page)->first();
                $module_name = $module->name;

                $result = CRUDBooster::generateSEOForModule($website, $company_name, $theme_type, $module_name, $languages);
                $seo_fields_mode = 'with_lang';
            } elseif ($seo_mode == 'item') {
                //get module name
                $module = DB::table('cms_moduls')->where('path', $page)->first();
                $module_name = $module->name;

                //get item content
                $item_content = $this->getItemContentAsJSON($module, $page_id);
                $result = CRUDBooster::generateSEOForModuleItem($website, $company_name, $theme_type, $module_name, $languages, $item_content);
                $seo_fields_mode = 'with_lang';
            }

            if ($result['status'] == 'success') {

                $seo_info_json = $result['result']['seo_info'];
                $data = json_decode($seo_info_json, true);
                $seo_array = $data['metadata'] ?? $data ?? null;

                if ($seo_array && is_array($seo_array)) {
                    // Re-index using lang as the key
                    $seo_by_lang = [];
                    foreach ($seo_array as $item) {
                        $lang = $item['lang'];
                        unset($item['lang']); // Remove 'lang' from the item
                        $seo_by_lang[$lang] = $item;
                    }
                    echo json_encode(array('status' => 'success', 'message' => 'generate seo with ai success', 'seo_fields_mode' => $seo_fields_mode, 'seo_by_lang' => $seo_by_lang));
                } else {
                    echo json_encode(array('status' => 'failed', 'message' => 'generate seo with ai failed response invalid'));
                }
            } else {
                echo json_encode(array('status' => 'failed', 'message' => 'generate seo with ai failed'));
            }
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'you have exceeded the allowed token usage limit'));
        }
    }


    public function getWebsiteLanguagesAsJSON()
    {
        $languages = DB::table('languages')->where('active', 1)->get();
        $languages_as_json = "";
        if ($languages && count($languages) > 0) {
            $languages_info = [];
            foreach ($languages as $lang) {
                $languages_info[] = [
                    'code' => $lang->code,
                    'name' => $lang->name
                ];
            }
            $languages_as_json = json_encode($languages_info);
        }
        return $languages_as_json;
    }

    public function getItemContentAsJSON($module, $item_id)
    {
        $item_content_by_lang = [];
        $translation_table = "";
        if ($module->translation_table) {
            $translation_table =  $module->translation_table;
        }

        if ($translation_table != '') {
            $foreignKeyName = Str::singular($module->table_name) . '_id';

            $item_by_langs =  DB::table($translation_table)->where($foreignKeyName, $item_id)->get();
            foreach ($item_by_langs as $rec) {
                if (property_exists($rec, 'title')) {
                    $item_content_by_lang['title_' . $rec->locale] = $rec->title;
                }

                if (property_exists($rec, 'brief')) {
                    $item_content_by_lang['brief_' . $rec->locale] = $rec->brief;
                }

                if (property_exists($rec, 'name')) {
                    $item_content_by_lang['name_' . $rec->locale] = $rec->name;
                }

                if (property_exists($rec, 'description')) {
                    $item_content_by_lang['description_' . $rec->locale] = $rec->description;
                }
            }
        } else {
            $rec =  DB::table($module->table_name)->where('id', $item_id)->first();
            if ($rec) {
                if (property_exists($rec, 'title')) {
                    $item_content_by_lang['title'] = $rec->title;
                }

                if (property_exists($rec, 'brief')) {
                    $item_content_by_lang['brief'] = $rec->brief;
                }
                if (property_exists($rec, 'name')) {
                    $item_content_by_lang['name'] = $rec->name;
                }
                if (property_exists($rec, 'description')) {
                    $item_content_by_lang['description'] = $rec->description;
                }
            }
        }

        return json_encode($item_content_by_lang);
    }


    public function generate_Module_Item_Content_By_Ai(Request $request)
    {
        //dd($request::all());

        $module_id = $request::get('module_id');
        $item_topic = $request::get('item_topic');

        if (CRUDBooster::checkMaximumTokensUsageLimit()) {


            $result = [];

            if ($module_id != '' && $item_topic != '') {

                $website = env('APP_URL', url('/'));
                $company_name = CRUDBooster::getAISetting('company_name');
                $theme_type = CRUDBooster::getAISetting('website_type');
                $languages = $this->getWebsiteLanguagesAsJSON();

                //get module information
                $module = DB::table('cms_moduls')->where('id', $module_id)->first();
                $module_name = $module->name;


                //item Fields for api as json
                $item_fields = $this->getModuleItemsFieldsAsJSON($module);

                $result =  CRUDBooster::generateModuleItemContent($website, $company_name, $theme_type, $module_name, $languages, $item_topic, $item_fields);
            }

            if (!empty($result) && $result['status'] == 'success') {
                $item_content_info = $result['result']['item_content_info'];
                $data = json_decode($item_content_info, true);
                $item_content_array =  $data ?? null;

                if ($item_content_array && is_array($item_content_array)) {
                    //get active langs
                    $active_site_langs = DB::table('languages')->where('active', 1)->get();

                    $insertedID = $this->insertModuleItemData($module, $active_site_langs, $item_content_array);

                    echo json_encode(array('status' => 'success', 'message' => 'generate module item by ai success', 'module_path' => $module->path, 'item_inserted_id' => $insertedID));
                } else {
                    echo json_encode(array('status' => 'failed', 'message' =>  'generate module item by ai failed response invalid'));
                }
            } else {
                echo json_encode(array('status' => 'failed', 'message' => 'generate module item by ai failed'));
            }
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'you have exceeded the allowed token usage limit'));
        }
    }

    public function getModuleItemsFieldsAsJSON($module)
    {

        //get module fields information
        $moduleControllerObj = CRUDBooster::getGeneratedModuleControllerInstance($module);
        $forms_fields = $moduleControllerObj->form;

        //get default language
        $default_fields_lang = '';
        $default_language = DB::table('languages')->where('active', 1)->where('default', 1)->first();
        if ($default_language) {
            $default_fields_lang = $default_language->code;
        }

        //get module fields for api as json
        $item_fields = [];

        foreach ($forms_fields as $field) {
            $type = $field['type'];
            if ($type == 'wysiwyg') {
                $type = 'FullTextEditor';
            }
            if (in_array($type, ['text', 'textarea', 'SimpleTextEditor', 'FullTextEditor'])) {
                //SimpleTextEditor, FullTextEditor
                $item_fields[] = [
                    'name' => $field['name'],
                    'type' => $type,
                    'lang_effect' => ($field['translation']) ? 'yes' : 'no',
                    'default_lang' => ($default_fields_lang != '') ? $default_fields_lang : 'en'
                ];
            }
            if ($type == 'icon') {
                $item_fields[] = [
                    'name' => $field['name'],
                    'type' => $field['type'],
                    'lang_effect' => 'no'
                ];
            }
        }

        $item_fields_as_json = '';
        if (count($item_fields) > 0) {
            $item_fields_as_json = json_encode($item_fields);
        }
        return $item_fields_as_json;
    }

    public function insertModuleItemData($module, $active_site_langs, $item_content_array)
    {
        //get module table_name
        $module_table_name = $module->table_name;
        $module_translation_table_name = $module->translation_table;

        if ($module_translation_table_name && $module_translation_table_name != '') { //module has translation table

            //insert module item data to database and return insertedID
            $moduleControllerObj = CRUDBooster::getGeneratedModuleControllerInstance($module);
            $module_fields = $moduleControllerObj->form;

            $values_into_module_table = [];
            $values_into_module_translation_table = [];
            foreach ($active_site_langs as $lang) {
                $values_into_module_translation_table["$lang->code"] = array();
            }

            foreach ($module_fields as $field) {
                $name = $field['name'];
                $type = $field['type'];
                $lang_effect = $field['translation'];
                if (in_array($type, ['text', 'textarea', 'wysiwyg', 'icon'])) {
                    if ($lang_effect) {
                        foreach ($active_site_langs as $lang) {
                            array_push($values_into_module_translation_table["$lang->code"], array('name' => $name, 'value' => $item_content_array[$name . "_$lang->code"]));
                        }
                    } else {
                        $values_into_module_table[] = array('name' => $name, 'value' => $item_content_array[$name]);
                    }
                } elseif ($type == 'date' || $type == 'datetime') {
                    $values_into_module_table[] = array('name' => $name, 'value' => date('Y-m-d'));
                } elseif ($type == 'switch') {
                    $values_into_module_table[] = array('name' => $name, 'value' => 1);
                }
            }

            //insert values to module table
            $inserted_values_into_table = collect($values_into_module_table)->pluck('value', 'name')->toArray();
            if (Schema::hasColumn($module->table_name, 'created_at')) {
                $inserted_values_into_table['created_at'] = date('Y-m-d H:i:s');
            }
            if (Schema::hasColumn($module->table_name, 'sorting')) {
                $inserted_values_into_table['sorting'] = DB::table($module->table_name)->max('sorting') + 1;
            }

            $module_item_insertedID = DB::table($module_table_name)->insertGetId($inserted_values_into_table);

            //insert values to module translation table
            $foreignKeyName = Str::singular($module->table_name) . '_id';

            foreach ($values_into_module_translation_table as $lang => $values) {
                $inserted_values_into_translation_table = collect($values)->pluck('value', 'name')->toArray();
                $inserted_values_into_translation_table["locale"] = $lang;
                $inserted_values_into_translation_table["$foreignKeyName"] = $module_item_insertedID;
                DB::table($module_translation_table_name)->insertGetId($inserted_values_into_translation_table);
            }

            return $module_item_insertedID;
        } else { //module doesn't have translation table
            //insert module item data to database and return insertedID
            $moduleControllerObj = CRUDBooster::getGeneratedModuleControllerInstance($module);
            $module_fields = $moduleControllerObj->form;

            $values_into_module_table = [];

            foreach ($module_fields as $field) {
                $name = $field['name'];
                $type = $field['type'];
                $lang_effect = $field['translation'];
                if (in_array($type, ['text', 'textarea', 'wysiwyg', 'icon'])) {
                    if ($lang_effect) {
                        foreach ($active_site_langs as $lang) {
                            $values_into_module_table[] = array('name' => $name . '_' . $lang->code, 'value' => $item_content_array[$name]);
                        }
                    } else {
                        $values_into_module_table[] = array('name' => $name, 'value' => $item_content_array[$name]);
                    }
                } elseif ($type == 'date' || $type == 'datetime') {
                    $values_into_module_table[] = array('name' => $name, 'value' => date('Y-m-d'));
                } elseif ($type == 'switch') {
                    $values_into_module_table[] = array('name' => $name, 'value' => 1);
                }
            }

            //insert values to module table
            $inserted_values_into_table = collect($values_into_module_table)->pluck('value', 'name')->toArray();
            $module_item_insertedID = DB::table($module_table_name)->insertGetId($inserted_values_into_table);

            return $module_item_insertedID;
        }
    }

    public function improve_content_By_Ai(Request $request)
    {

        $content = $request::get('content');
        $language = $request::get('language');
        $module_id = $request::get('module_id');
        $field_name = $request::get('field_name');

        $field_name = str_replace('_' . $language, '', $field_name);

        if (CRUDBooster::checkMaximumTokensUsageLimit()) {
            $result = [];

            if ($content != '') {
                $website = env('APP_URL', url('/'));
                $company_name = CRUDBooster::getAISetting('company_name');
                $theme_type = CRUDBooster::getAISetting('website_type');

                //get module information
                $module = DB::table('cms_moduls')->where('id', $module_id)->first();
                $module_name = $module->name;

                $language_info = DB::table('languages')->where('code', $language)->first();
                $language_json = '{"code":"' . $language_info->code . '","name":"' . $language_info->name . '"}';

                $result = CRUDBooster::improveContent($website, $company_name, $theme_type, $module_name, $language_json, $content, $field_name);
            }


            if ($result['status'] == 'success') {
                $improved_content = $result['result']['improved_content'];
                $data = json_decode($improved_content, true);
                $data =  $data ?? null;
                if ($data && is_array($data)) {
                    echo json_encode(array('status' => 'success', 'message' => 'Improve content success', 'improved_content' => $data['improved_content']));
                } else {
                    echo json_encode(array('status' => 'failed', 'message' => 'Improve content failed response invalid'));
                }
            } else {
                echo json_encode(array('status' => 'failed', 'message' => 'Improve content failed'));
            }
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'You have exceeded the allowed token usage limit'));
        }
    }

    public function translate_content_By_Ai(Request $request)
    {

        $content = $request::get('content');
        $source_lang = $request::get('source_lang');
        $target_lang = $request::get('target_lang');


        if (CRUDBooster::checkMaximumTokensUsageLimit()) {
            $result = [];

            if ($content != '' && $source_lang != '' && $target_lang != '') {
                $website = env('APP_URL', url('/'));
                $source_lang_info = DB::table('languages')->where('code', $source_lang)->first();
                $source_lang_json = '{"code":"' . $source_lang_info->code . '","name":"' . $source_lang_info->name . '"}';

                $target_lang_info = DB::table('languages')->where('code', $target_lang)->first();
                $target_lang_json = '{"code":"' . $target_lang_info->code . '","name":"' . $target_lang_info->name . '"}';
                $result =  CRUDBooster::translateContent($website, $content, $source_lang_json, $target_lang_json);
            }
            if ($result['status'] == 'success') {
                $translated_content = $result['result']['translated_content'];
                $data = json_decode($translated_content, true);
                $data =  $data ?? null;
                if ($data && is_array($data)) {
                    echo json_encode(array('status' => 'success', 'message' => 'Translate content success', 'translated_content' => $data['translated_content']));
                } else {
                    echo json_encode(array('status' => 'failed', 'message' => 'Translate content failed response invalid'));
                }
            } else {
                echo json_encode(array('status' => 'failed', 'message' => 'Translate content failed'));
            }
        } else {
            echo json_encode(array('status' => 'failed', 'message' => 'You have exceeded he allowed token usage limit'));
        }
    }


    public function showSettings()
    {

        $rows = DB::table('ai_content_settings')->pluck('setting_value', 'setting_name')->toArray();

        $currentTokenUsage = DB::table('ai_content_apis_logs')->sum('usage_total_tokens');

        $stats = [
            'current_token_usage'   => (int) $currentTokenUsage,
            'generated_seo'         => (int) (DB::table('ai_content_apis_logs')->where('ai_api_key', 'gernerate_seo_by_ai')->count() ?? 0),
            'generated_module_item' => (int) (DB::table('ai_content_apis_logs')->where('ai_api_key', 'generate_module_item_content_by_ai')->count() ?? 0),
            'improved_content'      => (int) (DB::table('ai_content_apis_logs')->where('ai_api_key', 'improve_content_by_ai')->count() ?? 0),
            'translated_content'    => (int) (DB::table('ai_content_apis_logs')->where('ai_api_key', 'translate_content_by_ai')->count() ?? 0),
        ];

        $logs = DB::table('ai_content_apis_logs')->orderBy('created_at', 'desc')->limit(10)->get();
        $page_title = "AI Settings";
        return view('crudbooster::ai_content_settings', [
            'settings' => $rows,
            'page_title' => $page_title,
            'style_css' => null,
            'stats'    => $stats,
            'logs'     => $logs,
            'updateAction' => url(config('crudbooster.ADMIN_PATH') . '/ai/settings/update'),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $fields = [];
        if (CRUDBooster::isSuperadmin()) {
            $fields = [
                'using_ai_features',
                'maximum_token_usage_limit',
                'personal_openai_api_key',
                'company_name',
                'company_description',
                'website_type'
            ];
            if ($request::get('personal_openai_api_key') != '') {
                $request::merge(['maximum_token_usage_limit' => INF]);
            }
        } else {
            $fields = [
                'using_ai_features',
                'personal_openai_api_key',
            ];
            if ($request::get('personal_openai_api_key') != '') {
                $fields[] = 'maximum_token_usage_limit';
                $request::merge(['maximum_token_usage_limit' => INF]);
            } else {
                $fields[] = 'maximum_token_usage_limit';
                $request::merge(['maximum_token_usage_limit' => '30000']);
            }
        }

        foreach ($fields as $name) {
            CRUDBooster::setAISetting($name, $request::get($name));
        }

        return back()->with('status', 'AI Settings saved successfully');
    }
}
