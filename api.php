<?php

class xpress_api
{
    static function json ()
    {
        // load wp-blog-header.php
        $path_wpbh = realpath( __DIR__ . '/../../../wp-blog-header.php' );
        // include if exists
        if (file_exists($path_wpbh)) {
            include_once $path_wpbh;
        }

        // add autoloader
        spl_autoload_register('xpress_api::autoload');
        xp_os::xpress_ajax();
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
