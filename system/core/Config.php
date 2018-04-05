<?php
defined('EVA_PATH') OR exit('No direct script access allowed');

class EVA_Config {

	public $config = []; // все конфиги (файлы)


	public $is_loaded =	[]; // все конфиги (файлы)


	public $_config_paths =	[APPLICATION_PATH]; // где будут лежать конфиг файлы

	public function __construct(){
		$this->config =& get_config(); // берем главный конф файл
	}


	public function item($item, $index = ''){
		if ($index == ''){ return isset($this->config[$item]) ? $this->config[$item] : NULL; }
		return isset($this->config[$index], $this->config[$index][$item]) ? $this->config[$index][$item] : NULL;
	} // func

} // class