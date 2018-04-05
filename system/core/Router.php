<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

class EVA_Router {

	public $config;
	public $routes = [];
	public $class = '';
	public $method = 'index';
	public $directory;
	public $default_controller;

	public function __construct($routing = null){
        $this->config =& load_class('Config', 'core');
        $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$this->uri = trim(str_replace($this->config->item("base_url"), "", $actual_link), "/");
        $this->_set_routing();
	} // func

	// --------------------------------------------------------------------

	/**
	 * Set route mapping
	 */
	protected function _set_routing(){

		if(count($this->routes)<1 and file_exists(CONFIG_PATH.'routes.php')){
            require_once(CONFIG_PATH.'routes.php');
            $this->routes = $route;
        }else{ die('Unable to locate the routes.php file'); }
        $this->_parse_routes();
	} // func

    protected function _parse_routes(){
        if($this->uri!=""){
            foreach($this->routes as $key=>$value){ // перебираем все роуты из файлика config/routes
                $key = str_replace([':any', ':num'], ['[^/]+', '[0-9]+'], $key); // заменяем в ключе плейсхолдеры на регэкспы
                if (preg_match('#^'.$key.'$#', $this->uri, $matches)){ // если находим соответствие урла какому либо роуту то
                    if (strpos($value, '$') !== false and strpos($key, '(') !== false){ // если в значении роута есть плейсхолдеры для переменных
                        $value = preg_replace('#^'.$key.'$#', $value, $this->uri); // подставляем эти значения в валуе
                    }
                    $this->_set_request(explode('/', $value)); // роут найден, передаем обработанный валуе дальше
                    return;
                }
            } // foreach
            $this->_set_request(explode('/', $this->uri)); // если сюда дошли - знач роут не найден => передаем ури в надежде что есть такой контроллер / метод
        }else{
            $this->_set_request(explode('/', $this->routes['default_controller'])); // ури пустой - передаем дефолтный контроллер
        }
    } // func


    private function _set_request($segments){
        $segments = $this->_validate_request($segments); // получаем путь до контроллера / имя контроллера / имя метода, если они есть
        $this->class = str_replace(["/", "."], "", $segments[0]);
        $this->method = (isset($segments[1]))?str_replace(["/", "."], "", $segments[1]):"index";
        $this->segments = $segments;
    } // func


    private function _validate_request($segments){
        $c = count($segments);
        $directory_override = (isset($this->directory)); // directory_override - содержит путь до контроллера (он же может быть в подпапках)
        while($c-- > 0){
            // проверяем - есть ли такая директория / есть ли в ней контроллер
            $test = $this->directory.ucfirst($segments[0]);
            if($directory_override===false
                and !file_exists(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$test.".php")
                and is_dir(CONTROLLERS_PATH.DIRECTORY_SEPARATOR.$test)
            ){
                // если НЕ была запомнена какая либо директория до этого то и при этом существует такая директория и НЕ существует такого файла
                $this->set_directory(array_shift($segments), true);  // добавляем директорию в путь до контроллера, продолжаем поиск контроллера
                continue;
            }
            return $segments;
        } // while
        return $segments;
    } // func


    public function set_directory($dir, $append = false){
        if ($append !== true or empty($this->directory)){
            $this->directory = str_replace('.','',trim(ucfirst($dir),'/')).DIRECTORY_SEPARATOR;
        }else{
            $this->directory .= str_replace('.','',trim(ucfirst($dir),'/')).DIRECTORY_SEPARATOR;
        }
    } // func


} // class
