<?php

namespace crocodicstudio\crudbooster\controllers;

use CRUDBooster;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeoController extends \crocodicstudio\crudbooster\controllers\CBController
{
    public function get($model = "home", $model_id = null)
    {

        $conditions = array();

        if ($model_id != null) {

            array_push($conditions, ['model', '=', $model]);
            array_push($conditions, ['model_id', '=', $model_id]);
            $data = DB::table('cms_seo')->where($conditions)->first();
        } else {

            array_push($conditions, ['model', '=', $model]);
            array_push($conditions, ['model_id', '=', null]);

            $data = DB::table('cms_seo')->where($conditions)->first();
        }

        return view('crudbooster::seo', array('data' => $data, 'type' => $model, 'id' => $model_id));
    }

    public function store($model, Request $request)
    {
        if ($request->isMethod('post')) {

            $conditions = array();

            if ($request->model_id == null) {

                array_push($conditions, ['model', '=', $model]);
                array_push($conditions, ['model_id', '=', null]);
                $seoOld = DB::table('cms_seo')->where($conditions)->delete();
            } else {

                array_push($conditions, ['model', '=', $model]);
                array_push($conditions, ['model_id', '=', $request->model_id]);

                $seoOld = DB::table('cms_seo')->where($conditions)->delete();
            }

            DB::table('cms_seo')->insert([
                'title_ar' => ($request->title_ar) ? $request->title_ar : null,
                'title_en' => ($request->title_en) ? $request->title_en : null,
                'description_ar' => ($request->description_ar) ? $request->description_ar : null,
                'description_en' => ($request->description_en) ? $request->description_en : null,
                'keywords_ar' => ($request->keywords_ar) ? $request->keywords_ar : null,
                'keywords_en' => ($request->keywords_en) ? $request->keywords_en : null,
                'author_ar' => ($request->author_ar) ? $request->author_ar : null,
                'author_en' => ($request->author_en) ? $request->author_en : null,
                'model_id' => ($request->model_id) ? $request->model_id : null,
                'model' => $model,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'active' => 1
            ]);

            return CRUDBooster::redirectBack(cbLang("alert_update_data_success"), 'success');
        }
    }
}
