<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testFoo()
    {
        sleep(1);
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBar()
    {
        sleep(1);
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBaz()
    {
        sleep(1);
        $this->assertTrue(true);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testWritingToStderr()
    {
        sleep(1);
        fwrite(STDERR, "MESSAGE FOR STDERR 1\n");
        sleep(1);
        fwrite(STDERR, "MESSAGE FOR STDERR 2\n");
        $this->assertTrue(true);
    }
}
