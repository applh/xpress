<?php

class xpi_tool
{

    static function update_zip ()
    {
        $plugin_dir = WP_PLUGIN_DIR;

        $res = "";
        $zip_url = "https://github.com/applh/xpress/archive/refs/heads/main.zip";
        $zip_file = "";
        $data_dir = xp_os::get_dir("../xpress-data");

        // take the first uploaded zip file if exists
        if (count($_FILES) > 0) {
            // $_FILES IS ASSOCIATIVE ARRAY
            $file = array_values($_FILES)[0]; 
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];
            // sanitize name (filename and extension)
            $name = preg_replace('/[^a-z0-9-_\.]/i', '', $name);
            $name = strtolower($name);
            // get extension
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            // header("X-Xp-Debug-update-zip-name: ($name)($ext)");

            // if extension is not allowed
            if (in_array($ext, ["zip"])) {
                // move file to plugin dir
                $prefix = date("ymd-His-") . uniqid();
                $zip_file = "$data_dir/$prefix-$name";
                move_uploaded_file($tmp_name, $zip_file);
                $zip_url = $zip_file;

                // debug header
                // header("X-Xp-Debug-update-zip: $zip_file");
            }
        }

        // load zip file from github
        $res = xp_os::unzip_url($zip_url, $data_dir, $plugin_dir);

        if (file_exists($zip_file)) {
            // delete zip file
            unlink($zip_file);
        }

        return $res;
    }
}