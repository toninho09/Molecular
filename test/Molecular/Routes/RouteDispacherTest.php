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

    public function testDispacherTeste()
    {
        $dispacher = new RouteDispacher();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/test';
        $dispacher->post('test', function () {
            return 'ok';
        });
        $this->assertEquals('ok', $dispacher->run(), 'Verificando Se o dipacher retorna a rota');

        $dispacher->post('outraRota', function () {
            return 'outra';
        });
        $this->assertEquals('ok', $dispacher->run(), 'Verificando Se com mais de uma rota ainda chama a certa');

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertEquals('', $dispacher->run(), 'Verifica se está considerando o methodo para encontrar a rota');

        $dispacher->get('test', function () {
            return 'get';
        });

        $this->assertEquals('get', $dispacher->run(), 'Verifica se o trás a rota mesmo com 2 cadastradas para a mesma rota e metodos diferentes');

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->assertEquals('ok', $dispacher->run(), 'Verifica se a rota anterior não sobrescreveu a antiga');
    }

    public function testGrupoDeRotas()
    {

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/test/teste';

        $dispacher = new RouteDispacher();
        $dispacher->group('test', function ($routes) {
            $routes->post('teste', function () {
                return 'ok';
            });
        }, ['filters' => ['before' => function () {
            return 'ok';
        }]]);

        $this->assertEquals('okok', $dispacher->run(), "verifica o retorno no caso de rotas em grupos");

    }


}

