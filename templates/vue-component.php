<?php

// centralize vuejs app components
// route the request to the right component 
// depending on the name

// default component
$vue_template = __DIR__ . "/av-default.php";

// search for the component
$vue_compo = __DIR__ . "/$name.php";
// if file exists, read it
if (file_exists($vue_compo)) {
    $vue_template = $vue_compo;
}

// load the component
if (is_file($vue_template)) {
    include $vue_template;
}
