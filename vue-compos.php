<?php

header('Content-Type: application/javascript');

// get name
$name = $_REQUEST['name'] ?? '';

include __DIR__ . "/templates/vue-component.php";
