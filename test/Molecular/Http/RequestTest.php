<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 06/05/16
 * Time: 01:40
 */
class RequestTest extends PHPUnit_Framework_TestCase
{
    public function testRequest(){
        $request = new \Molecular\Http\Request();
        $input = $request->input();
        $this->assertInstanceOf(\Molecular\Http\Input::class,$input,'O input do request deve ser uma instancia de \Molecular\Http\Input');
    }

    public function testEnviroment(){
        $request = new \Molecular\Http\Request();
        $_SERVER['REQUEST_URI'] = 'REQUEST_URI';
        $this->assertEquals($_SERVER['REQUEST_URI'],$request->getRequestURI());
        $_SERVER['REQUEST_METHOD'] = 'REQUEST_METHOD';
        $this->assertEquals($_SERVER['REQUEST_METHOD'],$request->getMethod());
        $_SERVER['SERVER_PORT'] = 'SERVER_PORT';
        $this->assertEquals($_SERVER['SERVER_PORT'],$request->getPort());
        $_SERVER['SERVER_NAME'] = 'SERVER_NAME';
        $this->assertEquals($_SERVER['SERVER_NAME'],$request->getServerName());
        $_SERVER['CONTENT_TYPE'] = 'CONTENT_TYPE';
        $this->assertEquals($_SERVER['CONTENT_TYPE'],$request->getContentType());
        $_SERVER['CONTENT_LENGTH'] = 'CONTENT_LENGTH';
        $this->assertEquals($_SERVER['CONTENT_LENGTH'],$request->getContentLength());
        $_SERVER['AUTH_USER'] = 'AUTH_USER';
        $this->assertEquals($_SERVER['AUTH_USER'],$request->getAuthUser());
        $_SERVER['AUTH_PASSWORD'] = 'AUTH_PASSWORD';
        $this->assertEquals($_SERVER['AUTH_PASSWORD'],$request->getAuthPassword());
        $_SERVER['REQUEST_TIME'] = 'REQUEST_TIME';
        $this->assertEquals($_SERVER['REQUEST_TIME'],$request->getRequestTime());
        $_SERVER['HTTP_ACCEPT'] = 'HTTP_ACCEPT';
        $this->assertEquals($_SERVER['HTTP_ACCEPT'],$request->getAccept());
    }

    public function testHeads(){
        $request = new \Molecular\Http\Request();
        if (!function_exists('getallheaders'))
        {
            function getallheaders()
            {
                $headers = array ();
                foreach ($_SERVER as $name => $value)
                {
                    if (substr($name, 0, 5) == 'HTTP_')
                    {
                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                    }
                }
                return $headers;
            }
        }

        $headers = getallheaders();
        $this->assertEquals(getallheaders(),$request->getHeaders());

    }

}
