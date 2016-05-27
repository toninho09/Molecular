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

    /**
     * @param $name
     * @param $callback
     * @param array $params
     * @param bool $singleton
     */
    public function set($name, $callback, $params = [], $singleton = false){
        $service = new Service();
        $service->set($callback,$params);
        $service->setSingleton($singleton);
        $this->services[$name] = $service;
    }

    /**
     * @param $name
     * @param $singleton
     * @throws \Exception
     */
    public function setSingleton($name, $singleton){
        $this->get($name)->setSingleton($singleton);
    }

    /**
     * @param $name
     * @return bool
     */
    public function has($name){
        return !empty($this->services[$name]);
    }

    /**
     * @param $name
     * @param null $default
     * @return Service
     * @throws \Exception
     */
    public function get($name, $default = null){
        if(!$this->has($name)) {
            if(!empty($default)){
                return $default;
            }
            throw new \Exception('Service Not Found');
        }
        return $this->services[$name]->get();
    }


}