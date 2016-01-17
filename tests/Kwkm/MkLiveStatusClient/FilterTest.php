<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\Filter;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testAllMethod()
    {
        $dump = <<<EOF
Filter: state = 0
Filter: state = 1
Filter: state != 2
Filter: state ~ 3
Filter: state !~ 4
Filter: state < 5
Filter: state > 6
Filter: state <= 7
Filter: state >= 8
And: 1
Or: 2
Negate:

EOF;

        $mock = new Filter();
        $mock->set('state = 0')
            ->equal('state', '1')
            ->notEqual('state', '2')
            ->match('state', '3')
            ->notMatch('state', '4')
            ->less('state', '5')
            ->greater('state', '6')
            ->lessEqual('state', '7')
            ->greaterEqual('state', '8')
            ->operatorAnd(1)
            ->operatorOr(2)
            ->negate();

        $this->assertEquals(
            $dump,
            join('', $mock->get())
        );

        $mock->reset();

        $this->assertEquals(
            '',
            join('', $mock->get())
        );
    }
}
