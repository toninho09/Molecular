<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 22:18
 */

namespace Molecular\Container;


class ServiceContainer
{
    private $services;

    /**
     * ServiceContainer constructor.
     * @param $services
     */
    public function __construct()
    {
        $this->services = [];
    }

    public function set($name,$callback,$params = [],$singleton = false){
        $service = new Service();
        $service->set($callback,$params);
        $service->setSingleton($singleton);
        $this->services[$name] = $service;
    }

    public function has($name){
        return !empty($this->services[$name]);
    }

    public function get($name,$default = null){
        if(!$this->has($name)) {
            if(!empty($default)){
                return $default;
            }
            throw new \Exception('Service Not Found');
        }
        return $this->services[$name]->get();
    }


}