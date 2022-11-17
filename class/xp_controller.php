<?php

class xp_controller
{
    static function public ($m="")
    {
        return true;
    }

    static function admin ($m="")
    {
        return xp_controller_helper::check_api_key();
    }

    static function tool ($m="")
    {
        return xp_controller_helper::check_api_key();
    }

    static function user ($m="")
    {
        // check admin access
        $res = xp_controller_helper::check_api_key();
        if (!$res) {
            $res = xp_controller_helper::check_user_api_key("user", $m);
        }
        return $res;
    }

}

class xp_controller_helper
{
    static function check_api_key ()
    {
        $res = false;
        // check xpress_api_key
        $xpress_api_key = "";
        if (function_exists("get_option")) {
            $xpress_api_key = get_option('xpress_api_key');
        }
        elseif (is_callable("xpress_api::get_option")) {
            $xpress_api_key = xpress_api::get_option('xpress_api_key');
        }
        
        // if not empty then compare with request
        if ($xpress_api_key) {
            // get request key
            $request_key = $_REQUEST['api_key'] ?? '';
            // sanitize request key
            $request_key = preg_replace('/[^a-z0-9]/i', '', $request_key);

            // compare
            if ($request_key == $xpress_api_key) {
                $res = true;
            }
        }
        return $res;
    }

    static function check_user_api_key ($c, $m="")
    {
        // user are granted access to class methods
        // user api key is composed with 
        // api_thash
        // api_tmax
        // api_thash = md5(xpress_api_key / c / api_tmax)
        $res = false;
        // check xpress_api_key
        $xpress_api_key = "";
        if (function_exists("get_option")) {
            $xpress_api_key = get_option('xpress_api_key');
        }
        elseif (is_callable("xpress_api::get_option")) {
            $xpress_api_key = xpress_api::get_option('xpress_api_key');
        }

        $request_tmax = 0;
        // if not empty then compare with request
        if ($xpress_api_key) {
            $request_key = $_REQUEST['api_key'] ?? '';
            // trim
            $request_key = trim($request_key);

            // check if api key length > 32
            if (strlen($request_key) > 32) {
                // api_key is composed with api_thash / api_tmax
                $api_key_parts = explode("/", $request_key);
                $api_thash = $api_key_parts[0] ?? "";
                $api_tmax = $api_key_parts[1] ?? "";
                $request_tmax = intval($api_tmax);
            }

            // check if request_tmax is in the future
            $now = time();
            if ($request_tmax > $now) {
                // get request key
                $request_thash = $api_thash ?? "";
                // sanitize request key
                $request_thash = preg_replace('/[^a-z0-9]/i', '', $request_thash);

                $user_key = md5("$xpress_api_key/$c/$request_tmax");

                // header("X-Xp-Debug--check-user-api-key: $request_thash/$user_key/$xpress_api_key/$c/$request_tmax");

                // compare
                if ($request_thash == $user_key) {
                    $res = true;
                }
            }
        }

        return $res;

    }
}