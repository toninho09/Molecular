<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 02/05/16
 * Time: 19:20
 */

namespace Molecular\Routes;

use Molecular\Helper\Callbacks\Call;
use Molecular\Injection\Resolve;
use Molecular\Routes\Middleware\Middleware;
use Molecular\Routes\Middleware\RouteBaseMiddleware;

class Route
{

    private $function = null;
    private $route = null;
    private $call = null;
    private $name = '';
    private $method = '';
    private $middlewares = [];
    
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
        $this->resolve = new Resolve();
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

    /**
     * @return bool
     */
    public function isValidMethod(){
        if($this->method == strtolower('any')) return true;
        return $this->method == $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return bool
     */
    public function isRouteValid(){
        if(preg_match("/^[\/]?".$this->route."$/",$_SERVER['REQUEST_URI'],$match)){
            return true;
        }
        return false;
    }

    public function getMiddleware(){
        if(!$this->haveRouteBaseMiddlewareInMiddlewareList()) {
            $middleware = new RouteBaseMiddleware();
            $middleware->setRoute($this);
            $this->addMiddleware($middleware);
        }
        return $this->middlewares;
    }

    private function haveRouteBaseMiddlewareInMiddlewareList(){
        foreach ($this->middlewares as $md){
            if($md instanceof RouteBaseMiddleware){
                /** @var RouteBaseMiddleware $md */
                if($md->getRoute() === $this){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run(){
        if(!$this->isRouteValid()) throw new \Exception("This Route is not valid to call");
        $buffer = '';
        $buffer .= $this->call->runFunction($this->function,$this->getParams());
        return $buffer;
    }

    /**
     * @return mixed
     */
    public function getParams(){
        preg_match("/^".$this->route."$/",$_SERVER['REQUEST_URI'],$match);
        unset($match[0]);
        return $match;
    }

    /**
     * @param $name
     * @return mixed
     */
    private function putRegex($name){
        return preg_replace(['/{\w+}/','/\/{\w+\?}/','/\\//','/\//'],['(\w+)','(\/\w+)?','/','\\/'],$name);
    }


    /**
     * @param array $middlewares
     */
    public function setMiddlewares($middlewares)
    {
        $this->middlewares = $middlewares;
    }

    public function addMiddleware($middleware){
        if(is_array($middleware)){
            $this->setMiddlewares(array_merge($this->middlewares,$middleware));
        }else{
            $this->middlewares[] = $middleware;
        }
    }

}