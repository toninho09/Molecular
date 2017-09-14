<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 00:43
 */

namespace Molecular\Framework;


use Molecular\Cache\CacheControler;
use Molecular\Container\ServiceContainer;
use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Injection\Resolve;
use Molecular\Routes\RouteDispacher;

class Application
{

    private $response;
    private $request;
    private $route;
    private $container;
    private $cache;
    private $inject;
    public static $instance;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        self::$instance = $this;
    }

    public function run()
    {
        $this->response = $this->getRoute()->run();
    }

    /**
     * @return string
     */
    public function getResponseContent()
    {
        return $this->getResponse()->getResponseContent();
    }

    public function getResponse(){
        if(!$this->response) {
            $this->response = new Response();
        }
        return $this->response;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if(!$this->request){
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     * @return RouteDispacher
     */
    public function getRoute()
    {
        if(!$this->route){
            $this->route = new RouteDispacher();
        }
        return $this->route;
    }

    /**
     * @return ServiceContainer
     */
    public function getContainer()
    {
        if(!$this->container){
            $this->container = new ServiceContainer();
        }
        return $this->container;
    }

    /**
     * @return \Molecular\Cache\CacheHandle|null
     */
    public function getCache()
    {
        if(!$this->cache){
            $cache = new CacheControler();
            $this->cache = $cache->getHandle();
        }
        return $this->cache;
    }

    /**
     * @return Resolve
     */
    public function getInject()
    {
        if(!$this->inject){
            $this->inject = new Resolve();
        }
        return $this->inject;
    }

}