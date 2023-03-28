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
    }

    public function getIndex()
    {
        $languages = DB::table('languages')->where('active', 1)->get();
        $columns = [];
        $columnsCount = $languages->count();
        if ($languages->count() > 0) {
            foreach ($languages as $key => $language) {
                if ($key == 0) {
                    $columns[$key] = $this->openJSONFile($language->code);
                }
                $columns[++$key] = ['data' => $this->openJSONFile($language->code), 'lang' => $language->code];
            }
        }

        return view('crudbooster::languages', compact('languages', 'columns', 'columnsCount'));
    }

    public function postStore(Request $request)
    {
        $request->validate([
            'key' => 'required',
            // 'value' => 'required',
        ]);
        foreach ($request->all() as $key => $input) {
            if (str_contains($key, 'val')) {
                $code = explode('_', $key);
                $code = $code[1];
                $data = $this->openJSONFile($code);
                $data[$request->key] = $input;
                $this->saveJSONFile($code, $data);
            }
        }
        $return_url = CRUDBooster::adminPath('languages');
        return redirect($return_url);
    }

    public function postTransUpdate(Request $request)
    {
        $data = $this->openJSONFile($request->code);
        $data[$request->pk] = $request->value;

        $this->saveJSONFile($request->code, $data);
        return response()->json(['success' => 'Done!']);
    }

    public function postDestroy($key)
    {
        $languages = DB::table('languages')->get();

        if ($languages->count() > 0) {
            foreach ($languages as $language) {
                $data = $this->openJSONFile($language->code);
                unset($data[$key]);
                $this->saveJSONFile($language->code, $data);
            }
        }
        return response()->json(['success' => $key]);
    }

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

    public function postTransUpdateKey(Request $request)
    {
        $languages = DB::table('languages')->get();

        if ($languages->count() > 0) {
            foreach ($languages as $language) {
                $data = $this->openJSONFile($language->code);
                if (isset($data[$request->pk])) {
                    $data[$request->value] = $data[$request->pk];
                    unset($data[$request->pk]);
                    $this->saveJSONFile($language->code, $data);
                }
            }
        }

        return response()->json(['success' => 'Done!']);
    }
}
