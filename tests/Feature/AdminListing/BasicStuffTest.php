<?php namespace Brackets\Admin\Tests\Feature\AdminListing;

use Brackets\Admin\Tests\TestCase;

class BasicStuffTest extends TestCase
{
    /** @test */
    function listing_should_return_whole_collection_when_nothing_was_set() {
        $result = $this->listing
            ->get();

        $this->assertCount(10, $result);
        $model = $result->first();
        $this->assertArrayHasKey('id', $model);
        $this->assertArrayHasKey('name', $model);
        $this->assertArrayHasKey('color', $model);
        $this->assertArrayHasKey('number', $model);
        $this->assertArrayHasKey('published_at', $model);
    }

    /** @test */
    function ability_to_specify_columns_to_filter() {
        $result = $this->listing
            ->get(['name', 'color']);

        $this->assertCount(10, $result);
        $model = $result->first();
        $this->assertArrayNotHasKey('id', $model);
        $this->assertArrayHasKey('name', $model);
        $this->assertArrayHasKey('color', $model);
        $this->assertArrayNotHasKey('number', $model);
        $this->assertArrayNotHasKey('published_at', $model);
    }

}
