# mkLivestatus Client

[![License](https://poser.pugx.org/passet/passet/license.svg)](https://packagist.org/packages/passet/passet)
[![Build Status](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/badges/build.png?b=2.0-dev)](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/build-status/2.0-dev)
[![Code Coverage](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/badges/coverage.png?b=2.0-dev)](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/?branch=2.0-dev)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/badges/quality-score.png?b=2.0-dev)](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/?branch=2.0-dev)

## Client Setting.

### In the case of localhost.

```PHP
use Kwkm\MkLiveStatusClient as mk;

require __DIR__ . '/vendor/autoload.php';

$config = new mk\Configration(
    array(
        'socketType' => 'unix',
        'socketPath' => '/var/run/nagios/rw/live',
    )
);

$client = new mk\Client($config);

$parser = new mk\Parser();
```

### In the case of remote network.

```PHP
use Kwkm\MkLiveStatusClient as mk;

require __DIR__ . '/vendor/autoload.php';

$config = new mk\Configration(
    array(
        'socketType' => 'tcp',
        'socketAddress' => '192.168.0.100',
        'socketPort' => 6557,
    )
);

$client = new mk\Client($config);

$parser = new mk\Parser();
```

## Example - LqlBuilder

### Retrieve all contacts.

```PHP
$lql = new mk\LqlBuilder(mk\Table::CONTACTS);

$result = $parser->get($client->execute($lql));
```

### Retrieves just the columns name and alias.

```PHP
$lql = new mk\LqlBuilder(mk\Table::CONTACTS);
$lql->columns(array('name', 'alias'));

$result = $parser->get($client->execute($lql));
```

### Gets all services with the current state 2 (critical).

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state'))
    ->filterEqual('state', '2');

$result = $parser->get($client->execute($lql));
```

### Gets all critical services which are currently within their notification period.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state'))
    ->filterEqual('state', '2')
    ->filterEqual('in_notification_period', '1');

$result = $parser->get($client->execute($lql));
```

### Matching lists.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state', 'contacts'))
    ->filterGreaterEqual('contacts', 'harri');

$result = $parser->get($client->execute($lql));
```

### Gets all hosts that do not have parents.

```PHP
$lql = new mk\LqlBuilder(mk\Table::HOSTS);
$lql->column('name')
    ->filterEqual('parents', '');

$result = $parser->get($client->execute($lql));
```

### Matching attribute lists

#### Find all hosts with modified attributes.

```PHP
$lql = new mk\LqlBuilder(mk\Table::HOSTS);
$lql->columns(array('host_name', 'modified_attributes_list'))
    ->filterNotEqual('modified_attributes', '0');

$result = $parser->get($client->execute($lql));
```

#### Find hosts where notification have been actively disabled.

```PHP
$lql = new mk\LqlBuilder(mk\Table::HOSTS);
$lql->columns(array('host_name', 'modified_attributes_list'))
    ->filterMatch('modified_attributes', 'notifications_enabled')
    ->filterEqual('notifications_enabled', '0');

$result = $parser->get($client->execute($lql));
```

#### Find hosts where active or passive checks have been tweaked.

```PHP
$lql = new mk\LqlBuilder(mk\Table::HOSTS);
$lql->columns(array('host_name', 'modified_attributes_list'))
    ->filterSet('modified_attributes ~~ active_checks_enabled,passive_checks_enabled');

$result = $parser->get($client->execute($lql));
```

### Combining Filters with And, Or and Negate.

#### Selects all services which are in state 1 or in state 3.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->filterEqual('state', '1')
    ->filterEqual('state', '3')
    ->filterOr(2);

$result = $parser->get($client->execute($lql));
```

#### Shows all non-OK services which are within a scheduled downtime or which are on a host with a scheduled downtime.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->filterGreater('scheduled_downtime_depth', '0')
    ->filterGreater('host_scheduled_downtime_depth', '0')
    ->filterOr(2);

$result = $parser->get($client->execute($lql));
```

#### All services that are either critical and acknowledged or OK.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->filterEqual('state', '2')
    ->filterEqual('acknowledged', '1')
    ->filterAnd(2)
    ->filterEqual('state', '0')
    ->filterOr(2);

$result = $parser->get($client->execute($lql));
```

#### Displays all hosts that have neither an a nor an o in their name.

```PHP
$lql = new mk\LqlBuilder(mk\Table::HOSTS);
$lql->filterMatch('name', 'a')
    ->filterMatch('name', 'o')
    ->filterOr(2)
    ->negate();

$result = $parser->get($client->execute($lql));
```

### Stats and Counts.

#### The numbers of services which are OK, WARN, CRIT or UNKNOWN.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->statsEqual('state', '0')
    ->statsEqual('state', '1')
    ->statsEqual('state', '2')
    ->statsEqual('state', '3');

$result = $parser->decode($client->execute($lql));
```

#### The output to services to which the contact harri.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->statsEqual('state', '0')
    ->statsEqual('state', '1')
    ->statsEqual('state', '2')
    ->statsEqual('state', '3')
    ->filterGreaterEqual('contacts', 'harri');

$result = $parser->decode($client->execute($lql));
```

#### Combining with and/or.

```PHP
$lql = new mk\LqlBuilder(mk\Table::SERVICES);
$lql->filterGreaterEqual('host_groups', 'windows')
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

$result = $parser->decode($client->execute($lql));
```



## Example - Lql

```PHP
$column = new mk\Column(
    array(
        'host_name',
        'description',
        'state',
    )
);

$filter = new mk\Filter();
$filter->equal('state', '2')
       ->equal('acknowledged', '1')
       ->operatorAnd(2)
       ->equal('state', '0')
       ->operatorOr(2);

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->column($column)->filter($filter);

$result = $parser->get($client->execute($lql));
```

```PHP
$column = new mk\Column(
    array(
        'host_name',
    )
);

$stats = new mk\Stats();
$stats->equal('state', '0')
      ->equal('state', '1')
      ->equal('state', '2')
      ->equal('state', '3');

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->stats($stats)->column($column);

$result = $parser->decode($client->execute($lql));
```
