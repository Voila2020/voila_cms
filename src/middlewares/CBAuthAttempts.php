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
    /**
     * Whole hours since the IP was blocked, as Carbon 2 returned them (Carbon 3 returns a float).
     */
    private static function hoursSinceBlocked($blockedAt): int
    {
        return (int) Carbon::parse($blockedAt)->diffInHours(Carbon::now(), true);
    }

    public function handle($request, Closure $next)
    {
        $ip_address = Request::ip();
        $user = DB::table('cms_users')->where('email', $request->input('email'))->first();
        # if matches password
        $cms_login_attempts = DB::table('cms_login_attempts')->where('ip_address', $ip_address)->first();
        if ($user && Hash::check($request->input('password'), $user->password) && ($cms_login_attempts && self::hoursSinceBlocked($cms_login_attempts->blocked_at) > intval(get_setting('block_ip_in_hours')) || $cms_login_attempts && !$cms_login_attempts->blocked_at)) {
            DB::table('cms_login_attempts')->where('ip_address', $ip_address)
                ->update([
                    'attempts' => 0,
                    'blocked_at' => null,
                ]);
            return $next($request);
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
            $diffInHours = self::hoursSinceBlocked($cms_login_attempts->blocked_at);
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
