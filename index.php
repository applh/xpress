<?php
/**
 * Plugin Name: XPress
 */

// Basic WP security
if (!is_callable("add_action")) return;

class xpress
{
    static function plugin ()
    {
        // add autoloader
        spl_autoload_register('xpress::autoload');

        // add the setup method
        add_action('plugins_loaded', 'xp_setup::plugins_loaded');
    }

    // autoloader
    static function autoload ($class)
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
xpress::plugin();