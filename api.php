<?php

// return json response
$infos = [];
// time
$infos['time'] = time();
// request
$infos['request'] = $_REQUEST;
// files
$infos['files'] = $_FILES;

// return json response
header('Content-Type: application/json');
echo json_encode($infos);
