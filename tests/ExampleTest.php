<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    public function testBasicExample()
    {
        $this->post('/wepayapi/v1/test', ['name' => 'Sally']);
    }
}
