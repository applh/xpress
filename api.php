<?php

class xpress_api
{
    static function json ()
    {
        // add autoloader
        spl_autoload_register('xpress_api::autoload');
        // return json response
        $infos = [];
        // time
        $infos['time'] = time();
        // request
        $infos['request'] = $_REQUEST;
        // files
        $infos['files'] = $_FILES;

        // get class c and method m
        $c = $_REQUEST['c'] ?? "public";
        $m = $_REQUEST['m'] ?? '';
        // sanitize c and m
        $c = preg_replace('/[^a-z0-9_]/i', '', $c);
        $m = preg_replace('/[^a-z0-9_]/i', '', $m);
        // get callback
        $callback = "xpi_$c::$m";
        $infos['callable'] = $callback;
        // call callable if callable
        if (is_callable($callback)) {
            // control access
            if (xp_controller::$c()) {
                $feedback = $callback();
                $infos['feedback'] = $feedback ?? "";
            } else {
                $infos['error'] = "access denied";
            }
        } else {
            $infos['feedback'] = "not callable";
        }

        // return json response
        header('Content-Type: application/json');
        echo json_encode($infos);
    }

    // autoloader
    static function autoload($class)
    {
        // get the class file
        $file = __DIR__ . "/class/$class.php";

        // check if the file exists
        if (file_exists($file)) {
            require $file;
        }
    }

    static function get_option ($name)
    {
        $res = '';
        // global $wpdb;
        // $table = $wpdb->prefix . "options";
        // $sql = "SELECT option_value FROM $table WHERE option_name = '$name'";
        // $res = $wpdb->get_var($sql);

        // include file ../xppress-data/index.php if exists
        $file = __DIR__ . "/../xpress-data/index.php";
        if (file_exists($file)) {
            include $file;
            $res = $xpress_api_key ?? '';
        }
        return $res;
    }
}

// call the main method
xpress_api::json();
