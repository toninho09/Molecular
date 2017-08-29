<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 27/05/16
 * Time: 01:53
 */

namespace Molecular\Cache;


class CacheFile implements CacheHandle
{
    /**
     * var to save the folder is used to storage the cache
     * @var string
     */
    private $folder;
    public function __construct($folder = null) {
        $this->setCacheFolder(!is_null($folder) ? $folder : sys_get_temp_dir());
    }

    /**
     * set the value to folder var
     * @param string $path the new value to folder var
     */
    public function setCacheFolder($path){
        if(file_exists($path) && is_dir($path) && is_writable($path)){
            $this->folder = $path;
        }else{
            throw new \Exception("The Folder $path is invalid to save Cache", 1);
        }
    }

    /**
     * get the folder is used to storage the cache
     * @return string the full path
     */
    public function getCacheFolder(){
        return $this->folder;
    }

    /**
     * generete the location used to save or recuver the cache
     * @param  string $key the key of the cache
     * @return string      the location
     */
    protected function generateFileLocation($key) {
        return $this->folder . DIRECTORY_SEPARATOR . sha1($key);
    }

    /**
     * Add one value in the cache only if the value does not already exist,
     * @param string  $key     the key using to rescue the cache value
     * @param mixed  $content  the value of the cache
     * @param integer $time    the time to rescue the cache, in minutes
     */
    public function add($key,$content,$time = 60){
        if($this->has($key)) return false;
        $this->set($key,$content,$time);
        return true;
    }

    /**
     * put a new value or subscribe the last value in the cache file
     * @param string  $key     the key using to rescue or create a new cache
     * @param mixed  $content the value storage on cache
     * @param integer $time    the time where the cache still alive
     */
    public function set($key,$content,$time = 60){
        if(!is_numeric($time)) throw new \Exception("Time is not a numeric value.", 1);
        $time = strtotime('+'.$time.' minutes');
        $content = serialize(array('expired_at' => $time,'content' => $content));
        return $this->createFile($key, $content);
    }

    /**
     * function using to rescue the cache value
     * @param  string $key     key to rescue the cache
     * @param  mixed $default the default value if the cache is not found
     * @return mixed          the value in cache
     */
    public function get($key,$default = null){
        $filename = $this->generateFileLocation($key);
        if (file_exists($filename) && is_readable($filename)) {
            $cache = unserialize(file_get_contents($filename));
            if ( $cache['expired_at'] == 'ever' ||$cache['expired_at'] > time() ) {
                return $cache['content'];
            } else {
                unlink($filename);
                return $default;
            }
        }
        return $default;
    }

    /**
     * try rescue the cache if the cache not exist or is expired, return false
     * @param  string  $key key using to storage or rescue the cache
     * @return boolean      [description]
     */
    public function has($key){
        $filename = $this->generateFileLocation($key);
        if (file_exists($filename) && is_readable($filename)) {
            $cache = unserialize(file_get_contents($filename));
            if ( $cache['expired_at'] == 'ever' ||$cache['expired_at'] > time() ) {
                return true;
            } else {
                unlink($filename);
                return false;
            }
        }
        return false;
    }

    /**
     * put a value on cache forever until the file is deleted or used the function forget
     * @param  string $key     the key used to rescue the cache
     * @param  mixed $content the cache value
     * @return boolean          return if the cache is create with sucess
     */
    public function forever($key,$content){
        $time ="ever";
        $content = serialize(array('expired_at' => $time,'content' => $content));
        return $this->createFile($key, $content);
    }

    /**
     * delete / clean the cache value
     * @param  string $key the cache key
     * @return null
     */
    public function forget($key){
        $filename = $this->generateFileLocation($key);
        if (file_exists($filename) && is_readable($filename)) {
            unlink($filename);
        }
    }

    public function group($key){
        return false;
    }

    /**
     * create the file used to storage the cache
     * @param  string $key     the key value
     * @param  mixed $content the value of the cache
     * @return boolean          true if the file is created with sucess
     */
    private function createFile($key,$content){
        $filename = $this->generateFileLocation($key);
        return file_put_contents($filename, $content) OR trigger_error('Not Possible create the cache file.', E_USER_ERROR);
    }
}