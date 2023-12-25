<?php

use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    private string $test = "test";
    private int $int = 123;

    public function testInit()
    {
        $this->assertIsString($this->test);
    }

    public function testInit2()
    {
        $this->assertIsInt($this->int);
    }

    public function testText()
    {
        $this->assertEquals($this->int, "123");
    }
}
