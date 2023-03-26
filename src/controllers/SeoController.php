<?php

namespace crocodicstudio\crudbooster\controllers;

use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function index($page = "home", $page_id = null)
    {
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

    public function store($page, Request $request)
    {
        $data = $request->all();
        $conditions = array();
        $languages = DB::table('languages')->get();

        foreach ($languages as $lang) {
            array_push($conditions, ['page', '=', $page]);
            array_push($conditions, ['page_id', '=', $request->page_id ?: null]);
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
                    'page_id' => ($request->page_id) ? $request->page_id : null,
                    'page' => $page,
                    'language' => $lang->code,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'active' => 1,
                ]);
            }
        }

        return CRUDBooster::redirectBack(cbLang("alert_update_data_success"), 'success');
    }
}
