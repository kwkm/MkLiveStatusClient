# mkLivestatus Client

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/?branch=master)

## Client Setting.

### In the case of localhost.

```PHP
use Kwkm\MkLiveStatusClient as mk;

require __DIR__ . '/vendor/autoload.php';

$client = new mk\Client(
    array(
        'socketType' => 'unix',
        'socketPath' => '/var/run/nagios/rw/live',
    )
);

$parser = new mk\Parser();
```

### In the case of remote network.

```PHP
use Kwkm\MkLiveStatusClient as mk;

require __DIR__ . '/vendor/autoload.php';

$client = new mk\Client(
    array(
        'socketType' => 'tcp',
        'socketAddress' => '192.168.0.100',
        'socketPort' => 6557,
    )
);

$parser = new mk\Parser();
```

## Example.

### Retrieve all contacts.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::CONTACTS);

$result = $parser->get($client->execute($lql));
```

### Retrieves just the columns name and alias.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::CONTACTS)->columns(array('name', 'alias'));

$result = $parser->get($client->execute($lql));
```

### Gets all services with the current state 2 (critical).

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->columns(array('host_name', 'description', 'state'))
    ->filterEqual('state', '2');

$result = $parser->get($client->execute($lql));
```

### Gets all critical services which are currently within their notification period.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->columns(array('host_name', 'description', 'state'))
    ->filterEqual('state', '2')
    ->filterEqual('in_notification_period', '1');

$result = $parser->get($client->execute($lql));
```

### Matching lists.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->columns(array('host_name', 'description', 'state', 'contacts'))
    ->filterGreaterEqual('contacts', 'harri');

$result = $parser->get($client->execute($lql));
```

### Gets all hosts that do not have parents.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::HOSTS)
    ->column('name')
    ->filterEqual('parents', '');

$result = $parser->get($client->execute($lql));
```

### Matching attribute lists

#### Find all hosts with modified attributes.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::HOSTS)
    ->columns(array('host_name', 'modified_attributes_list'))
    ->filterNotEqual('modified_attributes', '0');

$result = $parser->get($client->execute($lql));
```

#### Find hosts where notification have been actively disabled.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::HOSTS)
    ->columns(array('host_name', 'modified_attributes_list'))
    ->filterMatch('modified_attributes', 'notifications_enabled')
    ->filterEqual('notifications_enabled', '0');

$result = $parser->get($client->execute($lql));
```

#### Find hosts where active or passive checks have been tweaked.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::HOSTS)
    ->columns(array('host_name', 'modified_attributes_list'))
    ->filter('modified_attributes ~~ active_checks_enabled,passive_checks_enabled');

$result = $parser->get($client->execute($lql));
```

### Combining Filters with And, Or and Negate.

#### Selects all services which are in state 1 or in state 3.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->filterEqual('state', '1')
    ->filterEqual('state', '3')
    ->filterOr(2);

$result = $parser->get($client->execute($lql));
```

#### Shows all non-OK services which are within a scheduled downtime or which are on a host with a scheduled downtime.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->filterGreater('scheduled_downtime_depth', '0')
    ->filterGreater('host_scheduled_downtime_depth', '0')
    ->filterOr(2);

$result = $parser->get($client->execute($lql));
```

#### All services that are either critical and acknowledged or OK.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->filterEqual('state', '2')
    ->filterEqual('acknowledged', '1')
    ->filterAnd(2);
    ->filterEqual('state', '0')
    ->filterOr(2);

$result = $parser->get($client->execute($lql));
```

#### Displays all hosts that have neither an a nor an o in their name.

```PHP
$lql = new mk\Lql();
$lql->table(mk\Table::HOSTS)
    ->filterMatch('name', 'a')
    ->filterMatch('name', 'o')
    ->filterOr(2)
    ->negate();

$result = $parser->get($client->execute($lql));
```
