<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 22/08/17
 * Time: 19:42
 */

namespace Tests\Molecular\Routes;


use Molecular\Http\Request;
use Molecular\Http\Response;
use Molecular\Routes\Middleware\Middleware;

class TesteMiddleware extends Middleware
{

    public $req;
    public function __construct(Request $req)
    {
        $this->req = $req;
    }

    public function handle(Request $request, Response $response)
    {
        $response->setResponseContent('test1');
        $this->next($request,$response);
        $response->setResponseContent('test2');
    }
}