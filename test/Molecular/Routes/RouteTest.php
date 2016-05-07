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

    public function testFilter(){
        $route = new Route('test','POST',function(){
            return '2';
        });
        $route->getFilter()->setFilter('before',function(){
            return '1';
        });
        $route->getFilter()->setFilter('after',function(){
           return '3';
        });

        $_SERVER['REQUEST_URI'] = '/test';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertTrue($route->isRouteValid(),"Metodo Valido Com filtros");

        $this->assertEquals('123',$route->run(),"verifica o retorno dos filtros");
    }
}
