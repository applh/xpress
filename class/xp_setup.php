<?php

class xp_setup
{
    static function plugins_loaded ()
    {
        // https://developer.wordpress.org/reference/functions/add_menu_page/
        if (is_admin()) {
            add_action("admin_menu", "xp_setup::admin_init");
        }

    }

    static function admin_init ()
    {
        // https://developer.wordpress.org/reference/functions/add_plugins_page/
        add_plugins_page(
            "XPress",
            "XPress",
            "edit_plugins",
            "xpress-admin",
            "xp_setup::admin_page",
        );
    }

    static function admin_page ()
    {
        // check xpress_api_key 
        $xpress_api_key = get_option('xpress_api_key');
        // if not set create random md5
        if (!$xpress_api_key) {
            $xpress_api_key = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
            update_option('xpress_api_key', $xpress_api_key);
        }

        // check if xpress-data folder exists
        $xpress_data_folder = __DIR__ . "/../../xpress-data";
        if (!file_exists($xpress_data_folder)) {
            mkdir($xpress_data_folder);

            $code = '$xpress_api_key = "' . $xpress_api_key . '";';

            // add index.php with plugin annotation
            $index_php = 
            <<<php
            <?php
            /*
            Plugin Name: XPress Data
            */

            $code

            php;

            file_put_contents($xpress_data_folder . "/index.php", $index_php);
        }

        // should output: /wp-content/plugins/xpress-main 
        $xp_url = plugin_dir_url(__DIR__);

        require __DIR__ . "/../templates/plugin-admin.php";
    }
}
