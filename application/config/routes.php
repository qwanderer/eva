<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';

$route['test/contr/a1/(:num)/(:num)'] = "Test/Testcontr/a1/$1/$2";
