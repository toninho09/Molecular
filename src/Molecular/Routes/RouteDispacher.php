<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 02/05/16
 * Time: 19:19
 */

namespace Molecular\Routes;


use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Injection\Resolve;
use Molecular\Routes\Middleware\Middleware;

class RouteDispacher
{

    private $routes = [];
    private $notFound;
    private $prefix;
    private $middleware;
    private $resolve;

    public function __construct()
    {
        $this->prefix = '';
        $this->middleware = [];
        $this->resolve = new Resolve();
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
        $this->registerRoute('ANY', $name, $function, $params);
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
        $dispacher->setPrefix($this->prefix . $this->fixPrefix($nameGroup));
        $callback($dispacher);
        $routes = $dispacher->getRoutes();
        foreach ($routes as $keyRoute => $valueRoute) {
            if (!empty($params['middleware'])) $valueRoute->addMiddlewares($params['middleware']);
            $this->routes[] = $valueRoute;
            unset($routes[$keyRoute]);
        }
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function run()
    {
        $buffer = '';

        $matchedRoutes = $this->getMatchedRoutes();

        if (empty($matchedRoutes)) {
            throw new \Exception('Route not found');
        }

        return $this->runMatchedRoutes($matchedRoutes);
    }

    /**
     * @return Route|null
     */
    private function getMatchedRoutes()
    {
        foreach ($this->routes as $route) {
            /** @var Route $route */
            if ($route->isValidMethod() && $route->isRouteValid()) {
                $route->addMiddleware($this->middleware);
                return $route;
            }
        }
        return null;
    }

    /**
     * @param $buffer
     * @param $routes
     * @return Response
     */
    private function runMatchedRoutes(Route $route)
    {

        $midllewares = array_reverse($route->getMiddleware());
        $midlleware = null;

        foreach ($midllewares as $md){
            if(!is_object($md)){
                $md = $this->resolve->resolve($md);
            }
            /** @var Middleware $md */
            if(!$midlleware){
                $midlleware = $md;
            }else{
                $md->setNextMiddleware($midlleware);
                $midlleware = $md;
            }
        }
        $midlleware->handle(new Request(),new Response());
        return $midlleware->getResponse();
    }

    /**
     * @param $name
     * @return Route
     * @throws \Exception
     */
    public function getRouteByName($name)
    {
        foreach ($this->routes as $route) {
            /** @var Route $route */
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
        if ($this->prefix != '' && substr($this->prefix,-1) != '/') {
            $this->prefix .= '/';
        }
        if ($this->prefix == '' && $name == '') {
            $this->prefix = '/';
        }
        if ($name != '' && substr($name,-1) == '/') {
            $name = substr($name,0,-1);
        }

        $route = new Route(preg_replace('/\/+/',"/",$this->prefix.$name), $method, $function);
        if (isset($params['as'])) $route->setName($params['as']);
        if (isset($params['middleware'])) $route->addMiddleware($params['middleware']);
        $this->routes[] = $route;
    }


    public function addMiddleware($class)
    {
        $this->middleware[] = $class;
    }

    /**
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * @param array $middleware
     */
    public function setMiddleware($middleware)
    {
        $this->middleware = $middleware;
    }
}
