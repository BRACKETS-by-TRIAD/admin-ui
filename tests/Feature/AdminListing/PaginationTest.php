<?php namespace Brackets\Admin\Tests\Feature\AdminListing;

use Brackets\Admin\Tests\TestCase;
use Illuminate\Database\QueryException;

class PaginationTest extends TestCase
{
    /** @test */
    function listing_provides_pagination() {
        $result = $this->listing
            ->attachOrdering('name')
            ->attachPagination(2, 3)
            ->get();

        $this->assertCount(3, $result->getCollection());
        $this->assertEquals(10, $result->total());
        $this->assertEquals(3, $result->perPage());
        $this->assertEquals(2, $result->currentPage());
        $this->assertEquals(4, $result->lastPage());
        $this->assertEquals('Zeta 3', $result->getCollection()->first()->name);
    }

    /** @test */
    function listing_pagination_works_on_translatable_model_too() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->attachPagination(2, 3)
            ->get();

        $this->assertCount(3, $result->getCollection());
        $this->assertEquals(10, $result->total());
        $this->assertEquals(3, $result->perPage());
        $this->assertEquals(2, $result->currentPage());
        $this->assertEquals(4, $result->lastPage());
        $this->assertEquals('Zeta 3', $result->getCollection()->first()->name);
    }

    /** @test */
    function listing_pagination_works_on_translatable_model_with_locale_sk() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->setLocale('sk')
            ->attachPagination(1, 3)
            ->get();

        $this->assertCount(1, $result->getCollection());
        $this->assertEquals(1, $result->total());
        $this->assertEquals(3, $result->perPage());
        $this->assertEquals(1, $result->currentPage());
        $this->assertEquals(1, $result->lastPage());
        $this->assertEquals('Alfa', $result->getCollection()->first()->name);
    }

}
