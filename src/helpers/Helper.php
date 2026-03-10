<?php
/*
| ---------------------------------------------------------------------------------------------------------------
| Main Helper of CRUDBooster
| Do not edit or modify this helper unless your modification will be replace if any update from CRUDBooster.
|
| Homepage : http://crudbooster.com
| ---------------------------------------------------------------------------------------------------------------
|
 */

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

if (!function_exists('ends_with')) {
    /**
     * Laravel ends_with alternative
     * @param $text
     * @param $need
     * @return bool
     */
    function ends_with($text, $need)
    {
        return \Illuminate\Support\Str::endsWith($text, $need);
    }
}

if (!function_exists('cbLang')) {
    /**
     * @param $key
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    function cbLang($key, array $replace = [], $locale = null)
    {
        if (get_setting('default_language')) {
            App::setlocale(get_setting('default_language') == 'english' ? 'en' : 'ar');
        }

        $value = trans('crudbooster.' . $key);
        if ($value != 'crudbooster.' . $key) {
            return trans("crudbooster." . $key, $replace, $locale);
        }

        return $key;
    }
}

if (!function_exists('db')) {
    /**
     * @return \Illuminate\Database\Query\Builder
     */
    function db(string $table)
    {
        return \Illuminate\Support\Facades\DB::table($table);
    }
}

if (!function_exists('assetThumbnail')) {
    function assetThumbnail($path)
    {
        $path = str_replace('uploads/', 'uploads_thumbnail/', $path);
        return asset($path);
    }
}

if (!function_exists('assetResize')) {
    function assetResize($path, $width, $height = null, $quality = 70)
    {
        $basename = basename((string) $path);
        $pathWithoutName = str_replace($basename, '', $path);
        $newLocation = $pathWithoutName . '/w_' . $width . '_h_' . $height . '_' . $basename;
        if (Storage::exists($newLocation)) {
            return asset($newLocation);
        } else {
            $img = Image::make(storage_path($path))->fit($width, $height);
            $img->save(storage_path($newLocation), $quality);
            return asset($newLocation);
        }
    }
}

if (!function_exists('extract_unit')) {
    /*
    Credits: Bit Repository
    URL: http://www.bitrepository.com/extract-content-between-two-delimiters-with-php.html
     */
    function extract_unit($string, $start, $end)
    {
        $pos = stripos((string) $string, (string) $start);
        $str = substr((string) $string, $pos);
        $str_two = substr($str, strlen((string) $start));
        $second_pos = stripos($str_two, (string) $end);
        $str_three = substr($str_two, 0, $second_pos); // remove whitespaces
        return trim($str_three);
    }
}

if (!function_exists('now')) {
    function now()
    {
        return date('Y-m-d H:i:s');
    }
}

/*
| --------------------------------------------------------------------------------------------------------------
| Get data from input post/get more simply
| --------------------------------------------------------------------------------------------------------------
| $name = name of input
|
 */

if (!function_exists('get_setting')) {
    /**
     * @param $key
     * @return bool
     */
    function get_setting($key, $default = null)
    {
        $setting = \crocodicstudio\crudbooster\helpers\CB::getSetting($key);
        return ($setting) ?: $default;
    }
}

if (!function_exists('set_setting')) {
    function set_setting($key, $value)
    {
        // $setting = ($setting) ?: null;
        return \crocodicstudio\crudbooster\helpers\CB::setSetting($key, $value);
    }
}

if (!function_exists('str_random')) {
    function str_random($length = 16)
    {
        return \Illuminate\Support\Str::random($length);
    }
}

if (!function_exists('str_slug')) {
    function str_slug($text, $separator = "-", $language = "en")
    {
        return \Illuminate\Support\Str::slug($text, $separator, $language);
    }
}

if (!function_exists('g')) {
    /**
     * @param $key
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Http\Request|string
     */
    function g($key, $default = null)
    {
        return request($key, $default);
    }
}

if (!function_exists('min_var_export')) {
    function min_var_export($input)
    {
        if (is_array($input)) {
            $buffer = [];
            foreach ($input as $key => $value) {
                $buffer[] = var_export($key, true) . "=>" . min_var_export($value);
            }

            return "[" . implode(",", $buffer) . "]";
        } else {
            return var_export($input, true);
        }

    }
}

if (!function_exists('cbSafeEval')) {
    /**
     * PHP 8 compatible safe evaluation function
     * Replaces eval() with safer variable-based approach
     * @param string $code The PHP code expression to evaluate
     * @return mixed The result of the evaluation
     */
    function cbSafeEval(string $code)
    {
        try {
            $result = null;
            // Use a closure to safely evaluate the code with proper scope
            $callback = function () use ($code, &$result) {
                $result = eval('return ' . $code . ';');
                return $result;
            };
            return $callback();
        } catch (\Throwable $e) {
            // Log error and return null for PHP 8 compatibility
            \Log::warning('cbSafeEval error: ' . $e->getMessage());
            return null;
        }
    }
}

if (!function_exists('rrmdir')) {
    /*
     * http://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object !== "." && $object !== "..") {
                    if (is_dir($dir . "/" . $object)) {
                        rrmdir($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }

                }
            }
            rmdir($dir);
        }
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= 1024 ** $pow;
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
