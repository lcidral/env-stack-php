<?php

namespace Tests;

use Mockery;
use lcidral\developstack\HelloWorld;

class HelloWordTest extends \PHPUnit\Framework\TestCase
{

    function testHello()
    {
        $hello = (new HelloWorld())->getHello();
        $this->assertEquals('Hello', $hello);
    }

    function testWorld()
    {
        $world = (new HelloWorld())->getWorld();
        $this->assertEquals('World', $world);
    }

    function testHelloWorld()
    {
        $helloWorld = (new HelloWorld())->getHelloWorld();
        $this->assertEquals('Hello World', $helloWorld);
    }

    public function testShouldHaveHello()
    {
        $mock = Mockery::mock('lcidral\developstack\HelloWorld');
        $mock->shouldReceive('getHello')->once()->andReturn('Hello');

        $this->assertEquals('Hello', $mock->getHello());
    }

    public function testShouldHaveWackosFactory() {

        $mock = Mockery::mock('lcidral\developstack\HelloWorld')
            ->shouldAllowMockingProtectedMethods();

        $mock->shouldReceive('getWackos')
            ->once()
            ->andReturn('Wackos Factory');

        $this->assertEquals('Wackos Factory', $mock->getWackos());
    }
}
