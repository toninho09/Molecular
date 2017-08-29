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

    /**
     * @return string
     * @throws \Exception
     */
    function __toString(){
        return $this->render();
    }

    function __construct($file = ''){
        $this->file = $file;
        $this->defaultViewPath = '';
        $this->viewRender = '';
        $this->data = [];
    }

    /**
     * @param $path
     */
    public function setDefaultViewPath($path){
        $this->defaultViewPath = $path;
    }

    /**
     * @param $file
     */
    public function setFile($file){
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function getDefaultViewPath(){
        return $this->defaultViewPath;
    }

    /**
     * @param $name
     * @param string $value
     * @throws \Exception
     */
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

    /**
     * @param string $file
     * @param array $data
     * @return string
     * @throws \Exception
     */
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
            ob_get_clean();
            throw new \Exception("File [$fileFormat] Not Found.");
        }
        return ob_get_clean();
    }

}