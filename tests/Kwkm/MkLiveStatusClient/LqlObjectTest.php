<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\LqlObject;
use \TestMock;

class LqlObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testNotStringTable()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->setTable(500);
        } catch (\InvalidArgumentException $e) {
            $this->assertNull($mock->table);

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testNotArrayColumns()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->setColumns(500);
        } catch (\InvalidArgumentException $e) {
            $this->assertEmpty($mock->columns);

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testNotStringColumn()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendColumns(500);
        } catch (\InvalidArgumentException $e) {
            $this->assertEmpty($mock->columns);

            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testStringColumn()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendColumns('host_name');
            $this->assertContains('host_name', $mock->columns);

            return;
        } catch (\InvalidArgumentException $e) {
            $this->fail('An expected exception has not been raised.');
        }
    }

    public function testStringQuery()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendStringQuery('host_name', 'example.com');
            $this->assertContains("host_name: example.com\n", $mock->queries);

            return;
        } catch (\InvalidArgumentException $e) {
            $this->fail('An expected exception has not been raised.');
        }
    }

    public function testIntegerQuery()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendIntegerQuery('host_name', 5);
            $this->assertContains("host_name: 5\n", $mock->queries);

            return;
        } catch (\InvalidArgumentException $e) {
            $this->fail('An expected exception has not been raised.');
        }
    }

    public function testNotStringQuery()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendStringQuery('host_name', 5);
        } catch (\InvalidArgumentException $e) {
            $this->assertEmpty($mock->queries);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testNotIntegerQuery()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendIntegerQuery('host_name', 'example.com');
        } catch (\InvalidArgumentException $e) {
            $this->assertEmpty($mock->queries);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testNoValueQuery()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendNoValueQuery('host_name');
            $this->assertContains("host_name:\n", $mock->queries);

            return;
        } catch (\InvalidArgumentException $e) {
            $this->fail('An expected exception has not been raised.');
        }
    }

    public function testStringParameter()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendParameter('host_name');
            $this->assertContains("host_name\n", $mock->queries);

            return;
        } catch (\InvalidArgumentException $e) {
            $this->fail('An expected exception has not been raised.');
        }
    }

    public function testNotStringParameter()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->appendParameter(5);
        } catch (\InvalidArgumentException $e) {
            $this->assertEmpty($mock->queries);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testNotBooleanHeader()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->setHeader('off');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals("ColumnHeaders: on\n", $mock->headers);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testNotStringOutputFormat()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->setOutputFormat(5);
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals("OutputFormat: json\n", $mock->outputFormat);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testNotIntegerLimit()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->setLimit('foo');
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('', $mock->limit);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }

    public function testNotStringAuthUser()
    {
        $mock = TestMock::on(new LqlObject());
        try {
            $mock->setAuthUser(5);
        } catch (\InvalidArgumentException $e) {
            $this->assertEquals('', $mock->authUser);

            return;
        }
        $this->fail('An expected exception has not been raised.');
    }
}