<?php

namespace Brackets\AdminUI\Tests\Feature;

use Brackets\AdminUI\Tests\TestCase;

class SimpleAdminTest extends TestCase
{
    /**
     * @return void
     */
    public function test_if_can_display_an_admin_listing()
    {
        $this->visit('/admin/test/index');

        $this->assertContains("<title>Craftable - Craftable</title>", $this->response->getContent());

        $this->assertContains("Here should be some custom code :)", $this->response->getContent());

        $this->assertContains("</html>", $this->response->getContent());

    }

}
