<?php namespace Brackets\Admin\Tests\Feature\AdminListing;

use Brackets\Admin\AdminListing;
use Brackets\Admin\NotAModelClassException;
use Brackets\Admin\Tests\TestCase;

class ExceptionsTest extends TestCase
{
    /** @test */
    function creating_listing_for_a_class_that_is_not_a_model_should_lead_to_an_exception() {
        try {
            AdminListing::create(static::class);
        } catch (NotAModelClassException $e) {
            return ;
        }

        $this->fail('AdminListing should fail when trying to build for a non Model class');
    }

    /** @test */
    function creating_listing_for_an_integer_class_should_lead_to_an_exception() {
        try {
            AdminListing::create(10);
        } catch (NotAModelClassException $e) {
            return ;
        }

        $this->fail('AdminListing should fail when trying to build for a non Model class');
    }

    /** @test */
    function creating_listing_for_a_non_class_string_should_lead_to_an_exception() {
        try {
            AdminListing::create("Some string that is definitely not a class name");

            // this time we are not checking a NotAModelClassException exception, because it is going to fail a bit earlier
        } catch (\ReflectionException $e) {
            return ;
        }

        $this->fail('AdminListing should fail when trying to build for a non Model class');
    }

}
