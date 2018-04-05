<?php
chdir(__DIR__);
ini_set('default_charset', "UTF-8");
//error_reporting(E_ALL);
//ini_set("display_errors", 1);

define('EVA_PATH',           dirname(__FILE__).DIRECTORY_SEPARATOR);
define('APPLICATION_PATH',   EVA_PATH."application".DIRECTORY_SEPARATOR);
define('SYSTEM_PATH',        EVA_PATH."system".DIRECTORY_SEPARATOR);
define('CORE_PATH',          SYSTEM_PATH."core".DIRECTORY_SEPARATOR);
define('CONFIG_PATH',        APPLICATION_PATH."config".DIRECTORY_SEPARATOR);
define('CONTROLLERS_PATH',   APPLICATION_PATH."controllers".DIRECTORY_SEPARATOR);
define('LIBRARIES_PATH',     APPLICATION_PATH."libraries".DIRECTORY_SEPARATOR);
define('VIEWS_PATH',         APPLICATION_PATH."views".DIRECTORY_SEPARATOR);
define('EVA_VERSION',        "0.0.3");

require_once(CORE_PATH.'Common.php');



$CFG =& load_class('Config', 'core');
$RTR =& load_class('Router', 'core');
require_once SYSTEM_PATH.'core/Controller.php';

function &get_instance(){
    return EVA_Controller::get_instance();
}

if($db_conf = $CFG->item("database")){
    require_once(SYSTEM_PATH."/database/DB.php");
} // load DB

// TODO add helpers loader

$e404 = FALSE;
$class = ucfirst($RTR->class);
$method = $RTR->method;
$params = (isset($RTR->segments) and is_array($RTR->segments) and count($RTR->segments)>=2)?array_slice($RTR->segments, 2):[];

$e404=false;
if ($class === NULL or !file_exists(CONTROLLERS_PATH.$RTR->directory.$class.'.php')){
    $e404 = true; $msg = __LINE__;
}else{
    require_once(CONTROLLERS_PATH.$RTR->directory.$class.'.php');
    if (!class_exists($class,false)){
        $e404 = true; $msg = __LINE__;
    }elseif (!method_exists($class, $method)){
        $e404 = true; $msg = __LINE__;
    }elseif (!is_callable([$class, $method])){
        $e404 = true; $msg = __LINE__;
    } // if
} // if

if($e404){
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 page not found ($msg)</h1>";
    echo "The page that you have requested could not be found.";
    die;
} // if 404

$Client_controller = new $class();
call_user_func_array([&$Client_controller, $method], $params);