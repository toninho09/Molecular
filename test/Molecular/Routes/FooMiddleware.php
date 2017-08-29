<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 22/08/17
 * Time: 19:54
 */

namespace Tests\Molecular\Routes;


use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Routes\Middleware\Middleware;

class FooMiddleware extends Middleware
{

    public function handle(Request $request, Response $response)
    {
        $response->setResponseContent('foo');
        $this->next($request,$response);
        $response->setResponseContent('bar');
    }
}