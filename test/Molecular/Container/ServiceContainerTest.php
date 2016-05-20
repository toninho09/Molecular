<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 23:08
 */
class ServiceContainerTest extends PHPUnit_Framework_TestCase
{

    public function testContainer(){
        $container = new \Molecular\Container\ServiceContainer();
        $this->assertEquals($container->has('teste'),false,"não deve haver nada no container" );
        $container->set('teste',function(){
            return 'ok';
        } );
        $this->assertEquals($container->has('teste'),true,'agora deve contar algo no container' );
        $this->assertEquals($container->get('teste'),'ok','deve retornar o retorno do callback que foi passado' );
        $this->assertEquals($container->get('notFound','ok'),'ok','deve retornar o valor default quando for passado e não tiver no container' );
    }
    
    public function testException(){
        $container = new \Molecular\Container\ServiceContainer();
        $this->setExpectedException('Exception');
        $container->get('teste');
    }
    
}
