<?php namespace Brackets\Admin\Tests\Feature\AdminListing;

use Brackets\Admin\Tests\TestCase;
use Illuminate\Database\QueryException;

class OrderingTest extends TestCase
{
    /** @test */
    function listing_should_provide_ability_to_sort_by_name() {
        $result = $this->listing
            ->attachOrdering('name')
            ->get();

        $this->assertEquals('Alpha', $result->first()->name);
    }

    /** @test */
    function listing_should_provide_ability_to_change_sort_order() {
        $result = $this->listing
            ->attachOrdering('name', 'desc')
            ->get();

        $this->assertEquals('Alpha', $result->last()->name);
        $this->assertEquals('Zeta 9', $result->first()->name);
    }

    /** @test */
    function sorting_by_not_existing_column_should_lead_to_an_error() {

        try {
            $this->listing
                ->attachOrdering('not_existing_column_name')
                ->get();
        } catch (QueryException $e) {
            return ;
        }

        $this->fail("Sorting by not existing column should lead to an exception");

    }

    /** @test */
    function translated_listing_can_be_sorted_by_translated_column() {
        $result = $this->translatedListing
            ->attachOrdering('name->en')
            ->get();

        $model = $result->first();

        $this->assertEquals('2000-06-01 00:00:00', $model->published_at);
        $this->assertEquals('Alpha', $model->name);
        $this->assertEquals('red', $model->color);
        $this->assertEquals('red', $model->getTranslation('color', 'en'));
    }

    /** @test */
    function translated_listing_supports_querying_only_some_columns() {
        $result = $this->translatedListing
            ->attachOrdering('name->en')
            ->get(['published_at', 'name']);

        $model = $result->first();

        $this->assertEquals('2000-06-01 00:00:00', $model->published_at);
        $this->assertEquals('Alpha', $model->name);
        $this->assertEquals(null, $model->color);
        $this->assertEquals('Alpha', $model->getTranslation('name', 'en'));
        $this->assertEquals(null, $model->getTranslation('color', 'en'));
    }

    /** @test */
    function translated_listing_can_work_with_locales() {
        // FIXME this is temp fix, until Spatie add ability to set locale on model dynamically
        app()->setLocale('sk');

        $result = $this->translatedListing
            ->attachOrdering('name->sk')
//            ->setLocale('sk')
            ->get();

        $this->assertCount(10, $result);

        $model = $result->first();

        $this->assertEquals('2000-06-01 00:00:00', $model->published_at);
        $this->assertEquals('Alfa', $model->name);
        $this->assertEquals('cervena', $model->color);
        $this->assertEquals('cervena', $model->getTranslation('color', 'sk'));
    }

}
