<?php

namespace Brackets\Admin\Tests\Feature;

use Brackets\Admin\Tests\TestCase;

class SimpleAdminTest extends TestCase
{
    /**
     * @return void
     */
    public function test_if_can_display_a_listing()
    {
        $this->visit('/admin/test');

        $this->assertContains("<title>Admin Interface</title>", $this->response->getContent());

        $this->assertContains("Here should be some custom index code :)", $this->response->getContent());

        $this->assertContains("</html>", $this->response->getContent());

    }
}
