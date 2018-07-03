<?php

namespace lcidral\developstack;


class HelloWorld
{
    private $hello = "Hello";
    private $world = "World";

    /**
     * @return string
     */
    public function getHello()
    {
        return $this->hello;
    }

    /**
     * @return string
     */
    public function getWorld()
    {
        return $this->world;
    }

    public function getHelloWorld()
    {
        return $this->hello . ' ' . $this->world;
    }
}
