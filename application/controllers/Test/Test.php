<?php
class Test extends CI_Controller{

    public function asd(){
        d("asd");

        $db_data = $this->db->limit(1)->get("p_content")->result_array();
        dd($db_data);

    }

} // class