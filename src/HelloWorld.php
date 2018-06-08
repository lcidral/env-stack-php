<?php

namespace lcidral\developstack;


class HelloWorld
{
    private $hello = "Hello";
    private $world = "World";

    /**
     * @return string
     */
    public function getHello(): string
    {
        return $this->hello;
    }

    /**
     * @return string
     */
    public function getWorld(): string
    {
        return $this->world;
    }

    public function getHelloWorld()
    {
        return $this->hello . ' ' . $this->world;
    }
}
