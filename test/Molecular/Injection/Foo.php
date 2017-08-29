<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 23/08/17
 * Time: 18:25
 */

namespace Tests\Molecular\Injection;


class Foo {
    public function bar(){
        return 'bar';
    }

    public function repeat($repeat){
        return $repeat;
    }
}