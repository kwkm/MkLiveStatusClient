<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\LqlBuilder;
use Kwkm\MkLiveStatusClient\Table;

require_once __DIR__ . '/../../bootstrap.php';

class LqlBuilderTest extends \PHPUnit_Framework_TestCase
{

    public function testAuth()
    {
        $lql = <<<EOF
GET contacts
Columns: contact_name
ColumnHeaders: on
OutputFormat: json
AuthUser: kwkm
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::CONTACTS, 'kwkm')
        );
        $mock->column('contact_name');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'check AuthUser.'
        );
    }

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

    public function testReadme5()
    {
        $lql = <<<EOF
GET services
Columns: host_name description state contacts
ColumnHeaders: on
Filter: contacts >= harri
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::SERVICES)
        );
        $mock->columns(array('host_name', 'description', 'state', 'contacts'))
            ->filterGreaterEqual('contacts', 'harri');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Matching lists.'
        );
    }

    public function testReadme6()
    {
        $lql = <<<EOF
GET hosts
Columns: name
ColumnHeaders: on
Filter: parents =
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::HOSTS)
        );
        $mock->column('name')
            ->filterEqual('parents', '');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Gets all hosts that do not have parents.'
        );
    }

    public function testReadme7()
    {
        $lql = <<<EOF
GET hosts
Columns: host_name modified_attributes_list
ColumnHeaders: on
Filter: modified_attributes != 0
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::HOSTS)
        );
        $mock->columns(array('host_name', 'modified_attributes_list'))
            ->filterNotEqual('modified_attributes', '0');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Find all hosts with modified attributes.'
        );
    }

    public function testReadme8()
    {
        $lql = <<<EOF
GET hosts
Columns: host_name modified_attributes_list
ColumnHeaders: on
Filter: modified_attributes ~ notifications_enabled
Filter: notifications_enabled = 0
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::HOSTS)
        );
        $mock->columns(array('host_name', 'modified_attributes_list'))
            ->filterMatch('modified_attributes', 'notifications_enabled')
            ->filterEqual('notifications_enabled', '0');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Find hosts where notification have been actively disabled.'
        );
    }

    public function testReadme9()
    {
        $lql = <<<EOF
GET hosts
Columns: host_name modified_attributes_list
ColumnHeaders: on
Filter: modified_attributes ~~ active_checks_enabled,passive_checks_enabled
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::HOSTS)
        );
        $mock->columns(array('host_name', 'modified_attributes_list'))
            ->filter('modified_attributes ~~ active_checks_enabled,passive_checks_enabled');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Find hosts where active or passive checks have been tweaked.'
        );
    }

    public function testReadme10()
    {
        $lql = <<<EOF
GET services
Filter: state = 1
Filter: state = 3
Or: 2
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::SERVICES)
        );
        $mock->filterEqual('state', '1')
            ->filterEqual('state', '3')
            ->filterOr(2);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Selects all services which are in state 1 or in state 3.'
        );
    }

    public function testReadme11()
    {
        $lql = <<<EOF
GET services
Filter: scheduled_downtime_depth > 0
Filter: host_scheduled_downtime_depth > 0
Or: 2
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::SERVICES)
        );
        $mock->filterGreater('scheduled_downtime_depth', '0')
            ->filterGreater('host_scheduled_downtime_depth', '0')
            ->filterOr(2);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Shows all non-OK services which are within a scheduled downtime or which are on a host with a scheduled downtime.'
        );
    }

    public function testReadme12()
    {
        $lql = <<<EOF
GET services
Filter: state = 2
Filter: acknowledged = 1
And: 2
Filter: state = 0
Or: 2
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::SERVICES)
        );
        $mock->filterEqual('state', '2')
            ->filterEqual('acknowledged', '1')
            ->filterAnd(2)
            ->filterEqual('state', '0')
            ->filterOr(2);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'All services that are either critical and acknowledged or OK.'
        );
    }

    public function testReadme13()
    {
        $lql = <<<EOF
GET hosts
Filter: name ~ a
Filter: name ~ o
Or: 2
Negate:
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = \TestMock::on(
            new LqlBuilder(Table::HOSTS)
        );
        $mock->filterMatch('name', 'a')
            ->filterMatch('name', 'o')
            ->filterOr(2)
            ->negate();

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Displays all hosts that have neither an a nor an o in their name.'
        );
    }
}
