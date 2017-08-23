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

    public function testSingleton(){

        $container = new \Molecular\Container\ServiceContainer();
        $container->set('teste',function(){
            return microtime(true);
        } );
        $this->assertTrue($container->has('teste'),'O container deve existir');
        $tempo = $container->get('teste');
        usleep(1);
        $this->assertTrue($tempo < $container->get('teste'),'O metodo executando como nao singleton deve ser executado novamente');
        $container->setSingleton('teste',true);

        $tempo = $container->get('teste');
        usleep(1);
        $this->assertTrue($container->has('teste'), 'O container ainda deve existir');
        $this->assertEquals($tempo ,$container->get('teste'), ' O container com singleton deve retornar o mesmo valor');
    }
    
}
