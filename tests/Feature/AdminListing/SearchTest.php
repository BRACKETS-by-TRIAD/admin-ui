<?php namespace Brackets\Admin\Tests\Feature\AdminListing;

use Brackets\Admin\Tests\TestCase;
use Illuminate\Database\QueryException;

class SearchTest extends TestCase
{
    /** @test */
    function you_can_search_among_text_fields_and_id() {
        $result = $this->listing
            ->attachOrdering('name')
            ->attachSearch('alpha', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(1, $result);
    }

    /** @test */
    function searching_for_a_repeated_term() {
        $result = $this->listing
            ->attachOrdering('name')
            ->attachSearch('zeta', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(9, $result);
    }

    /** @test */
    function searching_not_existing_query_should_return_empty_response() {
        $result = $this->listing
            ->attachOrdering('name')
            ->attachSearch('not-existing-search-term', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    function searching_only_in_color() {
        $result = $this->listing
            ->attachOrdering('name')
            ->attachSearch('alpha', ['id', 'color'])
            ->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    function searching_a_number() {
        $result = $this->listing
            ->attachOrdering('name')
            ->attachSearch(1, ['id', 'name'])
            ->get();

        $this->assertCount(2, $result);
    }

    /** @test */
    function translations_you_can_search_among_text_fields_and_id() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->attachSearch('alpha', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(1, $result);
    }

    /** @test */
    function you_cannot_search_depending_on_a_different_locale() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->setLocale('sk')
            ->attachSearch('alpha', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    function searching_a_number_in_translated_model() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->attachSearch(1, ['id', 'name'])
            ->get();

        $this->assertCount(2, $result);
    }

    /** @test */
    function searching_a_number_in_translated_model_for_sk() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->setLocale('sk')
            ->attachSearch(1, ['id', 'name'])
            ->get();

        $this->assertCount(1, $result);
    }

    /** @test */
    function searching_for_a_multiple_terms_zero() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->attachSearch('alpha zeta', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(0, $result);
    }

    /** @test */
    function searching_for_a_multiple_terms_one() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->attachSearch('zeta 1', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(1, $result);
    }

    /** @test */
    function searching_for_a_multiple_terms_many() {
        $result = $this->translatedListing
            ->attachOrdering('name')
            ->attachSearch('zeta yellow', ['id', 'name', 'color'])
            ->get();

        $this->assertCount(9, $result);
    }

}
