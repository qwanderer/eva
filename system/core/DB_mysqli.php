<?php

class EVA_DB_mysqli{

    private $link;

    public function __construct($link){
        $this->link = $link;
    }


    public function sql($sql){
        return mysqli_query($this->link, $sql);
    } // func


    public function mr2array($result){
        $i=0;$ret = [];
        while ($row = $result->fetch_assoc()){
            foreach ($row as $key => $value){
                $ret[$i][$key] = $value;
            }
            $i++;
        }
        return ($ret);
    } // func


    public function result_array($sql){
        return $this->mr2array($this->sql($sql));
    } // func





}