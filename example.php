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

$lql = new mk\Lql();
$lql->table(mk\Table::SERVICES)
    ->columns(array('host_name', 'description', 'state'))->
    filterEqual('description', 'PING');

var_dump($parser->get($client->execute($lql)));

