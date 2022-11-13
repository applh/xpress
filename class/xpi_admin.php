<?php

class xpi_admin
{
    static function test ()
    {
        // date
        $date = date("ymd-His");
        // plugin xp-data dir
        $xp_data_dir = WP_PLUGIN_DIR . "/xpress-data";
        $xp_media_dir = "$xp_data_dir/media";

        // create dir if not exists
        if (!file_exists($xp_media_dir)) {
            mkdir($xp_media_dir, 0777, true);
        }
        // add index.php if not exists
        $index_php = $xp_media_dir . "/index.php";
        if (!file_exists($index_php)) {
            file_put_contents($index_php, "<?php // silence is golden");
        }
        // find files in media dir with name media-*.zip
        $files = glob($xp_data_dir . "/media-*.zip");
        // if no files found create a new one with name media-%RANDOM-MD5%.zip
        $zip = null;
        if (count($files) == 0) {
            $random_tag = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
            $zip_file = $xp_data_dir . "/media-$random_tag.zip";
            // create zip file
            $zip = new ZipArchive();
            $zip->open($zip_file, ZipArchive::CREATE);
        }
        // else get the last file
        else {
            $zip_file = $files[count($files) - 1];
            $zip = new ZipArchive();
            $zip->open($zip_file);
        }

        // FIXME: can be dangerous
        // needs more security
        // block php files
        // and don't overwrite existing files

        // move all uploaded files to xp-data dir
        $files = $_FILES;
        foreach ($files as $file) {
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];
            $name = preg_replace('/[^a-z0-9-_\.]/i', '', $name);
            $name = strtolower($name);
            // $dest = "$xp_media_dir/$name";
            // move_uploaded_file($tmp_name, $dest);

            // if $zip_file exists add file to zip
            if ($zip) {
                $zip->addFile($tmp_name, $name);
            }
        }

        // close zip file
        if ($zip) {
            $zip->close();
        }
        
        return "admin test ($date)";
    }
}