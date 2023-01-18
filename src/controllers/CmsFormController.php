<?php

namespace crocodicstudio\crudbooster\controllers;

use App\Application;
use App\ApplicationField;
use App\FormField;
use App\LandingPage;
use App\Seo;
use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class CmsFormController extends \crocodicstudio\crudbooster\controllers\CBController
{
    //
    public function getForm($id)
    {
        if (!CRUDBooster::myId()) {
            return redirect(config('crudbooster.ADMIN_PATH') . '/login');
        }

        $form = DB::table('forms')->find($id);
        $elemnt_form = "";
        if ($form) {
            $elemnt_form .= "<form method='POST' action='" . url('/request/form') . '/' . $form->id . "' class='" . $form->row_type . " well' style='background:#FFF' >";
            $fileds = DB::table('form_field')->select(
                'form_field.*',
                'fields.title',
                'forms.*'
            )
                ->join('fields', 'form_field.field_id', '=', 'fields.id')
                ->join('forms', 'form_field.form_id', '=', 'forms.id')
                ->where('form_field.form_id', $form->id)->orderBy('form_field.sorting', 'asc')->get();
            // return  response()->json($fileds, 200);

            if ($fileds) {
                foreach ($fileds as $item) {
                    $req = ($item->required_filed == 'Yes') ? "required" : "";
                    $elemnt_form .= "<div class='form-group'>";
                    $elemnt_form .= "<label>" . $item->label_filed . ":</label>";
                    if ($item->title == 'email' || $item->title == 'text') {
                        $elemnt_form .= "<input type='" . $item->title . "' class='form-control' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />";
                    } else if ($item->title == 'checkbox') {
                        $array_values = explode(',', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form .= "<label><input type='" . $item->title . "'  value='" . $filed . "' name='" . $this->stripSpace($item->label_filed) . "[]' " . $req . " />" . $filed . "</label><br>";
                        }
                    } else if ($item->title == 'radio') {
                        $array_values = explode(',', $item->values);
                        foreach ($array_values as $filed) {
                            $elemnt_form .= "<label><input type='" . $item->title . "'  value='" . $filed . "' name='" . $this->stripSpace($item->label_filed) . "' " . $req . " />" . $filed . "</label><br>";
                        }
                    } else if ($item->title == 'select') {
                        $array_values = explode(',', $item->values);
                        $elemnt_form .= "<select name='" . $this->stripSpace($item->label_filed) . "' class='form-control' >";

                        foreach ($array_values as $filed) {
                            $elemnt_form .= "<option value='" . $filed . "'>" . $filed . "</option>";
                        }
                        $elemnt_form .= "</select>";
                    }

                    $elemnt_form .= "</div>";
                }
                $elemnt_form .= "<div class='form-group'>";

                $elemnt_form .= "<input type='submit' class='btn btn-primary' value='SEND' />";
                $elemnt_form .= "</div>";

                $elemnt_form .= "</form>";
            }
            return view('form', array('data' => $elemnt_form));
        }
    }

    public function submit(Request $request, $id)
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
                return redirect()->back()->with("error", $field->label_filed . " " . __("already_exists"));;
            }
        }

        //--------------------------------------//

        $application = DB::table('applications')->insert([
            'form_id' => $form->id,
            'ip' => request()->ip(),
            'landing_page_id' => $request->landing_page_id,
            'active' => 1,
            'updated_at' => Carbon::now()
        ]);

        $submit = "<table class='table'><thead><tr>";
        foreach ($fields as $item) {
            $submit .= "<th>" . $item->label_filed . "</th>";
        }
        $submit .= "</tr></thead><body><tr>";
        foreach ($fields as $item) {
            if (is_array($request->input($this->stripSpace($item->label_filed)))) {
                $submit .= "<td>";
                foreach ($request->input($this->stripSpace($item->label_filed)) as $val) {
                    $submit .= $val . ",";
                }
                $submit .= "</td>";
            } else {
                $submit .= "<td>" . $request->input($this->stripSpace($item->label_filed)) . "</td>";
            }


            $applicationField = DB::table('applications_fields')->insert([
                'application_id' => $application->id,
                'form_id' => $item->form_id,
                'field_id' => $item->id,
                'landing_page_id' => $request->landing_page_id,
                'value' => $request->input($this->stripSpace($item->label_filed))
            ]);
        }

        $submit .= "</tr></tbody></table>";

        DB::table('applications')->where('id', $application->id)->update([
            'response' => $submit
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

    public function getSubmits($id)
    {
        $applications = DB::table('applications')
            ->where('form_id', $id)
            ->get();
        return view('submits', array('data' => $applications));
    }
    private function stripSpace($string)
    {
        return $string = str_replace(' ', '', trim($string));
    }

    public function getForms()
    {
        if (!CRUDBooster::myId()) {
            return redirect(config('crudbooster.ADMIN_PATH') . '/login');
        }

        $forms = DB::table('forms')->get();
        return response()->json(array("data" => $forms), 200);
    }

    public function getLandingPageThankyou($id)
    {
        $landingPage = DB::table('landing_pages')
            ->find($id);
        if (!$landingPage) {
            abort(404);
        }

        $response = $landingPage->response_message;
        return view("landing_page_builder.thankyou", compact("response"));
    }

    public function getApplicationsUnique(Request $request)
    {
        // $applicationField = ApplicationField::where();
    }
}
