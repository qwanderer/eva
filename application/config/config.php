<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

$config['base_url'] = 'http://localhost/eva/';
$config['charset'] = 'UTF-8';
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';
$config['database']=[
    'driver' => "mysqli",
    'host'=>"localhost",
    'user'=>"root",
    'pass'=>"",
    'db_name'=>"luna",
];