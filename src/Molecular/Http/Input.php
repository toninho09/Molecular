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
    private $jsonValues;
    /**
     * Input constructor.
     */
    public function __construct()
    {
        $this->jsonValues = $this->json();
    }

    /**
     * @param $value
     * @param null $default
     * @return string|null
     */
    public function get($value, $default = null){
        if(isset($_GET[$value])) return $_GET[$value];
        if(isset($_POST[$value])) return $_POST[$value];
        if(isset($this->jsonValues[$value])) return $this->jsonValues[$value];
        return $default;
    }

    /**
     * @param $value
     * @param null $default
     * @return mixed|null
     */
    public function post($value, $default = null){
        if(isset($_POST[$value])) return $_POST[$value];
        return $default;
    }

    /**
     * @param $value
     * @param null $default
     * @return mixed|null
     */
    public function cookie($value, $default = null){
        return isset($_COOKIE[$value]) ? $_COOKIE[$value] : $default;
    }

    /**
     * @return mixed
     */
    public function json($assoc = true){
        return json_decode($this->getPHPInput(),$assoc);
    }

    /**
     * @return string
     */
    public function stream(){
        return $this->getPHPInput();
    }

    /**
     * @return string
     */
    private function getPHPInput(){
        return file_get_contents('php://input');
    }
}