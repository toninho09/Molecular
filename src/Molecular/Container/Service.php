<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 22:18
 */

namespace Molecular\Container;


use Molecular\Helper\Callbacks\Call;

class Service
{
    private $singleton;
    private $service;
    private $callback;
    private $call;
    private $params;

    /**
     * Service constructor.
     * @param $name
     */
    public function __construct($callback = null,$params = [],$singleton = false)
    {
        $this->callback = $callback;
        $this->singleton = $singleton;
        $this->service = null;
        $this->params = $params;
        $this->call = new Call();
    }

    public function set($callback, $params = []){
        $this->callback = $callback;
        $this->params = $params;
    }

    public function get(){
        if($this->singleton && $this->service != null){
            return $this->service;
        }
        if($this->call->isRunnable($this->callback)) {
            $this->service = $this->call->runFunction($this->callback, $this->params);
        }elseif($this->callback != null){
            $this->service = $this->callback;
        }
        return $this->service;
    }

    public function setSingleton($singleton){
        $this->singleton = $singleton;
    }


}