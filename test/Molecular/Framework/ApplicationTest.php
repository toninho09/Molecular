<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 01:07
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testRoute(){
        $application = new \Molecular\Framework\Application();
        $this->assertNotEmpty($application->route);
        $_SERVER['REQUEST_URI'] = 'test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $application->route->get('test',function(){return 'ok';});
        $application->run();
        $this->assertEquals('ok',$application->getResponse());
    }
    
    public function testServiceContainer(){
        $application = new \Molecular\Framework\Application();
        $application->container->set('teste',10);
        $this->assertEquals($application->container->get('teste'),10);
        $application->container->set('callback',function(){
            return 20;
        });
        $this->assertEquals($application->container->get('callback'),20);
    }

    public function testRouteApplicationException(){
        $application = new \Molecular\Framework\Application();
        $this->assertNotEmpty($application->route);
        $_SERVER['REQUEST_URI'] = 'test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $application->route->get('test',function(){throw new \Exception();});
        $this->setExpectedException('Exception');
        $application->run();
    }
    
}
