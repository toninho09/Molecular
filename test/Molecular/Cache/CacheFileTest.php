<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 29/08/17
 * Time: 18:29
 */

use Molecular\Cache\CacheFile;

class CacheFileTest extends PHPUnit_Framework_TestCase
{
    public function testCache(){
        $cache = new CacheFile();
        $cache->forget('teste');
        $cache->set('teste','teste');
        $this->assertEquals('teste',$cache->get('teste'));
        $cache->forget('teste');
    }

    public function testFolderInvalid(){
        $cache = new CacheFile();
        $this->setExpectedException(\Exception::class);
        $cache->setCacheFolder('foo');
    }

    public function testFolder(){
        $cache = new CacheFile();
        $this->assertEquals(sys_get_temp_dir(),$cache->getCacheFolder());
    }

    public function testAdd(){
        $cache = new CacheFile();
        $cache->forget('add');
        $this->assertTrue($cache->add('add',''));
        $this->assertFalse($cache->add('add',''));
        $cache->forget('add');
    }

    public function testGetInvalid(){
        $cache = new CacheFile();
        $cache->forget('foo');
        $cache->set('foo','bar',0);
        $this->assertEquals('default',$cache->get('foo','default'));
        $cache->forget('notFound');
        $this->assertEquals('default',$cache->get('notFound','default'));
        $cache->forget('bar');
        $cache->set('bar','foo',0);
        $this->assertFalse($cache->has('bar'));
        $cache->forget('foo');
        $cache->forget('bar');
    }

    public function testForever(){
        $cache = new CacheFile();
        $cache->forget('foo');
        $cache->forever('foo','bar');
        $this->assertTrue($cache->has(('foo')));
        $this->assertEquals('bar',$cache->get('foo'));
    }

    public function testGroup(){
        $cache = new CacheFile();
        $this->assertFalse($cache->group('foo'));  //not Implemented
    }
}
