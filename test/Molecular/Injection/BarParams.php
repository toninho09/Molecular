<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 23/08/17
 * Time: 19:40
 */

namespace Tests\Molecular\Injection;


class BarParams
{

    public $test;
    public function __construct( $test = 'test')
    {
        $this->test = $test;
    }
}