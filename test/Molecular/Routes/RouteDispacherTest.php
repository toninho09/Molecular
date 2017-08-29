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

    public function testRouteWithMiddleware()
    {

        $this->dispacher->post('test', function () {
            return 'post';
        }, ['middleware' => TesteMiddleware::class]);
        $uri = '/test';
        $this->setHeaders('POST', $uri);

        $this->assertEquals('test1posttest2', $this->dispacher->run()->getResponseContent(), 'Verifica se está considerando o methodo para encontrar a rota');

    }

    public function testRouteWithManyMiddleware()
    {

        $this->dispacher->post('test', function () {
            return 'post';
        }, ['middleware' => [FooMiddleware::class, TesteMiddleware::class]]);
        $uri = '/test';
        $this->setHeaders('POST', $uri);

        $this->assertEquals('footest1posttest2bar', $this->dispacher->run()->getResponseContent(), 'Verifica se está considerando o methodo para encontrar a rota');
    }

    public function testGroupMiddleware()
    {

        $dispacher = new RouteDispacher();
        $dispacher->get('get', function () {
            return 'get';
        });
        $dispacher->addMiddleware(FooMiddleware::class);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/get';

        $this->assertEquals('foogetbar', $dispacher->run()->getResponseContent());

        $dispacher->addMiddleware(FooMiddleware::class);
        $dispacher->get('foo', function () {
            return 'foo';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/foo';

        $this->assertEquals('foofoofoobarbar', $dispacher->run()->getResponseContent());

        $middlewares = $dispacher->getMiddleware();
        $this->assertEquals([FooMiddleware::class, FooMiddleware::class], $middlewares);

        $dispacher->setMiddleware(FooMiddleware::class);
        $dispacher->get('bar', function () {
            return 'bar';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/bar';

        $this->assertEquals('foobarbar', $dispacher->run()->getResponseContent());

        $dispacher->setMiddleware([FooMiddleware::class]);
        $dispacher->get('bar2', function () {
            return 'bar2';
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/bar2';

        $this->assertEquals('foobar2bar', $dispacher->run()->getResponseContent());
    }

    public function testMethodsTypes()
    {
        $dispacher = new RouteDispacher();
        $dispacher->put('put', function () {
            return 'put';
        });
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['REQUEST_URI'] = '/put';

        $this->assertEquals('put', $dispacher->run()->getResponseContent());

        $dispacher->delete('delete', function () {
            return 'delete';
        });
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['REQUEST_URI'] = '/delete';

        $this->assertEquals('delete', $dispacher->run()->getResponseContent());

        $dispacher->option('option', function () {
            return 'option';
        });
        $_SERVER['REQUEST_METHOD'] = 'OPTION';
        $_SERVER['REQUEST_URI'] = '/option';

        $this->assertEquals('option', $dispacher->run()->getResponseContent());

        $dispacher->path('path', function () {
            return 'path';
        });
        $_SERVER['REQUEST_METHOD'] = 'PATH';
        $_SERVER['REQUEST_URI'] = '/path';

        $this->assertEquals('path', $dispacher->run()->getResponseContent());

        $dispacher->head('head', function () {
            return 'head';
        });
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $_SERVER['REQUEST_URI'] = '/head';

        $this->assertEquals('head', $dispacher->run()->getResponseContent());

        $dispacher->any('any', function () {
            return 'any';
        });
        $_SERVER['REQUEST_URI'] = '/any';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'OPTION';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'PATH';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'HEAD';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'ANY';
        $this->assertEquals('any', $dispacher->run()->getResponseContent());
    }

    public function testCustomRoute()
    {
        $dispacher = new RouteDispacher();

        $dispacher->custom('CUSTOM', 'custom', function () {
            return 'custom';
        });
        $_SERVER['REQUEST_METHOD'] = 'CUSTOM';
        $_SERVER['REQUEST_URI'] = '/custom';

        $this->assertEquals('custom', $dispacher->run()->getResponseContent());

        $dispacher->custom(['CUSTOM1', 'CUSTOM2'], 'custom', function () {
            return 'custom';
        });
        $_SERVER['REQUEST_METHOD'] = 'CUSTOM1';
        $this->assertEquals('custom', $dispacher->run()->getResponseContent());
        $_SERVER['REQUEST_METHOD'] = 'CUSTOM2';
        $this->assertEquals('custom', $dispacher->run()->getResponseContent());
        $this->setExpectedException(\Exception::class);
        $_SERVER['REQUEST_METHOD'] = 'CUSTOM3';
        $dispacher->run()->getResponseContent();

    }

    public function testGetRouteByName()
    {

        $dispacher = new RouteDispacher();
        $dispacher->get('get', function () {
            return 'get';
        }, ['as' => 'testeRoute']);
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/get';

        $route = $dispacher->getRouteByName('testeRoute');
        $this->assertEquals($route->run(), $dispacher->run()->getResponseContent());

        $this->setExpectedException(\Exception::class);
        $dispacher->getRouteByName("notFound");
    }

    public function testGroup()
    {
        $dispacher = new RouteDispacher();
        $dispacher->group('api', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('teste', function () {
                return 'teste';
            });
        });

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api/teste';

        $this->assertEquals('teste', $dispacher->run()->getResponseContent());

        $dispacher->group('/api1', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('teste', function () {
                return 'teste';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api1/teste';

        $this->assertEquals('teste', $dispacher->run()->getResponseContent());

        $dispacher->group('/api2/', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('teste', function () {
                return 'teste';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api2/teste';

        $this->assertEquals('teste', $dispacher->run()->getResponseContent());

        $dispacher->group('/api3/', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('/teste', function () {
                return 'teste';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api3/teste';

        $this->assertEquals('teste', $dispacher->run()->getResponseContent());

        $dispacher->group('/api4/', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('/teste/', function () {
                return 'teste';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/api4/teste';

        $this->assertEquals('teste', $dispacher->run()->getResponseContent());

        $dispacher->group('', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('', function () {
                return 'teste';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $this->assertEquals('teste', $dispacher->run()->getResponseContent());
        $dispacher->group('', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('', function () {
                return 'teste';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

    }

    public function testEmptyGroup(){

        $dispacher = new RouteDispacher();
        $dispacher->group('//', function ($group) {
            /** @var RouteDispacher $group */
            $group->get('//', function () {
                return 'teste2';
            });
        });
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $this->assertEquals('teste2', $dispacher->run()->getResponseContent());
    }

}

