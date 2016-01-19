<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\LqlBuilder;
use Kwkm\MkLiveStatusClient\Table;

class LqlBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testUnknownMethod()
    {
        $mock = new LqlBuilder(Table::CONTACTS);
        try {
            $mock->fooBar();
        } catch (\BadMethodCallException $e) {
            return;
        }
        
        $this->fail('An expected exception has not been raised.');
    }

    public function testUnknownFilterMethod()
    {
        $mock = new LqlBuilder(Table::CONTACTS);
        try {
            $mock->filterHogeHoge();
        } catch (\BadMethodCallException $e) {
            return;
        }
        
        $this->fail('An expected exception has not been raised.');
    }

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

        $mock = new LqlBuilder(Table::CONTACTS, 'kwkm');
        $mock->column('contact_name');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'check AuthUser.'
        );
    }

    public function testCsvLimit()
    {
        $lql = <<<EOF
GET contacts
Columns: contact_name
ColumnHeaders: off
Separators: 10 44 59 124
OutputFormat: csv
Limit: 5
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::CONTACTS);
        $mock->column('contact_name')
            ->headers(false)
            ->outputFormat('csv')
            ->parameter('Separators: 10 44 59 124')
            ->limit(5);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'check OutputFormat & Limit.'
        );
    }

    public function testReadme1()
    {
        $lql = <<<EOF
GET contacts
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::CONTACTS);

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

        $mock = new LqlBuilder(Table::CONTACTS);
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

        $mock = new LqlBuilder(Table::SERVICES);
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

        $mock = new LqlBuilder(Table::SERVICES);
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

        $mock = new LqlBuilder(Table::SERVICES);
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

        $mock = new LqlBuilder(Table::HOSTS);
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

        $mock = new LqlBuilder(Table::HOSTS);
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

        $mock = new LqlBuilder(Table::HOSTS);
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

        $mock = new LqlBuilder(Table::HOSTS);
        $mock->columns(array('host_name', 'modified_attributes_list'))
            ->filterSet('modified_attributes ~~ active_checks_enabled,passive_checks_enabled');

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

        $mock = new LqlBuilder(Table::SERVICES);
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

        $mock = new LqlBuilder(Table::SERVICES);
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

        $mock = new LqlBuilder(Table::SERVICES);
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

        $mock = new LqlBuilder(Table::HOSTS);
        $mock->filterMatch('name', 'a')
            ->filterMatch('name', 'o')
            ->filterOr(2)
            ->filterNegate();

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

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->statsEqual('state', '0')
            ->statsEqual('state', '1')
            ->statsEqual('state', '2')
            ->statsEqual('state', '3');

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

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->statsEqual('state', '0')
            ->statsEqual('state', '1')
            ->statsEqual('state', '2')
            ->statsEqual('state', '3')
            ->filterGreaterEqual('contacts', 'harri');

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

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->filterGreaterEqual('host_groups', 'windows')
            ->filterEqual('scheduled_downtime_depth', '0')
            ->filterEqual('host_scheduled_downtime_depth', '0')
            ->filterEqual('in_notification_period', '1')
            ->statsEqual('last_hard_state', '0')
            ->statsEqual('last_hard_state', '1')
            ->statsEqual('acknowledged', '0')
            ->statsAnd(2)
            ->statsEqual('last_hard_state', '1')
            ->statsEqual('acknowledged', '1')
            ->statsAnd(2)
            ->statsEqual('last_hard_state', '2')
            ->statsEqual('acknowledged', '0')
            ->statsAnd(2)
            ->statsEqual('last_hard_state', '2')
            ->statsEqual('acknowledged', '1')
            ->statsAnd(2)
            ->statsEqual('last_hard_state', '3')
            ->statsEqual('acknowledged', '0')
            ->statsAnd(2)
            ->statsEqual('last_hard_state', '3')
            ->statsEqual('acknowledged', '1')
            ->statsAnd(2);

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Combining with and/or.'
        );
    }

    public function testReadme17()
    {
        $lql = <<<EOF
GET services
Columns: host_name
ColumnHeaders: on
Filter: host_groups >= windows
Stats: state = 0
Stats: state = 1
Stats: state = 2
Stats: state = 3
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->filterGreaterEqual('host_groups', 'windows')
            ->statsEqual('state', '0')
            ->statsEqual('state', '1')
            ->statsEqual('state', '2')
            ->statsEqual('state', '3')
            ->column('host_name');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'The number of services in the various states for each host in the host group windows.'
        );
    }

    public function testReadme18()
    {
        $lql = <<<EOF
GET services
Columns: check_command
ColumnHeaders: on
Stats: state != 9999
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->statsNotEqual('state', '9999')
            ->column('check_command');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Counts the total number of services grouped by the check command.'
        );
    }

    public function testReadme19()
    {
        $lql = <<<EOF
GET services
Columns: state
ColumnHeaders: on
Stats: state != 9999
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->statsNotEqual('state', '9999')
            ->column('state');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Counting the total number of services grouped by their states.'
        );
    }

    public function testReadme20()
    {
        $lql = <<<EOF
GET services
Filter: state = 0
Stats: min execution_time
Stats: max execution_time
Stats: avg execution_time
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->filterEqual('state', '0')
            ->statsMin('execution_time')
            ->statsMax('execution_time')
            ->statsAvg('execution_time');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Minimum, maximum and average check execution time of all service checks in state OK.'
        );
    }

    public function testReadme21()
    {
        $lql = <<<EOF
GET services
Columns: host_name
ColumnHeaders: on
Filter: state = 0
Stats: min execution_time
Stats: max execution_time
Stats: avg execution_time
OutputFormat: json
ResponseHeader: fixed16


EOF;

        $mock = new LqlBuilder(Table::SERVICES);
        $mock->filterEqual('state', '0')
            ->statsMin('execution_time')
            ->statsMax('execution_time')
            ->statsAvg('execution_time')
            ->column('host_name');

        $this->assertEquals(
            $lql,
            $mock->build(),
            'Grouping host_name.'
        );
    }

}
