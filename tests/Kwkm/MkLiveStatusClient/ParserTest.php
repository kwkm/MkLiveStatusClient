<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    public function getResponse()
    {
        $response = <<<EOL
[["host_name","description","state"],
["localhost","Total Processes",0],
["localhost","Swap Usage",0],
["localhost","SSH",0],
["localhost","Root Partition",0],
["localhost","PING",0],
["localhost","HTTP",1],
["localhost","Current Users",0],
["localhost","Current Load",0]]

EOL;

        return $response;
    }

    public function testDecode()
    {
        $mock = new Parser();

        $data = $mock->decode($this->getResponse());

        $this->assertEquals($data[0][1], 'description');
    }

    public function testGet()
    {
        $mock = new Parser();

        $data = $mock->get($this->getResponse());

        $this->assertEquals($data[0]['description'], 'Total Processes');
    }
}
