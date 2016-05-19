<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 22:30
 */
class ServiceTest extends PHPUnit_Framework_TestCase
{
    public function testServiceNull(){
        $container = new Molecular\Container\Service();
        $this->assertEquals($container->get(),null,"não tem callback no para o service");
    }

    public function testServiceCallback(){
        $container = new \Molecular\Container\Service();
        $container->set(function () {
            return 'ok';
        });
        $this->assertEquals($container->get(),'ok','Container deve retornar o callback' );
    }
    
    public function testServiceCallbackSingleton(){
        $container = new \Molecular\Container\Service();
        $container->set(function () {
            return microtime();
        });
        $container->setSingleton(true);
        $valor1 = $container->get();
        usleep(2);
        $valor2 = $container->get();

        $this->assertEquals($valor1, $valor2,"O Container deve retornar o mesmo valor");
    }

    public function testServiceCallbackParams(){
        $container = new \Molecular\Container\Service();
        $container->set(function ($value1,$value2,$value3) {
            return $value1+$value2+$value3;
        },[1,2,3]);

        $this->assertEquals($container->get(),6,"O Container deve passar os parametros na montagem" );
    }

    public function testServiceDirectValue(){
        $container = new \Molecular\Container\Service();
        $container->set('ok');
        $this->assertEquals($container->get(),'ok',"O container deve armazenar quando não é callback" );
    }

    public function testServiceInService(){
        $container1 = new \Molecular\Container\Service();
        $container1->set(function(){
            return 'ok';
        });

        $container2 = new \Molecular\Container\Service();
        $container2->set($container1);
        $this->assertEquals($container2->get()->get(),'ok',"O container de dentro deve manter suas propriedades" );
    }
}
