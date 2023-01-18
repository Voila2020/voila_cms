<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use PDO;

class RouterController extends Controller
{
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
