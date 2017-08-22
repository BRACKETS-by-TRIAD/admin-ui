<?php

namespace Brackets\Admin\Tests\Feature;

use Brackets\Admin\Tests\TestCase;

class DummyTest extends TestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->visit('/admin/test');
        $response->dump();
    }
}
