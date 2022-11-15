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
            $xp_data_dir = xp_os::get_dir("../xpress-data");

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

    // relative path to xpress-main plugin dir
    static function get_dir ($path="")
    {
        $plugin_dir = xpress::v("plugin_dir");
        $path2 = "$plugin_dir/$path";
        // create dir if not exists
        if (!file_exists($path2)) {
            mkdir($path2, 0777, true);
        }
        return realpath($path2);
    }

    static function unzip_url ($zip_url, $data_dir, $plugin_dir)
    {
        // load zip file from github
        $zip_data = file_get_contents($zip_url);
        $list_files = [];

        if ($zip_data) {
            if ($data_dir) {
                $zip_file = "$data_dir/xpress-main.zip";
                file_put_contents($zip_file, $zip_data);
                // list files in zip
                $zip = new ZipArchive;
                //rename all root dir files to xpress-main
                if ($zip->open($zip_file) === TRUE) {
                    for ($i = 0; $i < $zip->numFiles; $i++) {
                        $curfile = $zip->getNameIndex($i);
                        // get the parent folder of curfile
                        $parent = dirname($curfile);
                        // if parent is . then curfile is in root dir
                        if ($parent == ".") {
                            // rename curfile to xpress-main/curfile
                            $newfile = "xpress-main/$curfile";
                            $zip->renameIndex($i, $newfile);
                        }
                        else {
                            // else change first folder to xpress-main
                            // get the first folder
                            $first_folder = explode("/", $curfile)[0];
                            // rename first folder to xpress-main
                            $newfile = str_replace($first_folder, "xpress-main", $curfile);
                            $zip->renameIndex($i, $newfile);
                        }
                    }
                    $zip->close();
                }
                $res = $zip->open($zip_file);
                if ($res === TRUE) {
                    foreach (range(0, $zip->numFiles - 1) as $i) {
                        $list_files[] = $zip->getNameIndex($i);
                    }
                    $zip->extractTo($plugin_dir);
                    $zip->close();
                }
            }
        }
        
        return $list_files;
    }
}