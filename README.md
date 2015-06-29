# mkLivestatus Client

## Client Setting

### In the case of localhost

```PHP
$client = new Client(
    array(
        'socketType' => 'unix',
        'socketPath' => '/var/run/nagios/rw/live',
    )
);
```

### In the case of remote network

```PHP
$client = new Client(
    array(
        'socketType' => 'unix',
        'socketPath' => '/var/run/nagios/rw/live',
    )
);
```

