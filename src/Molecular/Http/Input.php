<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 01:45
 */

namespace Molecular\Http;


class Input
{

    public function get($value,$default = null){
        if(isset($_GET[$value])) return $_GET[$value];
        if(isset($_POST[$value])) return $_POST[$value];
        return $default;
    }

    public function post($value,$default = null){
        if(isset($_POST[$value])) return $_POST[$value];
        return $default;
    }

    public function cookie($value,$default = null){
        return isset($_COOKIE[$value]) ? $_COOKIE[$value] : $default;
    }

    public function json(){
        return json_decode($this->getPHPInput());
    }

    public function stream(){
        return $this->getPHPInput();
    }

    private function getPHPInput(){
        return file_get_contents('php://input');
    }
}