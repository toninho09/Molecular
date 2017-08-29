<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 23/08/17
 * Time: 19:42
 */

namespace Tests\Molecular\Injection;


class BarParamsNotDefault
{

    public $test;
    public function __construct( $test)
    {
        $this->test = $test;
    }
}