<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\Column;

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $mock = new Column();
        $mock->add('contact_name');

        $this->assertEquals(
            array('contact_name'),
            $mock->get()
        );
    }

    public function testDelete()
    {
        $mock = new Column();
        $mock->add('contact_name')
            ->delete('contact_name');

        $this->assertEquals(
            array(),
            $mock->get()
        );
    }
}
