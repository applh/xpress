<?php

class xpi_admin
{
    static function script ()
    {
        // isolate WP code for later separation
        $root_plugin_dir = WP_PLUGIN_DIR;

        $res = "";
        // date
        $date = date("ymd-His");
        // plugin xp-data dir
        $xp_data_dir = "$root_plugin_dir/xpress-data";

        // FIXME: can be dangerous
        // needs more security
        // block php files
        // and don't overwrite existing files
        $res = "admin script ($date)";

        // store each file in different zip file 
        // depending on its file extension
        $ext_zips = [];

        // move all uploaded files to xp-data dir
        $files = $_FILES;
        foreach ($files as $file) {
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];
            // sanitize name (filename and extension)
            $name = preg_replace('/[^a-z0-9-_\.]/i', '', $name);
            $name = strtolower($name);
            // get extension
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            // if extension is not allowed
            if (in_array($ext, ["zip"])) {
                // don't zip zip files
                // special processing
            }
            else {
                // check if zip file for this extension exists
                $zip = $ext_zips[$ext] ?? null;
                // if not create it
                if ($zip == null) {
                    // check if zip file exists with name media-%RANDOM-MD5%.zip
                    $files = glob($xp_data_dir . "/media-$ext-*.zip");
                    // if no files found create a new one with name media-%RANDOM-MD5%.zip
                    if (count($files) == 0) {
                        $random_tag = md5(password_hash(uniqid(), PASSWORD_DEFAULT));
                        $zip_file = $xp_data_dir . "/media-$ext-$random_tag.zip";
                    }
                    else {
                        // get the last file found
                        $zip_file = $files[count($files) - 1];
                    }
                    // header debug
                    // header("X-Xp-Debug-script: $zip_file");
                    // create zip file
                    $zip = new ZipArchive();
                    $zip->open($zip_file, ZipArchive::CREATE);

                    $ext_zips[$ext] = $zip;
                }

                // $dest = "$xp_media_dir/$name";
                // move_uploaded_file($tmp_name, $dest);

                // if $zip_file exists add file to zip
                if ($zip) {
                    $zip->addFile($tmp_name, $name);
                }

            }
        }

        // close all zip files
        foreach ($ext_zips as $zip) {
            $zip->close();
        }
        
        return $res;
    }

    static function zip_list ()
    {
        // isolate WP code for later separation
        $root_plugin_dir = WP_PLUGIN_DIR;

        $ext = $_REQUEST['ext'] ?? "webp";

        // read the list of files in media zip file
        $xp_data_dir = "$root_plugin_dir/xpress-data";
        $search = "$xp_data_dir/media-$ext-*.zip";
        // debug header
        // header("X-Xp-Debug-zip_list: $search");
        $files = glob($search);
        $list = [];
        if (!empty($files)) {
            $zip_file = $files[count($files) - 1];
            // debug header
            // header("X-Xp-Debug-zip_list: $zip_file");

            $zip = new ZipArchive();
            $zip->open($zip_file);
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $list[] = $zip->getNameIndex($i);
            }
            $zip->close();    
        }
        return $list;
    }
}