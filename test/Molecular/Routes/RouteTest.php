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
        $this->assertTrue($route->isRouteValid());
    }
}
