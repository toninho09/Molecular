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
    public function testCallback(){
        $call = new Call();
        $return = $call->runFunction(function(){return true;});
        $this->assertTrue($return,"executou o callback");

        $this->assertTrue($call->isRunnable(function(){}),"Callback pode ser executado");
        $this->assertTrue(class_exists('\Molecular\Helper\Callbacks\Call'),"Classe Call Existe");

        $this->assertTrue($call->isRunnable('\Molecular\Helper\Callbacks\Call@isRunnable'),"O metodo da classe pode ser chamado pela sintaxe Class@Method");
    }

}
