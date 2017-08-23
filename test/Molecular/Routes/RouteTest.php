<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 03/05/16
 * Time: 00:15
 */

namespace Tests\Molecular\Routes;


use Molecular\Routes\Middleware\RouteBaseMiddleware;
use Molecular\Routes\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    public function testRouteValid(){
        $route = new Route('test','POST',function(){});
        $_SERVER['REQUEST_URI'] = 'test';
        $this->assertTrue($route->isRouteValid(),'Classe de rota reconhece a rota simples');

        $_SERVER['REQUEST_URI'] = '/test';

        $this->assertTrue($route->isRouteValid(),'Classe de rota deve reconhecer mesmo com \\ no começo da rota');
    }
    
    public function testRouteWithParams(){
        $route = new Route('teste/{teste}/{teste2}','any',function(){});
        $_SERVER['REQUEST_URI'] = 'teste/1/2';
        $this->assertTrue($route->isRouteValid(),'Classe de rota reconhece a rota com parametros');

        $_SERVER['REQUEST_URI'] = 'teste/1';

        $this->assertFalse($route->isRouteValid(),'Classe de rota não deve reconhecer a rota com parametros obrigatórios faltando');
    }

    public function testeRouteMiddleware(){
        $route = new Route('test','POST',function(){});
        $middlewares = $route->getMiddleware();
        $this->assertCount(1,$middlewares,'Deve-se criar o middleware padrao para processamento da rota');
        $middleware = array_pop($middlewares);
        $this->assertInstanceOf(RouteBaseMiddleware::class,$middleware,'Deve-se criar o middleware padrao para tratamento de rota');
    }

    public function testeMiddleware(){

        $route = new Route('test','POST',function(){});
        $middleware = new RouteBaseMiddleware();
        $middleware->setRoute($route);
        $this->assertEquals($route, $middleware->getRoute());
    }
}
