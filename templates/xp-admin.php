<?php

if (!is_callable("xpress::v")) die();

status_header(200);

$xp_url = xpress::v("plugin_url");
$xpress_api_key = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XP ADMIN</title>
    <style>
        html,
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-size: 16px;
        }

        * {
            box-sizing: border-box;
        }
    </style>
</head>

<body>
</body>

<?php include __DIR__ . "/xp-admin-vue.php" ?>

</html>