<?php

namespace IsaEken\ThemeSystem\Tests;

class BrowserTest extends BrowserTestCase
{
    public function testHelloWorld()
    {
        $this->visit('/hello-world')->see('Hello World');
    }

    public function testMiddleware()
    {
        $this
            ->visit('/middleware')
            ->see('Testing');
    }
}
