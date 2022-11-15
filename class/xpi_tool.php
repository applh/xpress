<?php

class xpi_tool
{
    static function mail ()
    {
        // date
        $date = date("ymd-His");
        $zip = xp_os::media_zip();

        // get params from, to, subject, body from $_POST
        $from = $_POST['from'] ?? "";
        $to = $_POST['to'] ?? "";
        $subject = $_POST['subject'] ?? "";
        $body = $_POST['body'] ?? "";

        // if no from, to, subject, body then return error
        if (!$from || !$to || !$subject || !$body) {
            return "error";
        }

        // TODO: sanitize
        // send email by wp_email
        $headers = ['Content-Type: text/html; charset=UTF-8'];
        $attachments = [];
        $status = wp_mail($to, $subject, $body, $headers, $attachments);

        // store mail data in json format
        $mail_data = [
            'date' => $date,
            'from' => $from,
            'to' => $to,
            'subject' => $subject,
            'body' => $body,
            'status' => $status,
        ];
        $mail_json = json_encode($mail_data, JSON_PRETTY_PRINT);
        // save email in zip
        $zip->addFromString("mail-$date.json", $mail_json);

        // close zip file
        $zip = xp_os::media_zip("close");
        
        return "tool mail ($date)($mail_json)";
    }

    static function update_zip ()
    {
        $plugin_dir = WP_PLUGIN_DIR;

        $res = "";
        $zip_url = "https://github.com/applh/xpress/archive/refs/heads/main.zip";
        $zip_file = "";
        $data_dir = xp_os::get_dir("../xpress-data");

        // take the first uploaded zip file if exists
        if (count($_FILES) > 0) {
            $file = $_FILES[0];
            $tmp_name = $file['tmp_name'];
            $name = $file['name'];
            // sanitize name (filename and extension)
            $name = preg_replace('/[^a-z0-9-_\.]/i', '', $name);
            $name = strtolower($name);
            // get extension
            $ext = pathinfo($name, PATHINFO_EXTENSION);
            // if extension is not allowed
            if (in_array($ext, ["zip"])) {
                // move file to plugin dir
                $prefix = date("ymd-His-") . uniqid();
                $zip_file = "$data_dir/$prefix-$name";
                move_uploaded_file($tmp_name, $zip_file);
                $zip_url = $zip_file;

                // debug header
                header("X-Xp-Debug-update-zip: $zip_file");
            }
        }

        // load zip file from github
        xp_os::unzip_url($zip_url, $data_dir, $plugin_dir);

        if (file_exists($zip_file)) {
            // delete zip file
            // unlink($zip_file);
        }

        return $res;
    }
}