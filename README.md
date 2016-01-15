# mkLivestatus Client

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/badges/quality-score.png?b=2.0-dev)](https://scrutinizer-ci.com/g/kwkm/MkLiveStatusClient/?branch=2.0-dev)

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
$lql = new mk\Lql(mk\Table::CONTACTS);

$result = $parser->get($client->execute($lql));
```

### Retrieves just the columns name and alias.

```PHP
$lql = new mk\Lql(mk\Table::CONTACTS);
$lql->columns(array('name', 'alias'));

$result = $parser->get($client->execute($lql));
```

### Gets all services with the current state 2 (critical).

```PHP
$filter = new mk\Filter();
$filter->equal('state', '2');

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state'))
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

### Gets all critical services which are currently within their notification period.

```PHP
$filter = new mk\Filter();
$filter->equal('state', '2')
       ->qual('in_notification_period', '1');

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state'))
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

### Matching lists.

```PHP
$filter = new mk\Filter();
$filter->greaterEqual('contacts', 'harri');

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state', 'contacts'))
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

### Gets all hosts that do not have parents.

```PHP
$filter = new mk\Filter();
$filter->equal('parents', '');

$lql = new mk\Lql(mk\Table::HOSTS);
$lql->column('name')
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

### Matching attribute lists

#### Find all hosts with modified attributes.

```PHP
$filter = new mk\Filter();
$filter->notEqual('modified_attributes', '0');

$lql = new mk\Lql(mk\Table::HOSTS);
$lql->columns(array('host_name', 'modified_attributes_list'))
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

#### Find hosts where notification have been actively disabled.

```PHP
$filter = new mk\Filter();
$filter->match('modified_attributes', 'notifications_enabled')
       ->equal('notifications_enabled', '0');

$lql = new mk\Lql(mk\Table::HOSTS);
$lql->columns(array('host_name', 'modified_attributes_list'))
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

#### Find hosts where active or passive checks have been tweaked.

```PHP
$filter = new mk\Filter();
$filter->set('modified_attributes ~~ active_checks_enabled,passive_checks_enabled');

$lql = new mk\Lql(mk\Table::HOSTS);
$lql->columns(array('host_name', 'modified_attributes_list'))
    ->filter($filter);

$result = $parser->get($client->execute($lql));
```

### Combining Filters with And, Or and Negate.

#### Selects all services which are in state 1 or in state 3.

```PHP
$filter = new mk\Filter();
$filter->equal('state', '1')
       ->equal('state', '3')
       ->operatorOr(2);

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->filter($filter);

$result = $parser->get($client->execute($lql));
```

#### Shows all non-OK services which are within a scheduled downtime or which are on a host with a scheduled downtime.

```PHP
$filter = new mk\Filter();
$filter->greater('scheduled_downtime_depth', '0')
       ->greater('host_scheduled_downtime_depth', '0')
       ->operatorOr(2);

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->filter($filter);

$result = $parser->get($client->execute($lql));
```

#### All services that are either critical and acknowledged or OK.

```PHP
$filter = new mk\Filter();
$filter->equal('state', '2')
       ->equal('acknowledged', '1')
       ->operatorAnd(2)
       ->equal('state', '0')
       ->operatorOr(2);

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->filter($filter);

$result = $parser->get($client->execute($lql));
```

#### Displays all hosts that have neither an a nor an o in their name.

```PHP
$filter = new mk\Filter();
$filter->match('name', 'a')
       ->match('name', 'o')
       ->operatorOr(2);

$lql = new mk\Lql(mk\Table::HOSTS);
$lql->filter($filter)->negate();

$result = $parser->get($client->execute($lql));
```
