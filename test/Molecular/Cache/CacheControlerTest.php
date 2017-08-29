<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 22:03
 */
class CacheControlerTest extends PHPUnit_Framework_TestCase
{
    public function testSetFileCache(){
        $cache = new \Molecular\Cache\CacheControler();
        $cache->setHandle(new \Molecular\Cache\CacheFile());
        $this->assertInstanceOf(\Molecular\Cache\CacheFile::class,$cache->getHandle());
    }
}
