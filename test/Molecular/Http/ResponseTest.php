<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 01:40
 */
class ResponseTest extends PHPUnit_Framework_TestCase
{
    public function testResponse(){
        $response = new \Molecular\Http\Response();
        $response->setResponseContent('ok');
        $this->assertEquals('ok',$response->getResponseContent());
        $response->setResponseContent('ok');
        $this->assertEquals('okok',$response->getResponseContent());
        $response->setResponseContent('ok',true);
        $this->assertEquals('ok',$response->getResponseContent());
    }
}
