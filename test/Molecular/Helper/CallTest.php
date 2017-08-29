<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 03/05/16
 * Time: 00:16
 */

namespace Tests\Molecular\Helper;


use Molecular\Helper\Callbacks\Call;

class CallTest extends \PHPUnit_Framework_TestCase
{
    public function testCallback()
    {
        $call = new Call();
        $return = $call->runFunction(function () {
            return true;
        });
        $this->assertTrue($return, "executou o callback");

        $this->assertTrue($call->isRunnable(function () {
        }), "Callback pode ser executado");
        $this->assertTrue(class_exists('\Molecular\Helper\Callbacks\Call'), "Classe Call Existe");

        $this->assertTrue($call->isRunnable('\Molecular\Helper\Callbacks\Call@isRunnable'), "O metodo da classe pode ser chamado pela sintaxe Class@Method");
    }

    public function testClassMethod()
    {
        $call = new Call();
        $this->assertTrue($call->isRunnable('\Tests\Molecular\Helper\Foo@bar'), 'Deve ser capaz de localizar a classe pelo nome');
        $this->assertEquals('bar', $call->runFunction('\Tests\Molecular\Helper\Foo@bar'), 'Deve ser capaz de instanciar a classe e chamar o metodo');
        $this->assertEquals('repeat', $call->runFunction('\Tests\Molecular\Helper\Foo@repeat', ['repeat']), "Deve ser capax de instanciar a classe e chamar o metodo passando parametros");
    }

    public function testExceptionText()
    {
        $call = new Call();
        $this->setExpectedException(\Exception::class);
        $call->runFunction('teste');

    }

    public function testExceptionNumber()
    {
        $call = new Call();
        $this->setExpectedException(\Exception::class);
        $call->runFunction(1);
    }

    public function testExceptionMethodNotExists()
    {
        $call = new Call();
        $this->setExpectedException(\Exception::class);
        $call->runFunction("\Tests\Molecular\Helper\Foo@foo");
    }

    public function testExceptionClassNotExists()
    {
        $call = new Call();
        $this->setExpectedException(\Exception::class);
        $call->runFunction("\Tests\Molecular\Helper\Bar@foo");
    }

}
