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
        // store the plugin dir
        xpress::v("plugin_dir", __DIR__);
        // store the plugin templates dir
        xpress::v("plugin_templates_dir", __DIR__ . "/templates");
        // store the plugin url
        xpress::v("plugin_url", plugin_dir_url(__FILE__));
        
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

    // store key/value
    static function v ($key, $value=null)
    {
        static $data = array();

        if ($value == null) {
            // read
            return $data[$key] ?? null;
        }
        else {
            // write
            $data[$key] = $value;
            return $value;
        }
    }
}

// call the main method
xpress::plugin();