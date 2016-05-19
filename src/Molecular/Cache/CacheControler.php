<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 22:03
 */

namespace Molecular\Cache;


class CacheControler
{
    private $cache;
    /**
     * CacheControler constructor.
     */
    public function __construct()
    {

    }

    public function setHandle(CacheHandle $cache){
        $this->cache = $cache;
    }
    
}