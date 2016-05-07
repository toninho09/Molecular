<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 02/05/16
 * Time: 19:20
 */

namespace Molecular\Routes;

use Molecular\Helper\Callbacks\Call;

class Route
{

    private $function = null;
    private $route = null;
    private $call = null;
    private $name = '';
    private $filter = '';
    private $method = '';
    private $next = false;
    
    
    /**
     * Route constructor.
     * @param string $name
     * @param null $method
     * @param null $function
     */
    public function __construct($route,$method, $function)
    {
        $this->method = $method;
        $this->function = $function;
        $this->route = $this->putRegex($route);
        $this->call = new Call();
        $this->filter = new Filter();
    }

    public function setNext(){
        $this->next = true;
    }

    public function next(){
        return $this->next;
    }

    /**
     * @return Filter|string
     */
    public function getFilter()
    {
        return $this->filter;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function isValidMethod(){
        if($this->method == strtolower('any')) return true;
        return $this->method == $_SERVER['REQUEST_METHOD'];
    }

    public function isRouteValid(){
        if(preg_match("/^[\/]?".$this->route."$/",$_SERVER['REQUEST_URI'],$match)){
            return true;
        }
        return false;
    }

    public function run(){
        if(!$this->isRouteValid()) throw new \Exception("This Route is not valid to call");
        $buffer = '';
        if($this->filter->exists('before'))$buffer .= $this->filter->run('before');
        $buffer .= $this->call->runFunction($this->function,$this->getParams());
        if($this->filter->exists('after')) $buffer .= $this->filter->run('after');
        return $buffer;

    }

    public function getParams(){
        preg_match("/^".$this->route."$/",$_SERVER['REQUEST_URI'],$match);
        unset($match[0]);
        return $match;
    }

    private function putRegex($name){
        return preg_replace(['/{\w+}/','/\/{\w+\?}/','/\\//','/\//'],['(\w+)','(\/\w+)?','/','\\/'],$name);
    }

}