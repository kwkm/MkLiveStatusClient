<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\Stats;

class StatsTest extends \PHPUnit_Framework_TestCase
{
    public function testAllMethod()
    {
        $dump = <<<EOF
Stats: state = 0
Stats: state = 1
Stats: state != 2
Stats: sum execution_time
Stats: min execution_time
Stats: max execution_time
Stats: avg execution_time
Stats: std execution_time
Stats: suminv execution_time
Stats: avginv execution_time
StatsAnd: 1
StatsOr: 2
StatsNegate:

EOF;

        $mock = new Stats();
        $mock->set('state = 0')
            ->equal('state', '1')
            ->notEqual('state', '2')
            ->sum('execution_time')
            ->min('execution_time')
            ->max('execution_time')
            ->avg('execution_time')
            ->std('execution_time')
            ->suminv('execution_time')
            ->avginv('execution_time')
            ->operatorAnd(1)
            ->operatorOr(2)
            ->negate();

        $this->assertEquals(
            $dump,
            join('', $mock->get())
        );
    }
}
