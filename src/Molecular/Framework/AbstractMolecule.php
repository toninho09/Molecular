<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 00:55
 */

namespace Molecular\Framework;


abstract class AbstractMolecule
{
    protected $name;
    protected $instance;

    public final function getInstance(){
        return $this->instance;
    }

    public function getName(){
        return $this->name;
    }
    
    public function boot(){
        
    }
    
    public function register(){
        
    }
    
    public function run(){
        
    }

    public function end(){

    }
}