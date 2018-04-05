<?php
defined('EVA_PATH') OR exit('No direct script access allowed cc');

class EVA_Controller {
	private static $instance;
	public function __construct(){
		self::$instance =& $this;


        // все что зашгрузили до этого  в ручную так же дублируем в этот класс чтобы получился один "супер" объект
        foreach (is_loaded() as $var => $class){
            $this->$var =& load_class($class);
        }

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();


	} // func

	public static function &get_instance(){
		return self::$instance;
	} // func

} // class
