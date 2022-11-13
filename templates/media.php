<?php

// FIXME
// TEMPLATE SHOULD ONLY BE ACTIVE WITH URI STARTING WITH /@/ 

// get $uri
$uri = $_SERVER["REQUEST_URI"];
// parse url $uri
extract(parse_url($uri));
// get $path
$path ??= "";
// parse path $path
extract(pathinfo($path));
// get dirnames
$dirname ??= "";
$dirname = trim($dirname, "/");
$filename ??= "";
$extension ??= "";

$now = date("Y-m-d H:i:s");
if ($dirname == "@/media") {

    $search = "$filename.$extension";
    // search in media zip file if exists
    $xp_data_dir = WP_PLUGIN_DIR . "/xpress-data";
    $zip_search = "$xp_data_dir/media-*.zip";
    $zip_files = glob($zip_search);
    $zip_file = $zip_files[0] ?? null;
    $file_data = "";
    $extension_ok = ["webp", "jpg", "jpeg", "png", "gif", "svg"];

    if ($zip_file && in_array($extension, $extension_ok)) {
        // get content from zip file
        $path_zip = "zip://$zip_file#$search";
        $file_data = @file_get_contents($path_zip);
        if ($file_data !== false) {
            status_header(200);
            // FIXME: get mime type
            $mime_type = "image/$extension";
            // set content type
            header("Content-Type: $mime_type");
            // set content length
            header("Content-Length: " . strlen($file_data));
            // set content
            echo $file_data;
        }
        else {
            echo "Media loader ($now)($path_zip)($filename)($extension)";

        }
    }
    if (!$file_data) {
        // special media loader
        echo "Media loader ($now)($uri)($filename)($extension)";
    }
}
else {
    // normal 404
    echo "Hello World ($now)";
}

