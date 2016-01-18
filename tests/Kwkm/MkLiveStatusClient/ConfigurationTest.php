<?php
namespace Kwkm\MkLiveStatusClient\Tests;

use Kwkm\MkLiveStatusClient\Configuration;
use \InvalidArgumentException;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testUnspoortOption()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'unix',
                'socketHoge' => 'fuga'
            ));
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testUnspoortSocketType()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'hoge',
            ));
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testBlankSocketFile()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'unix',
                'socketPath' => '',
            ));
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testNonSocketFile()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'unix',
                'socketPath' => 'hoge',
            ));
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testBlankSocketAddress()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'tcp',
                'socketPort' => '5667',
            ));
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testBlankSocketPort()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'tcp',
                'socketAddress' => '127.0.0.1',
            ));
        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }

    public function testGetter()
    {
        try {
            $mock = new Configuration(array(
                'socketType' => 'tcp',
                'socketAddress' => '127.0.0.1',
                'socketPort' => '5667',
            ));

            $this->assertEquals(
                'tcp',
                $mock->socketType
            );

            $test = $mock->socketHoge;

        } catch (InvalidArgumentException $e) {
            return;
        }

        $this->fail('An expected exception has not been raised.');
    }
}
