<?php

class xp_controller
{
    static function public ()
    {
        return true;
    }

    static function admin ()
    {
        return xp_controller_helper::check_api_key();
    }

    static function tool ()
    {
        return xp_controller_helper::check_api_key();
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
}