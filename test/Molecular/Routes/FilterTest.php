<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 03/05/16
 * Time: 00:17
 */

namespace Tests\Molecular\Routes;


use Molecular\Routes\Filter;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testHasFilter(){
        $filter = new Filter();
        $filter->setFilter('teste',function(){});
        $this->assertTrue($filter->exists('teste'),'Não existe o filtro');
    }
}
