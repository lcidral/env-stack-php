<?php

namespace Tests;

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
}
