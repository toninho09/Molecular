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
        $this->assertTrue($return);
    }

}
