<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 03/05/16
 * Time: 00:15
 */

namespace Tests\Molecular\Routes;


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
}
