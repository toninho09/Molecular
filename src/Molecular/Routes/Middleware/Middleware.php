<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 22/08/17
 * Time: 17:57
 */

namespace Molecular\Routes\Middleware;


use Molecular\Http\Request;
use Molecular\Http\Response;

abstract class Middleware implements MiddlewareInterface
{
    /** @var \Molecular\Routes\Middleware\MiddlewareInterface**/
    private $nextMiddleware;
    /** @var  Response */
    private $response;
    /** @var  Request */
    private $request;

    abstract public function handle(Request $request, Response $response);

    public function next(Request $request, Response $response)
    {
        $this->response = $response;
        $this->request = $request;
        if($this->nextMiddleware instanceof MiddlewareInterface){
            $this->nextMiddleware->handle($this->request,$this->response);
            $next = $this->nextMiddleware;
        }else{
            $next = $this;
        }
        $this->response = $next->getResponse();
        return $this->response;
    }

    public function setNextMiddleware(MiddlewareInterface $nextMiddleware)
    {
        $this->nextMiddleware = $nextMiddleware;
    }

    public function getResponse()
    {
        return $this->response;
    }
}