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
    private $prefix;

    public function __construct()
    {
        $this->prefix = '';
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

    public function getFilters()
    {
        return $this->filters;
    }

    public function post($name, $function, $params = [])
    {
        $this->registerRoute('POST', $name, $function, $params);
    }

    public function get($name, $function, $params = [])
    {
        $this->registerRoute('GET', $name, $function, $params);
    }

    public function put($name, $function, $params = [])
    {
        $this->registerRoute('PUT', $name, $function, $params);
    }

    public function delete($name, $function, $params = [])
    {
        $this->registerRoute('DELETE', $name, $function, $params);
    }

    public function option($name, $function, $params = [])
    {
        $this->registerRoute('OPTION', $name, $function, $params);
    }

    public function path($name, $function, $params = [])
    {
        $this->registerRoute('PATH', $name, $function, $params);
    }

    public function head($name, $function, $params = [])
    {
        $this->registerRoute('HEAD', $name, $function, $params);
    }

    public function any($name, $function, $params = [])
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

    public function custom($method, $name, $function, $params = [])
    {
        if (is_array($method)) {
            foreach ($method as $value) {
                $this->registerRoute($value, $name, $function, $params);
            }
        } else {
            $this->registerRoute($method, $name, $function, $params);
        }
    }

    private function fixPrefix($prefix)
    {
        if (substr($this->prefix, -1) != '/' && $prefix{0} != '/') {
            $prefix = '/' . $prefix;
        }
        return $prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $this->fixPrefix($prefix);
    }

    public function group($nameGroup, $callback, $params = [])
    {
        $dispacher = new RouteDispacher();
        $dispacher->setPrefix($this->fixPrefix($nameGroup));
        $callback($dispacher);
        $routes = $dispacher->getRoutes();
        foreach ($routes as $keyRoute => $valueRoute) {
            if (!empty($params['filters'])) $valueRoute->getFilter()->setArrayFilter($params['filters']);
            $this->routes[] = $valueRoute;
            unset($routes[$keyRoute]);
        }
    }

    public function run()
    {
        if ($this->filters->exists('before')) $this->filters->run('before');
        $buffer = '';
        foreach ($this->routes as $route) {
            if ($route->isValidMethod() && $route->isRouteValid()) {
                $buffer = $route->run();
                if ($route->next()) continue;
                break;
            }
        }
        if ($this->filters->exists('after')) $this->filters->run('after');
        return $buffer;
    }

    public function getRouteByName($name)
    {
        foreach ($this->routes as $route) {
            if ($name == $route->getName()) return $route;
        }
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    private function registerRoute($method, $name, $function, $params = [])
    {
        if (substr($name, 1) == '/') {
            str_split($name, 1);
        }
        if ($this->prefix != '' && $this->prefix{0} != '/') {
            $this->prefix = '/' . $this->prefix;
        }
        if ($this->prefix != '' && $this->prefix{strlen($this->prefix) - 1} != '/') {
            $this->prefix .= '/';
        }
        if ($this->prefix == '' && $name == '') {
            $this->prefix = '/';
        }

        $route = new Route($this->prefix . $name, $method, $function);
        if (isset($params['as'])) $route->setName($params['as']);
        if (isset($params['filters'])) $route->getFilter()->setArrayFilter($params['filters']);
        if (isset($params['next'])) $route->setNext();
        $this->routes[] = $route;
    }
}