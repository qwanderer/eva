<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

class EVA_Loader {


	public function __construct($routing = null){
		$this->config =& load_class('Config', 'core');
		$this->EVA = & get_instance();
	} // func

	public function initialize(){

		if(file_exists(APPLICATION_PATH.'config/autoload.php')){ require_once(APPLICATION_PATH.'config/autoload.php');}
		if(!isset($autoload)){ return false; }

		if (isset($autoload['libraries']) && count($autoload['libraries']) > 0){$this->library($autoload['libraries']);}
	} // func


	public function library($lib_arr){
		foreach($lib_arr as $lib_path=>$prop_name){
			$path = trim($lib_path, "/");
			$temp = explode("/", $lib_path);
			$class_name = end($temp);


			if(!class_exists($class_name, false)){
				if(!isset($this->EVA->$prop_name)){
					if(file_exists(LIBRARIES_PATH.$path.".php")){
						require_once(LIBRARIES_PATH.$path.".php");
						$this->EVA->$prop_name = load_class($lib_path, "libraries", null, $prop_name);
					}else{ die('Unable to locate the specified class: '.LIBRARIES_PATH.$path.".php"); }
				}
			} // class_exists
		} // foreach
	} // func

} // class
