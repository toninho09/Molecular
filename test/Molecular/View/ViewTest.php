<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 20/05/16
 * Time: 00:59
 */
class ViewTest extends PHPUnit_Framework_TestCase
{

    public function testRender(){
        $view = new \Molecular\View\View();
        $view->setDefaultViewPath(__DIR__.DIRECTORY_SEPARATOR);
        $view->setFile('testeViewRender.php');

        $this->assertEquals($view->render(),'ok');

    }
}
