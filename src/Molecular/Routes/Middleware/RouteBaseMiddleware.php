<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 22/08/17
 * Time: 18:47
 */

namespace Molecular\Routes\Middleware;


use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Routes\Route;

class RouteBaseMiddleware extends Middleware
{

    /** @var Route */
    private $route;

    /**
     * @return Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param Route $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    public function handle(Request $request, Response $response)
    {

        $response->setResponseContent($this->route->run());
        $this->next($request,$response);
    }
}