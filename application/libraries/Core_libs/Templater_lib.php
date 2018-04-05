<?php

class Templater_lib{




    public function renderOutput($template, $data=[], $return_flag=1){
        $data['content'] = (isset($data['content']))?$data['content']:"";
        if(file_exists(VIEWS_PATH.$template.'.php')){
            ob_start();
            require_once(VIEWS_PATH.$template.'.php');
            $return = ob_get_clean();
            if($return_flag==1){
                return $return;
            }else{
                echo $return;
            }
        }else{
            die("template not found: ".$template.'.php');
        }
    } // func




} // class