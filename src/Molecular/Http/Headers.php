<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 29/08/17
 * Time: 18:51
 */

namespace Molecular\Http;


class Headers
{
    public function getHeader($key,$default = null){
        $headers = $this->getAllHeader();
        if(!empty($headers[$key])){
            return $headers[$key];
        }
        return $default;
    }

    public function getAllHeader(){
        if (function_exists('getallheaders')) {
            return getallheaders();
        }
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
            if (substr($name, 0, 5) == 'HTTP_')
            {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    public function setHeader($header){
        header($header);
    }
}