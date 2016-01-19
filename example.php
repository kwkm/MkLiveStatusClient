<?php
use Kwkm\MkLiveStatusClient as mk;

require __DIR__ . '/vendor/autoload.php';

$client = new mk\Client(
    new mk\Configuration(
        array(
            'socketType' => 'unix',
            'socketPath' => '/var/run/nagios/rw/live',
        )
    )
);

$parser = new mk\Parser();

$column = new mk\Column(
    array('host_name', 'description', 'state')
);

$filter = new mk\Filter();
$filter->equal('description', 'PING');

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->column($column)->filter($filter);

var_dump($parser->get($client->execute($lql)));

