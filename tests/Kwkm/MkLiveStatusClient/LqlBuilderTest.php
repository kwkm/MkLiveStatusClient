<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\LqlBuilder;
use Kwkm\MkLiveStatusClient\Table;

require_once __DIR__ . '/../../bootstrap.php';

class OptionParserTest extends \PHPUnit_Framework_TestCase
{

    public function testReadme1()
    {
        $lql = <<<EOF
GET contacts
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::CONTACTS)
        );

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Retrieve all contacts.'
        );
    }


    public function testReadme2()
    {
        $lql = <<<EOF
GET contacts
Columns: name alias
ColumnHeaders: on
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::CONTACTS)
        );
        $mock->columns(array('name', 'alias'));

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Retrieves just the columns name and alias.'
        );
    }

    public function testReadme3()
    {
        $lql = <<<EOF
GET services
Columns: host_name description state
ColumnHeaders: on
Filter: state = 2
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::SERVICES)
        );
        $mock->columns(array('host_name', 'description', 'state'))
            ->filterEqual('state', '2');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Gets all services with the current state 2 (critical).'
        );
    }

    public function testReadme4()
    {
        $lql = <<<EOF
GET services
Columns: host_name description state
ColumnHeaders: on
Filter: state = 2
Filter: in_notification_period = 1
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::SERVICES)
        );
        $mock->columns(array('host_name', 'description', 'state'))
            ->filterEqual('state', '2')
            ->filterEqual('in_notification_period', '1');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Gets all critical services which are currently within their notification period.'
        );
    }
}
