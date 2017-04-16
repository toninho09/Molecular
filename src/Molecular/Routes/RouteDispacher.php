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

    /**
     * @param $function
     */
    public function after($function)
    {
        $this->filters->setFilter('after', $function);
    }

    /**
     * @param $function
     */
    public function before($function)
    {
        $this->filters->setFilter('before', $function);
    }

    /**
     * @param $name
     * @param $function
     */
    public function filter($name, $function)
    {
        $this->filters->setFilter($name, $function);
    }

    /**
     * @return Filter
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function post($name, $function, $params = [])
    {
        $this->registerRoute('POST', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function get($name, $function, $params = [])
    {
        $this->registerRoute('GET', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function put($name, $function, $params = [])
    {
        $this->registerRoute('PUT', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function delete($name, $function, $params = [])
    {
        $this->registerRoute('DELETE', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function option($name, $function, $params = [])
    {
        $this->registerRoute('OPTION', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function path($name, $function, $params = [])
    {
        $this->registerRoute('PATH', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function head($name, $function, $params = [])
    {
        $this->registerRoute('HEAD', $name, $function, $params);
    }

    /**
     * @param $name
     * @param $function
     * @param array $params
     */
    public function any($name, $function, $params = [])
    {
        $this->registerRoute($_SERVER['REQUEST_METHOD'], $name, $function, $params);
    }

    /**
     * @param $function
     */
    public function setNotFound($function)
    {
        $this->notFound = $function;
    }

    /**
     *
     */
    public function next()
    {
        $this->next = true;
    }

    /**
     * @param $method
     * @param $name
     * @param $function
     * @param array $params
     */
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

    /**
     * @param $prefix
     * @return string
     */
    private function fixPrefix($prefix)
    {
        if ($prefix != '' && substr($this->prefix, -1) != '/' && $prefix{0} != '/') {
            $prefix = '/' . $prefix;
        }
        return $prefix;
    }

    /**
     * @param $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $this->fixPrefix($prefix);
    }

    /**
     * @param $nameGroup
     * @param $callback
     * @param array $params
     */
    public function group($nameGroup, $callback, $params = [])
    {
        $dispacher = new RouteDispacher();
        $dispacher->setPrefix( $this->prefix . $this->fixPrefix($nameGroup));
        $callback($dispacher);
        $routes = $dispacher->getRoutes();
        foreach ($routes as $keyRoute => $valueRoute) {
            if (!empty($params['filters'])) $valueRoute->getFilter()->setArrayFilter($params['filters']);
            $this->routes[] = $valueRoute;
            unset($routes[$keyRoute]);
        }
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        $buffer = '';

        $matchedRoutes = $this->getMatchedRoutes();

        if (empty($matchedRoutes)) {
            throw new \Exception('Route not found');
        }

        if ($this->filters->exists('before')) $this->filters->run('before');

        $this->runMatchedRoutes($buffer, $matchedRoutes);

        if ($this->filters->exists('after')) $this->filters->run('after');

        return $buffer;
    }

    private function getMatchedRoutes()
    {
        $matchedRoutes = [];

        foreach ($this->routes as $route) {
            if ($route->isValidMethod() && $route->isRouteValid()) {
                $matchedRoutes[] = $route;
                if ($route->next()) continue;
                break;
            }
        }

        return $matchedRoutes;
    }

    private function runMatchedRoutes(&$buffer, $routes)
    {
        foreach ($routes as $route) {
            $buffer = $route->run();
        }
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public function getRouteByName($name)
    {
        foreach ($this->routes as $route) {
            if ($name == $route->getName()) return $route;
        }
        throw new \Exception('Route not found');
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param $method
     * @param $name
     * @param $function
     * @param array $params
     */
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
