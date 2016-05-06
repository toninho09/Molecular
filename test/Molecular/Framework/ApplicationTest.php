<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 01:07
 */
class ApplicationTest extends PHPUnit_Framework_TestCase
{
    public function testAddMolecule(){
        $application = new \Molecular\Framework\Application();
        $this->assertNotEmpty($application->route);
        $_SERVER['REQUEST_URI'] = 'test';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $application->route->get('test',function(){return 'ok';});
        $application->run();
        $this->assertEquals('ok',$application->getResponse());
    }
}
