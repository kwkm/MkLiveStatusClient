<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\Column;
use Kwkm\MkLiveStatusClient\Filter;
use Kwkm\MkLiveStatusClient\Lql;
use Kwkm\MkLiveStatusClient\Stats;
use Kwkm\MkLiveStatusClient\Table;

require_once __DIR__ . '/../../bootstrap.php';

class LqlTest extends \PHPUnit_Framework_TestCase
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
            new Lql(Table::CONTACTS, 'kwkm')
        );
        $mock->column(new Column(array('contact_name')));

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
            new Lql(Table::CONTACTS)
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
            new Lql(Table::CONTACTS)
        );
        $mock->column(new Column(array('name', 'alias')));

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

        $filter = new Filter();
        $filter->equal('state', '2');

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->column(new Column(array('host_name', 'description', 'state')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->equal('state', '2')
            ->equal('in_notification_period', '1');

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->column(new Column(array('host_name', 'description', 'state')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->greaterEqual('contacts', 'harri');

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->column(new Column(array('host_name', 'description', 'state', 'contacts')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->equal('parents', '');

        $mock = \TestMock::on(
            new Lql(Table::HOSTS)
        );
        $mock->column(new Column(array('name')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->notEqual('modified_attributes', '0');

        $mock = \TestMock::on(
            new Lql(Table::HOSTS)
        );
        $mock->column(new Column(array('host_name', 'modified_attributes_list')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->match('modified_attributes', 'notifications_enabled')
            ->equal('notifications_enabled', '0');

        $mock = \TestMock::on(
            new Lql(Table::HOSTS)
        );
        $mock->column(new Column(array('host_name', 'modified_attributes_list')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->set('modified_attributes ~~ active_checks_enabled,passive_checks_enabled');

        $mock = \TestMock::on(
            new Lql(Table::HOSTS)
        );
        $mock->column(new Column(array('host_name', 'modified_attributes_list')))
            ->filter($filter);

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

        $filter = new Filter();
        $filter->equal('state', '1')
            ->equal('state', '3')
            ->operatorOr(2);

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->filter($filter);

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


        $filter = new Filter();
        $filter->greater('scheduled_downtime_depth', '0')
            ->greater('host_scheduled_downtime_depth', '0')
            ->operatorOr(2);

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->filter($filter);

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

        $filter = new Filter();
        $filter->equal('state', '2')
            ->equal('acknowledged', '1')
            ->operatorAnd(2)
            ->equal('state', '0')
            ->operatorOr(2);

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->filter($filter);

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

        $filter = new Filter();
        $filter->match('name', 'a')
            ->match('name', 'o')
            ->operatorOr(2)
            ->negate();

        $mock = \TestMock::on(
            new Lql(Table::HOSTS)
        );
        $mock->filter($filter);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Displays all hosts that have neither an a nor an o in their name.'
        );
    }

    public function testReadme14()
    {
        $lql = <<<EOF
GET services
Stats: state = 0
Stats: state = 1
Stats: state = 2
Stats: state = 3
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $stats = new Stats();
        $stats->equal('state', '0')
            ->equal('state', '1')
            ->equal('state', '2')
            ->equal('state', '3');

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->stats($stats);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'The numbers of services which are OK, WARN, CRIT or UNKNOWN.'
        );
    }

    public function testReadme15()
    {
        $lql = <<<EOF
GET services
Stats: state = 0
Stats: state = 1
Stats: state = 2
Stats: state = 3
Filter: contacts >= harri
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $stats = new Stats();
        $stats->equal('state', '0')
            ->equal('state', '1')
            ->equal('state', '2')
            ->equal('state', '3');

        $filter = new Filter();
        $filter->greaterEqual('contacts', 'harri');

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->stats($stats)
            ->filter($filter);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'The output to services to which the contact harri.'
        );
    }

    public function testReadme16()
    {
        $lql = <<<EOF
GET services
Filter: host_groups >= windows
Filter: scheduled_downtime_depth = 0
Filter: host_scheduled_downtime_depth = 0
Filter: in_notification_period = 1
Stats: last_hard_state = 0
Stats: last_hard_state = 1
Stats: acknowledged = 0
StatsAnd: 2
Stats: last_hard_state = 1
Stats: acknowledged = 1
StatsAnd: 2
Stats: last_hard_state = 2
Stats: acknowledged = 0
StatsAnd: 2
Stats: last_hard_state = 2
Stats: acknowledged = 1
StatsAnd: 2
Stats: last_hard_state = 3
Stats: acknowledged = 0
StatsAnd: 2
Stats: last_hard_state = 3
Stats: acknowledged = 1
StatsAnd: 2
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $stats = new Stats();
        $stats->equal('last_hard_state', '0')
            ->equal('last_hard_state', '1')
            ->equal('acknowledged', '0')
            ->operatorAnd(2)
            ->equal('last_hard_state', '1')
            ->equal('acknowledged', '1')
            ->operatorAnd(2)
            ->equal('last_hard_state', '2')
            ->equal('acknowledged', '0')
            ->operatorAnd(2)
            ->equal('last_hard_state', '2')
            ->equal('acknowledged', '1')
            ->operatorAnd(2)
            ->equal('last_hard_state', '3')
            ->equal('acknowledged', '0')
            ->operatorAnd(2)
            ->equal('last_hard_state', '3')
            ->equal('acknowledged', '1')
            ->operatorAnd(2);

        $filter = new Filter();
        $filter->greaterEqual('host_groups', 'windows')
            ->equal('scheduled_downtime_depth', '0')
            ->equal('host_scheduled_downtime_depth', '0')
            ->equal('in_notification_period', '1');

        $mock = \TestMock::on(
            new Lql(Table::SERVICES)
        );
        $mock->filter($filter)
            ->stats($stats);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Combining with and/or.'
        );
    }

}
