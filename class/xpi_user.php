<?php

class xpi_user 
{
    static function mail ()
    {
        // date
        $date = date("ymd-His");
        $zip = xp_os::media_zip("email");

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
        $zip = xp_os::media_zip("email", "close");
        
        return "tool mail ($date)($mail_json)";
    }

    static function test ()
    {
        $now = time();
        $res = "(xpi_user::test $now)";
        return $res;
    }
}