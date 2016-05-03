<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 02/05/16
 * Time: 20:57
 */

namespace Molecular\Routes;


use Molecular\Helper\Callbacks\Call;

class Filter
{
    private $filters = [];
    private $call = null;

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        $this->filters = [];
        $this->call = new Call();
    }

    public function setFilter($name, $callback)
    {
        $this->filters[$name] = $callback;
    }

    public function run($name)
    {
        if (!$this->exists($name)) throw new \Exception("Filter $name is not defined");
        return $this->call($this->filters[$name]);
    }

    public function exists($name)
    {
        return isset($this->filters[$name]);
    }

    public function setArrayFilter(array $filters)
    {
        foreach ($filters as $key => $value) {
            $this->setFilter($key, $value);
        }
    }


}