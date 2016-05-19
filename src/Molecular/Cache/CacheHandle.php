<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 22:09
 */

namespace Molecular\Cache;


interface CacheHandle
{
    function add($key,$content,$time = 60);
    function set($key,$content,$time = 60);
    function get($key,$default = '');
    function has($key);
    function forever($key,$content);
    function forget($key);
    function group($key);
}