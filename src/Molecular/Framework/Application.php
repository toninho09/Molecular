<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 00:43
 */

namespace Molecular\Framework;


use Molecular\Container\ServiceContainer;
use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Routes\RouteDispacher;

class Application
{

    public $response;
    public $request;
    private $moleculeDispancer;
    public $route;
    public $container;
    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->moleculeDispancer = [];
        $this->route = new RouteDispacher();
        $this->request = new Request();
        $this->response = new Response();
        $this->container = new ServiceContainer();
    }

    public function addMolecule(AbstractMolecule $molecule){
        $molecule->boot();
        $molecule->register();
        $this->{$molecule->getName()} = &$molecule->getInstance();
        $this->moleculeDispancer[$molecule->getName()] = $molecule;
    }


    public function run(){
        $this->response->setResponseContent($this->route->run());
        foreach ($this->moleculeDispancer as $value){
            $value->run();
        }
    }

    public function end(){
        foreach ($this->moleculeDispancer as $value){
            $value->end();
        }
    }

    /**
     * @return string
     */
    public function getResponse(){
        return $this->response->getResponseContent();
    }
}