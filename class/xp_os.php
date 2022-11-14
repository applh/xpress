<?php

class xp_os 
{
    static function xpress_ajax()
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
        $infos['feedback'] = xp_os::api_callback() ?? "";

        if (function_exists("wp_send_json")) {
            // debug header
            header("X-Xpress-debug: wp_json_send");
            wp_send_json($infos, 200); //use wp_json_send to return some data to the client.
            wp_die(); //use wp_die() once you have completed your execution.
        } else {
            // debug header
            header("X-Xpress-debug: json_encode");
            // return json response
            header('Content-Type: application/json');
            echo json_encode($infos, JSON_PRETTY_PRINT);
            // die();
        }
    }


    static function api_callback()
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
                if ($control_cb($m)) {
                    $feedback = $callback();
                } else {
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

    static function media_zip($state="")
    {
        static $zip = null;

        if ($zip == null) {

            // plugin xp-data dir
            $xp_data_dir = WP_PLUGIN_DIR . "/xpress-data";
            // create dir if not exists
            if (!file_exists($xp_data_dir)) {
                mkdir($xp_data_dir, 0777, true);
            }

            // find files in media dir with name media-*.zip
            $files = glob($xp_data_dir . "/media-*.zip");
            // if no files found create a new one with name media-%RANDOM-MD5%.zip
            $zip = null;
            if (count($files) == 0) {
                $random_tag = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
                $zip_file = $xp_data_dir . "/media-$random_tag.zip";
            }
            // else get the last file
            else {
                $zip_file = $files[count($files) - 1];
            }
            // create zip file
            $zip = new ZipArchive();
            $zip->open($zip_file, ZipArchive::CREATE);
        }
        else {
            // if state is false close zip file
            if ($state == "close") {
                $zip->close();
                $zip = null;
            }
        }

        return $zip;
    }

    static function template_include ($template)
    {
        // warning: 
        // $template must be the path to the template file
        // as it is included right after
        // in wp-includes/template-loader.php
        if (is_404()) {
            $uri = $_SERVER["REQUEST_URI"];
            // TODO: should take into account if home_url is not / 
            // if $uri starts with /@/
            $templates_dir = xpress::v("plugin_templates_dir");
            $template_xp = "";
            if (str_starts_with($uri, "/@/media")) {
                $template_xp = $templates_dir . "/media.php";                
            }
            if (str_starts_with($uri, "/@/admin")) {
                $template_xp = $templates_dir . "/xp-admin.php";
            }

            if ($template_xp && is_file($template_xp)) {
                // store original template
                xpress::v("template_404", $template);
                // set new template
                $template = $template_xp;
            }
        }
        
        // TODO: could be a page template

        return $template;
    }
}