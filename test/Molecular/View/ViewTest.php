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
        $this->assertEquals((string)$view,'ok');
        $this->assertEquals(__DIR__.DIRECTORY_SEPARATOR , $view->getDefaultViewPath());

    }

    public function testViewNotFound(){

        $view = new \Molecular\View\View();
        $view->setDefaultViewPath(__DIR__.DIRECTORY_SEPARATOR);
        $this->setExpectedException(\Exception::class);
        $view->render('testeViewRender2.php');
    }

    public function testWithEmpty(){

        $view = new \Molecular\View\View();
        $this->setExpectedException(\Exception::class);
        $view->with(null);
    }

    public function testWithParamNameValue(){

        $view = new \Molecular\View\View();
        $view->with('param','teste');
        $view->setDefaultViewPath(__DIR__.DIRECTORY_SEPARATOR);
        $this->assertEquals('teste',$view->render('testeWithParam.php'));


        $view->with(['param'=>'teste2']);
        $this->assertEquals('teste2',$view->render('testeWithParam.php'));
    }
}
