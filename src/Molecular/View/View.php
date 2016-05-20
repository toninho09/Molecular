<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 20/05/16
 * Time: 00:46
 */

namespace Molecular\View;


class View
{
    private $file;
    private $defaultViewPath;
    private $data;

    function __toString(){
        return $this->render();
    }

    function __construct($file = ''){
        $this->file = $file;
        $this->defaultViewPath = '';
        $this->viewRender = '';
        $this->data = [];
    }

    public function setDefaultViewPath($path){
        $this->defaultViewPath = $path;
    }

    public function setFile($file){
        $this->file = $file;
    }

    public function getDefaultViewPath(){
        return $this->defaultViewPath;
    }

    public function with($name, $value = ''){
        if(empty($name)){
            throw new \Exception("Data is invalid.", 1);
        }
        if(is_array($name)){
            $this->data = array_merge($this->data,$name);
        }else{
            $this->data = array_merge($this->data,[$name=>$value]);
        }
    }

    public function render($file = '' , $data = []){
        if(empty($file)){
            $file = $this->file;
        }
        extract($this->data);
        extract($data);
        ob_start();
        $fileFormat = $this->defaultViewPath.$file;
        if (file_exists($fileFormat)){
            include $fileFormat;
        }else{
            throw new \Exception("File [$fileFormat] Not Found.");
        }
        return ob_get_clean();
    }

}