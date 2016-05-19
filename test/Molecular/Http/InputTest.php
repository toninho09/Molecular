<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 18/05/16
 * Time: 21:40
 */
class InputTest extends PHPUnit_Framework_TestCase
{
    public function testInput(){
        $input = new \Molecular\Http\Input();
        $_GET['teste'] = 'ok';
        $this->assertEquals($input->get('teste'),'ok','deve conseguir pegar o valor do get' );
        $this->assertEquals($input->get('teste2','default'),'default','quando não achar a variável deve retornar o default' );
        $this->assertEquals($input->post('teste'),null,'o metodo post não deve encontrar a variável apenas do get' );
        $_POST['teste'] = 'teste_post';
        $this->assertEquals($input->get('teste'),'ok','O Get tem prioridade' );
        $_POST['teste_post'] = 'post';
        $this->assertEquals($input->post('teste_post'),'post','o metodo post deve pegar o valor' );
        $this->assertEquals($input->get('teste_post'),'post','o metodo get tbm deve pegar o valor' );
    }
    
}
