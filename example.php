<?php
use Kwkm\MkLiveStatusClient as mk;

require __DIR__ . '/vendor/autoload.php';

$client = new mk\Client(
    array(
        'socketType' => 'unix',
        'socketPath' => '/var/run/nagios/rw/live',
    )
);

$parser = new mk\Parser();

$filter = new mk\Filter();
$filter->equal('description', 'PING');

$lql = new mk\Lql(mk\Table::SERVICES);
$lql->columns(array('host_name', 'description', 'state'))->filter($filter);

var_dump($parser->get($client->execute($lql)));

