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

    public $response;
    public $request;
    public $route;
    public $container;
    public $cache;
    public $inject;
    public static $instance;

    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->route = new RouteDispacher();
        $this->request = new Request();
        $this->response = new Response();
        $this->container = new ServiceContainer();
        $cache = new CacheControler();
        $this->cache = $cache->getHandle();
        $this->inject = new Resolve();
        self::$instance = $this;
    }

    public function run()
    {
        $this->response = $this->route->run();
    }


    /**
     * @return string
     */
    public function getResponse()
    {
        return $this->response->getResponseContent();
    }
}