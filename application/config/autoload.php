<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

$autoload = [
    'libraries'=> [
        "Test_lib"=>"test_lib",
        "Core_libs/Templater_lib"=>"tmp",
    ], // libraries
    'helper' => [] // helper
];

