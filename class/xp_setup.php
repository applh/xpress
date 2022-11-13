<?php

class xp_setup
{
    static function plugins_loaded ()
    {
        // https://developer.wordpress.org/reference/functions/add_menu_page/
        if (is_admin()) {
            add_action("admin_menu", "xp_setup::admin_init");
        }

        // add new ajax action (not logged in) action="xpress_api"
        // warning: POST request only
        // curl -v -X POST -d "action=xpress" https://YOUSITE.COM/wp-admin/admin-ajax.php -o ajax.json
        add_action("wp_ajax_nopriv_xpress", "xp_setup::xpress_ajax");
    }

    static function xpress_ajax ()
    {
        // return json
        $infos = [];
        // time
        $infos['time'] = time();
        // date
        $infos['date'] = date("Y-m-d H:i:s");
        // request
        $infos['request'] = $_REQUEST;
        // files
        $infos['files'] = $_FILES;
        // debug
        // $infos['funcs'] = get_defined_functions();

        // check callback
        $infos['feedback'] = xp_setup::api_callback() ?? "";

        if (function_exists("wp_send_json")) {
            // debug header
            header("X-Xpress-debug: wp_json_send");
            wp_send_json($infos, 200); //use wp_json_send to return some data to the client.
            wp_die(); //use wp_die() once you have completed your execution.
        }
        else {
            // debug header
            header("X-Xpress-debug: json_encode");
            // return json response
            header('Content-Type: application/json');
            echo json_encode($infos, JSON_PRETTY_PRINT);
            die();
        }

    }

    static function api_callback ()
    {
        $feedback = "";
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
            $control_cb = "xp_controller::$c";
            if (is_callable($control_cb)) {
                if ($control_cb()) {
                    $feedback = $callback();
                }
                else {
                    $feedback = "Access denied";
                }
            } else {
                $feedback = "access denied";
            }
        } else {
            $feedback = "not callable";
        }

        return $feedback;
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
