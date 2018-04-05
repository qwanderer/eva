<?php

class Testcontr extends CI_Controller {

    public function a1($param1, $param2){
        echo "<br><br><br><br>";
        echo "param1 = $param1<br>";
        echo "param2 = $param2";
    }

    public function index(){
        echo "index";
    }

} // class
