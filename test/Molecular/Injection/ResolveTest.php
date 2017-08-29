<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 23/08/17
 * Time: 19:33
 */

use Molecular\Injection\Resolve;

class ResolveTest extends PHPUnit_Framework_TestCase
{

    public function testResolveClass()
    {
        $resolve = new Resolve();
        $class = $resolve->resolve(\Tests\Molecular\Injection\Foo::class);
        $this->assertInstanceOf(\Tests\Molecular\Injection\Foo::class, $class);

        $this->setExpectedException(Exception::class);
        $resolve->resolve('foo');
    }

    public function testNotInstantiable()
    {
        $resolve = new Resolve();
        $this->setExpectedException(Exception::class);
        $class = $resolve->resolve(\Tests\Molecular\Injection\FooPrivate::class);
    }

    public function testParams()
    {

        $resolve = new Resolve();
        $class = $resolve->resolve(\Tests\Molecular\Injection\BarParams::class);
        $this->assertEquals('test', $class->test);
    }

    public function testParamsNotDefault()
    {

        $resolve = new Resolve();
        $this->setExpectedException(\Exception::class);
        $class = $resolve->resolve(\Tests\Molecular\Injection\BarParamsNotDefault::class);
    }
}
