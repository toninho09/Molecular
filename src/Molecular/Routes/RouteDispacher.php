<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 02/05/16
 * Time: 19:19
 */

namespace Molecular\Routes;


class RouteDispacher
{

    private $routes = [];
    private $group;
    private $notFound;
    private $next;
    private $filters;

    public function __construct()
    {
        $this->group = '';
        $this->next = false;
        $this->filters = new Filter();
    }

    public function after($function)
    {
        $this->filters->setFilter('after', $function);
    }

    public function before($function)
    {
        $this->filters->setFilter('before', $function);
    }

    public function filter($name, $function)
    {
        $this->filters->setFilter($name, $function);
    }

    public function post($name, $function)
    {
        $this->registerRoute('POST', $name, $function);
    }

    public function get($name, $function)
    {
        $this->registerRoute('GET', $name, $function);
    }

    public function put($name, $function)
    {
        $this->registerRoute('PUT', $name, $function);
    }

    public function delete($name, $function)
    {
        $this->registerRoute('DELETE', $name, $function);
    }

    public function option($name, $function)
    {
        $this->registerRoute('OPTION', $name, $function);
    }

    public function path($name, $function)
    {
        $this->registerRoute('PATH', $name, $function);
    }

    public function head($name, $function)
    {
        $this->registerRoute('HEAD', $name, $function);
    }

    public function any($name, $function)
    {
        $this->registerRoute($_SERVER['REQUEST_METHOD'], $name, $function);
    }

    public function setNotFound($function)
    {
        $this->notFound = $function;
    }

    public function next()
    {
        $this->next = true;
    }

    public function custom($method, $name, $function)
    {
        if (is_array($method)) {
            foreach ($method as $value) {
                $this->registerRoute($value, $name, $function);
            }
        } else {
            $this->registerRoute($method, $name, $function);
        }
    }

    public function group($nameGroup, $callback)
    {
        $oldGroup = $this->group;
        $this->group .= $nameGroup;
        $callback();
        $this->group = $oldGroup;
    }

    public function run()
    {
        $this->filters->run('before');
        $buffer = '';
        foreach ($this->routes as $route) {
            if ($route->isValidMethod() && $route->isRouteValid()) {
                $buffer = $route->run();
                if($route->next()) continue;
                break;
            }
        }
        $this->filters->run('after');
        return $buffer;
    }

    public function getRouteByName($name){
        foreach ($this->routes as $route){
            if($name == $route->getName()) return $route;
        }
    }

    private function registerRoute($method, $name, $function, $params = [])
    {
        if (($name == '' && $this->group == '') ||
            ($name == '' && $this->group{strlen($this->group)} != '/') ||
            ($name{0} == '' && $this->group == '')
        ) {
            $name = '/' . $name;
        }
        if ($this->group != '' && $this->group{0} != '/') {
            $this->group = '/' . $this->group;
        }
        $route = new Route($this->putRegex($this->group . $name), $method, $function);
        if (isset($params['as'])) $route->setName($params['as']);
        if (isset($params['filters'])) $route->getFilter()->setArrayFilter($params['filters']);
        if (isset($params['next'])) $route->setNext();
        $this->routes[] = $route;
    }
}