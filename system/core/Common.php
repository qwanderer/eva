<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

function d($data=[]){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}
function dd($data=[]){
    d($data);
    die;
}

function et($data, $with_line_numbers=0){
    if(!is_array($data) or !count($data)>0){ return "Table Error - data is not an array"; }
    $arr_keys = getMultiArrayKeys($data);
    $return = "<table border='1' cellpadding='5'>";
    $ln = ($with_line_numbers==1)?"<th>ln</th>":"";
    $headers = "<tr>$ln<th>".implode("</th><th>", $arr_keys)."</th></tr>";
    $table_data = "";$loop=0;
    foreach($data as $row){
        $loop++;
        $ln=($with_line_numbers==1)?"<td>$loop</td>":"";
        $table_data .= "<tr>$ln";
        foreach($arr_keys as $key){
            if(!isset($row[$key])){ $table_data .= "<td></td>";continue; }
            $td_data = (is_array($row[$key])) ? print_r($row[$key],1):$row[$key];
            $table_data .= "<td>".$td_data."</td>";
        }
        $table_data .= "</tr>";
    } // foreach
    echo $return.$headers.$table_data."</table>";
} // func
function etd($data, $with_line_numbers=0){
    et($data, $with_line_numbers);die;
} // func
function getMultiArrayKeys($data) {
    $keys=[];
    foreach($data as $k => $v) {
        is_int($k) OR $keys[]=$k;
        if (is_array($v)){
            $keys = array_merge($keys, getMultiArrayKeys($v));
        }
    }
    return array_unique($keys);
}


/**
 * ф-я работает как синглтон - если запрашиваемый класс не загружен - он инклюдится и делается пометка в статик переменную. Если был загружен - return
 */
function &load_class($class, $directory = 'libraries', $param = NULL, $short_name=""){
    static $_classes = [];

    if (isset($_classes[$class])){ return $_classes[$class]; }

    $name = false;

    // Сначала ищем класс в клиентских либах потом в систем либах
    foreach ([APPLICATION_PATH, SYSTEM_PATH] as $path){
        if (file_exists($path.$directory.DIRECTORY_SEPARATOR.$class.'.php')){

            $last_name = explode("/",$class);
            $name =($directory=="core")?'EVA_'.end($last_name):end($last_name);
            if (class_exists($name, false) === false){
                require_once($path.$directory.DIRECTORY_SEPARATOR.$class.'.php');
            }
            break;
        } // if
    } // foreach

    // НЕ нашли класс ?
    if ($name === false){ die('Unable to locate the specified class: '.$class.'.php  '); }

    if(substr($class, 0, 3)=="DB_"){$class="db";}
    $short_name=($short_name=="")?$class:$short_name;
    is_loaded($short_name);

    $_classes[$short_name] = isset($param)?new $name($param):new $name();

    return $_classes[$short_name];
} // func


/**
 * Хранит все классы, загруженные чере ф-ю load_class()
 */
function &is_loaded($class = ''){
    static $_is_loaded = [];
    if ($class !== ''){ $_is_loaded[strtolower($class)] = $class; }
    return $_is_loaded;
} // func




/**
 * Загружает config.php файл
 */
function &get_config($replace = []){
    static $config;

    if (empty($config)){
        $file_path = CONFIG_PATH.'config.php';
        if (file_exists($file_path)){
            require_once($file_path);
        }else{ die('Unable to locate the config.php file'); }
    }

    // Были ли какие то динамические замены ?
    foreach ($replace as $key => $val){
        $config[$key] = $val;
    }

    return $config;
} // func



/**
 * Возвращает значение из конфигов по ключу
 */
function config_item($item){
    static $_config;

    if (empty($_config)){
        // ф-ю нельзя приравнять к статик переменной на прямую - поэтому массив
        $_config[0] =& get_config();
    }
    return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
} // func




function redirect($uri=''){
    global $config;
    header('Location: '.$config['base_url'].'/'.$uri, TRUE, 302);
    exit;
} // func


function show_404(){
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 page not found</h1>";
    echo "The page that you have requested could not be found.";
    die;
}



