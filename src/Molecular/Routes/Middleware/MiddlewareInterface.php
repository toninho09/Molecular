<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 22/08/17
 * Time: 18:03
 */

namespace Molecular\Routes\Middleware;


use Molecular\Http\Request;
use Molecular\Http\Response;

interface MiddlewareInterface
{
    public function handle(Request $request, Response $response);
    public function setNextMiddleware(MiddlewareInterface $next);
    public function next(Request $request, Response $response);
    public function getResponse();
}
