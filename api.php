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
            $feedback = $callback();
            $infos['feedback'] = $feedback ?? "";
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
}

// call the main method
xpress_api::json();
