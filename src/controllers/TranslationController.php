<?php

namespace crocodicstudio\crudbooster\controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TranslationController extends \crocodicstudio\crudbooster\controllers\CBController
{
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function index()
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
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function store(Request $request)
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

        return redirect()->route('languages');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($key)
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

    /**
     * Open Translation File
     * @return Response
     */
    private function openJSONFile($code)
    {
        $jsonString = [];
        if (File::exists(base_path('resources/lang/' . $code . '.json'))) {
            $jsonString = file_get_contents(base_path('resources/lang/' . $code . '.json'));
            $jsonString = json_decode($jsonString, true);
        }
        return $jsonString;
    }

    /**
     * Save JSON File
     * @return Response
     */
    private function saveJSONFile($code, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
    }

    /**
     * Save JSON File
     * @return Response
     */
    public function transUpdate(Request $request)
    {
        $data = $this->openJSONFile($request->code);
        $data[$request->pk] = $request->value;

        $this->saveJSONFile($request->code, $data);
        return response()->json(['success' => 'Done!']);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function transUpdateKey(Request $request)
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
