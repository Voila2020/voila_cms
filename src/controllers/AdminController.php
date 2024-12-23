<?php

namespace crocodicstudio\crudbooster\controllers;

use App\Rules\ReCaptcha;
use Carbon\Carbon;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AdminController extends CBController
{
    public function getIndex()
    {
        $data = [];
        $data['page_title'] = '<strong>Dashboard</strong>';

        return view('crudbooster::home', $data);
    }

    public function getLockscreen()
    {

        if (!CRUDBooster::myId()) {
            Session::flush();

            return redirect()->route('getLogin')->with('message', cbLang('alert_session_expired'));
        }

        Session::put('admin_lock', 1);

        return view('crudbooster::lockscreen');
    }

    public function postUnlockScreen()
    {
        $id = CRUDBooster::myId();
        $password = request('password');
        $users = DB::table(config('crudbooster.USER_TABLE'))->where('id', $id)->first();

        if (Hash::check($password, $users->password)) {
            Session::put('admin_lock', 0);

            return redirect(CRUDBooster::adminPath());
        } else {
            echo "<script>alert('" . cbLang('alert_password_wrong') . "');history.go(-1);</script>";
        }
    }

    public function getLogin()
    {

        if (CRUDBooster::myId()) {
            return redirect(CRUDBooster::adminPath());
        }

        return view('crudbooster::login');
    }

    public function postLogin()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:' . config('crudbooster.USER_TABLE'),
            'password' => 'required',
        ]);

        if (CRUDBooster::getSetting('recaptcha_site_key') && CRUDBooster::getSetting('recaptcha_secret_key')) {
            $validator->sometimes('g-recaptcha-response', ['required', new ReCaptcha], function ($input) {
                return true;
            });
        }

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $email = Request::input("email");
        $password = Request::input("password");
        $users = DB::table(config('crudbooster.USER_TABLE'))->where("email", $email)->first();

        if (Hash::check($password, $users->password)) {
            $multi_auth = get_setting('multi_authentication');
            if($multi_auth == 'On' && $users->id != 1){ //not superadmin and multi auth is On
                //send authentication code to email
                $auth_code = mt_rand(10000000, 99999999);
                DB::table(config('crudbooster.USER_TABLE'))->where("email", $email)->update([
                    'auth_code' => $auth_code,
                ]);

                CRUDBooster::sendEmail([
                    'to' => $users->email,
                    'data' => [
                        'name' => $users->name,
                        'auth_code' => $auth_code
                    ],
                    'template' => 'multi_authentication_email',
                    'attachments' => [],
                ]);
                return view('crudbooster::multi_authentication')->with(['email' => $email]); 
            }else{
                $priv = DB::table("cms_privileges")->where("id", $users->id_cms_privileges)->first();

                $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', $users->id_cms_privileges)->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();
    
                $photo = ($users->photo) ? asset($users->photo) : (CRUDBooster::getSetting('default_img') ? asset(CRUDBooster::getSetting('default_img')) : asset('vendor/crudbooster/avatar.jpg'));
                Session::put('admin_id', $users->id);
                Session::put('admin_is_superadmin', $priv->is_superadmin);
                Session::put('admin_name', $users->name);
                Session::put('admin_photo', $photo);
                Session::put('admin_privileges_roles', $roles);
                Session::put("admin_privileges", $users->id_cms_privileges);
                Session::put('admin_privileges_name', $priv->name);
                Session::put('admin_lock', 0);
                Session::put('theme_color', $priv->theme_color);
                Session::put('remember_email', $email);
                Session::put("appname", get_setting('appname'));
    
                CRUDBooster::insertLog(cbLang("log_login", ['email' => $users->email, 'ip' => Request::server('REMOTE_ADDR')]));
    
                $cb_hook_session = new \App\Http\Controllers\CBHook;
                $cb_hook_session->afterLogin();
                return redirect(CRUDBooster::adminPath());
            }

        } else {
            return redirect()->route('getLogin')->with([
                'message' => cbLang('alert_password_wrong'),
                'remember_email' => $email,
            ]);
        }
    }

    public function getForgot()
    {
        if (CRUDBooster::myId()) {
            return redirect(CRUDBooster::adminPath());
        }

        return view('crudbooster::forgot');
    }

    public function postForgot()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:' . config('crudbooster.USER_TABLE'),
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }
        $token = str_random(60);
        DB::table(config('crudbooster.USER_TABLE'))->where('email', Request::input('email'))->update(['token' => $token, 'token_created_at' => Carbon::now()]);
        $appname = CRUDBooster::getSetting('appname');
        $user = CRUDBooster::first(config('crudbooster.USER_TABLE'), ['email' => g('email')]);
        $link = CRUDBooster::adminPath() . '/password/reset/' . $token;
        $user->link = $link;
        //$user->password = $rand_string;
        CRUDBooster::sendEmail(['to' => [$user->email], 'data' => $user, 'template' => 'forgot_password_backend']);

        CRUDBooster::insertLog(cbLang("log_forgot", ['email' => g('email'), 'ip' => Request::server('REMOTE_ADDR')]));

        return redirect()->route('getLogin')->with('message', cbLang("message_forgot_password"));
    }

    public function getMultiAuthentication()
    {
        if (CRUDBooster::myId()) {
            return redirect(CRUDBooster::adminPath());
        }

        return view('crudbooster::multi_authentication');
    }

    public function postMultiAuthentication()
    {
        $validator = Validator::make(Request::all(), [
            'email' => 'required|email|exists:' . config('crudbooster.USER_TABLE'),
            'auth_code' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();

            return redirect()->route('getMultiAuthentication')->with(['message' => implode(', ', $message), 'message_type' => 'danger','email'=>g('email')]);
        }
       
        $users = CRUDBooster::first(config('crudbooster.USER_TABLE'), ['email' => g('email')]);
        if($users->auth_code == g('auth_code')){
            //insert to log
            CRUDBooster::insertLog(cbLang("log_multi_authentication", ['email' => g('email'), 'ip' => Request::server('REMOTE_ADDR')]));
        
            //continue login 
            $priv = DB::table("cms_privileges")->where("id", $users->id_cms_privileges)->first();

            $roles = DB::table('cms_privileges_roles')->where('id_cms_privileges', $users->id_cms_privileges)->join('cms_moduls', 'cms_moduls.id', '=', 'id_cms_moduls')->select('cms_moduls.name', 'cms_moduls.path', 'is_visible', 'is_create', 'is_read', 'is_edit', 'is_delete')->get();

            $photo = ($users->photo) ? asset($users->photo) : (CRUDBooster::getSetting('default_img') ? asset(CRUDBooster::getSetting('default_img')) : asset('vendor/crudbooster/avatar.jpg'));
            Session::put('admin_id', $users->id);
            Session::put('admin_is_superadmin', $priv->is_superadmin);
            Session::put('admin_name', $users->name);
            Session::put('admin_photo', $photo);
            Session::put('admin_privileges_roles', $roles);
            Session::put("admin_privileges", $users->id_cms_privileges);
            Session::put('admin_privileges_name', $priv->name);
            Session::put('admin_lock', 0);
            Session::put('theme_color', $priv->theme_color);
            Session::put('remember_email', $users->email);
            Session::put("appname", get_setting('appname'));

            CRUDBooster::insertLog(cbLang("log_login", ['email' => $users->email, 'ip' => Request::server('REMOTE_ADDR')]));

            $cb_hook_session = new \App\Http\Controllers\CBHook;
            $cb_hook_session->afterLogin();
            return redirect(CRUDBooster::adminPath());
        }else{
           
            return redirect()->route('getMultiAuthentication')->with(['message' => cbLang("multi_authentication_code_incorrect"), 'message_type' => 'danger','email'=>g('email')]);
        }

    }

    public function getLogout()
    {

        $me = CRUDBooster::me();
        CRUDBooster::insertLog(cbLang("log_logout", ['email' => $me->email]));

        Session::flush();

        return redirect()->route('getLogin')->with('message', cbLang("message_after_logout"));
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make(Request::all(), [
            'token' => 'required',
            'reset_password' => 'required',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            $message = $validator->errors()->all();
            return redirect()->back()->with(['message' => implode(', ', $message), 'message_type' => 'danger']);
        }

        $tokenData = DB::table('cms_users')->where('token', Request::input("token"))->first();
        if (!$tokenData) {
            return redirect()->route('getLogin')->with(['message' => cbLang("message_not_valid_reset_token")]);
        }

        $currentTime = Carbon::now();
        if ($currentTime->diffInMinutes($tokenData->token_created_at) > config('crudbooster.reset_password_expired_time')) {
            return redirect()->route('getLogin')->with(['message' => cbLang("message_expired_reset_token")]);
        }

        if (Request::input("reset_password") != Request::input("password_confirmation")) {
            return redirect()->back()->with(['message' => cbLang("password_reset_not_matching")]);
        }

        $cmsUser = DB::table('cms_users')->where('token', Request::input("token"))->update(['password' => Hash::make(Request::input("reset_password")), 'token' => null, 'token_created_at' => null]);
        return redirect()->route('getLogin')->with(['message' => cbLang("password_changed_successfully"), 'message_type' => 'success']);
    }

    public function viewPasswordReset($token)
    {
        $user = DB::table('cms_users')->where('token', $token)->first();
        if (!$user) {
            return redirect()->route('getLogin')->with(['message' => cbLang("alert_danger"), 'message_type' => 'danger']);
        }
        if ($user->token == $token) {
            return view('crudbooster::password-reset')->with(['token' => $token]);
        }
    }
}
