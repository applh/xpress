<?php

class xp_controller
{
    static function public ()
    {
        return true;
    }

    static function admin ()
    {
        $res = false;
        // check xpress_api_key
        $xpress_api_key = xpress_api::get_option('xpress_api_key');
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