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
