<?php

namespace crocodicstudio\crudbooster\middlewares;

use Carbon\Carbon;
use Closure;
use crocodicstudio\crudbooster\helpers\CRUDBooster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

class CBAuthAttempts
{
    public function handle($request, Closure $next)
    {
        $ip_address = Request::ip();
        $diffInHours = new Carbon();
        $user = DB::table('cms_users')->where('email', $request->input('email'))->first();
        # if matches password
        $cms_login_attempts = DB::table('cms_login_attempts')->where('ip_address', $ip_address)->first();
        if (Hash::check($request->input('password'), $user->password)) {
            if (($cms_login_attempts && $diffInHours->diffInHours($cms_login_attempts->blocked_at, Carbon::now()->toDateTimeString()) > intval(get_setting('block_ip_in_hours')))
                || !$cms_login_attempts->blocked_at
            ) {
                DB::table('cms_login_attempts')->where('ip_address', $ip_address)
                    ->update([
                        'attempts' => 0,
                        'blocked_at' => null,
                    ]);
                return $next($request);
            }
        }

        if (!$cms_login_attempts) {
            DB::table('cms_login_attempts')->insert([
                'ip_address' => $ip_address,
                'attempts' => 0,
            ]);
            $cms_login_attempts = DB::table('cms_login_attempts')->where('ip_address', $ip_address)->first();
        }
        # blocked
        $block_msg = cbLang('login_block_msg');

        if ($cms_login_attempts->blocked_at) {
            $diffInHours = $diffInHours->diffInHours($cms_login_attempts->blocked_at, Carbon::now()->toDateTimeString());
            # Still expired
            if ($diffInHours < intval(get_setting('block_ip_in_hours'))) {
                return redirect()->back()->with([
                    'message' => $block_msg,
                    'message_type' => 'danger',
                ]);
            }
            # block time finished
            DB::table('cms_login_attempts')->where('ip_address', $ip_address)
                ->update([
                    'attempts' => 1,
                    'blocked_at' => null,
                ]);
        }
        # Not blocked
        else {
            $attempts = $cms_login_attempts->attempts + 1;
            if ($attempts == intval(get_setting('max_failed_login_trying'))) {
                DB::table('cms_login_attempts')->where('ip_address', $ip_address)
                    ->update([
                        'blocked_at' => Carbon::now(),
                        'attempts' => 0,
                    ]);
                return redirect()->back()->with([
                    'message' => $block_msg,
                    'message_type' => 'danger',
                ]);
            }
            DB::table('cms_login_attempts')->where('ip_address', $ip_address)
                ->update(['attempts' => $attempts]);
        }
        return $next($request);
    }
}
