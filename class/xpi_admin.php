<?php

class xpi_admin
{
    static function test ()
    {
        // date
        $date = date("ymd-His");
        // plugin xp-data dir
        $xp_data_dir = WP_PLUGIN_DIR . "/xpress-data";
        // create dir if not exists
        if (!file_exists($xp_data_dir)) {
            mkdir($xp_data_dir, 0777, true);
        }

        // move all uploaded files to xp-data dir
        $files = $_FILES;
        foreach ($files as $file) {
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];
            $name = preg_replace('/[^a-z0-9-_\.]/i', '', $name);
            $name = strtolower($name);
            $dest = "$xp_data_dir/$name";
            move_uploaded_file($tmp_name, $dest);
        }
        return "admin test ($date)";
    }
}