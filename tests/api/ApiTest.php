<?php

namespace Tests;

use lcidral\developstack\HelloWorld;

class ApiTest extends BaseTestCase
{
    public function testGetWithoutHelloWorld()
    {
        $response = $this->runApp('GET', '/');

        $helloWorld = (new HelloWorld())->getHelloWorld();

        $this->assertContains('{"hello":"Hello","world":"World"}', (string)$response->getBody());
        $this->assertNotContains($helloWorld, (string)$response->getBody());
    }

    public function testGetWithHelloWorld()
    {
        $response = $this->runApp('GET', '/hello');

        $helloWorld = (new HelloWorld())->getHelloWorld();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains($helloWorld, (string)$response->getBody());
    }

    public function testPostNotAllowed()
    {
        $response = $this->runApp('POST', '/', ['test']);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertContains('Method not allowed', (string)$response->getBody());
    }
}