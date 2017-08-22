<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 03/05/16
 * Time: 23:41
 */

namespace Tests\Molecular\Routes;

use Molecular\Routes\RouteDispacher;

class RouteDispacherTest extends \PHPUnit_Framework_TestCase
{
    private $dispacher;

    public function __construct()
    {
        $this->dispacher = new RouteDispacher();
    }

    private function setHeaders($method, $uri)
    {
        $_SERVER['REQUEST_METHOD'] = $method;
        $_SERVER['REQUEST_URI'] = $uri;
    }

    public function testRouteWithFunction()
    {
        $this->setHeaders('POST', '/test');

        $this->dispacher->post('test', function () {
            return 'ok';
        });

        $this->assertEquals('ok', $this->dispacher->run()->getResponseContent(), 'Verificando Se o dipacher retorna a rota');
    }

    public function testTwoRoutes()
    {
        $this->setHeaders('POST', '/test');

        $this->dispacher->post('test', function () {
            return 'ok';
        });

        $this->dispacher->post('outraRota', function () {
            return 'outra';
        });

        $this->assertEquals('ok', $this->dispacher->run()->getResponseContent(), 'Verificando Se com mais de uma rota ainda chama a certa');
    }

    public function testSameRouteWithDifferentMethods()
    {
        $this->dispacher->post('test', function () {
            return 'post';
        });

        $this->dispacher->get('test', function () {
            return 'get';
        });

        $uri = '/test';

        $this->setHeaders('POST', $uri);
        $this->assertEquals('post', $this->dispacher->run()->getResponseContent(), 'Verifica se está considerando o methodo para encontrar a rota');

        $this->setHeaders('GET', $uri);
        $this->assertEquals('get', $this->dispacher->run()->getResponseContent(), 'Verifica se está considerando o methodo para encontrar a rota');
    }

    public function testRouteThatDoesNotExist()
    {
        $dispacher = new RouteDispacher();

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/home';

        $this->setExpectedException('Exception');

        $dispacher->run();
    }

    public function testeRouteWithMiddleware(){

        $this->dispacher->post('test', function () {
            return 'post';
        },['middleware' => TesteMiddleware::class]);
        $uri = '/test';
        $this->setHeaders('POST', $uri);

        $this->assertEquals('test1posttest2', $this->dispacher->run()->getResponseContent(), 'Verifica se está considerando o methodo para encontrar a rota');

    }

    public function testeRouteWithManyMiddleware(){

        $this->dispacher->post('test', function () {
            return 'post';
        },['middleware' => [FooMiddleware::class,TesteMiddleware::class]]);
        $uri = '/test';
        $this->setHeaders('POST', $uri);

        $this->assertEquals('footest1posttest2bar', $this->dispacher->run()->getResponseContent(), 'Verifica se está considerando o methodo para encontrar a rota');

    }

}

