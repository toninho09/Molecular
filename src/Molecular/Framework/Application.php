<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 00:43
 */

namespace Molecular\Framework;


use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Routes\RouteDispacher;

class Application
{

    public $response;
    public $request;
    private $moleculeDispancer;
    public $route;
    /**
     * Application constructor.
     */
    public function __construct()
    {
        $this->moleculeDispancer = [];
        $this->route = new RouteDispacher();
        $this->request = new Request();
        $this->response = new Response();
    }

    public function addMolecule(AbstractMolecule $molecule){
        $molecule->boot();
        $molecule->register();
        $this->{$molecule->getName()} = &$molecule->getInstance();
        $this->moleculeDispancer[$molecule->getName()] = $molecule;
    }


    public function run(){
        $this->response->setResponseContent($this->route->run());
        foreach ($this->moleculeDispancer as $key => $value){
            $value->run();
        }
    }

    public function end(){
        foreach ($this->moleculeDispancer as $key => $value){
            $value->end();
        }
    }

    public function getResponse(){
        return $this->response->getResponseContent();
    }
}