<?php

namespace Brackets\Admin\MediaLibrary\Test\HasMediaTrait;

use Brackets\Admin\MediaLibrary\Test\TestCase;

class HasMediaCollectionsTest extends TestCase
{

    /** @test */
    public function it_returns_a_media_collection_as_a_laravel_collection()
    {
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $this->testModelWithCollections->getMediaCollections());
    }

    /** @test */
    public function not_authorized_user_can_get_public_media() 
    {
        $this->assertCount(0, $this->testModelWithCollections->getMedia('gallery'));

        $this->testModelWithCollections->addMedia($this->getTestFilesDirectory('test.jpg'))
                                       ->preservingOriginal()
                                       ->toMediaCollection('gallery', 'media');
        
        $this->testModelWithCollections = $this->testModelWithCollections->fresh();

        $media = $this->testModelWithCollections->getMedia('gallery');

        $this->assertCount(1, $media);
        
        $response = $this->call('GET', $media->first()->getUrl());
        $response->assertStatus(200);
    }

    /** @test */
    public function not_authorized_user_cant_get_protected_media() 
    {
        $this->assertCount(0, $this->testModelWithCollections->getMedia('documents'));

        $this->testModelWithCollections->addMedia($this->getTestFilesDirectory('test.jpg'))
                                       ->preservingOriginal()
                                       ->toMediaCollection('documents', 'media-protected');
        
        $this->testModelWithCollections = $this->testModelWithCollections->fresh();

        $media = $this->testModelWithCollections->getMedia('documents');

        $this->assertCount(1, $media);

        $response = $this->call('GET', $media->first()->getUrl());
        $response->assertStatus(404);
    }

    /** @test */
    public function check_media_collections_count()
    {   
        $this->assertCount(0, $this->testModel->getMediaCollections()); 
        $this->assertCount(2, $this->testModelWithCollections->getMediaCollections()); 
    }




    // /** @test */
    // public function is_default_component_thumbs_creation_defined()
    // {   
    //     $found = $this->testModelWithCollections->getImageMediaCollections();

    //     $this->assertFalse($found);
    // }

    // /** @test */
    // public function it_returns_false_for_an_empty_collection()
    // {
    //     $this->assertFalse($this->testModel->hasMedia());
    // }
    // /** @test */
    // public function it_returns_true_for_a_non_empty_collection()
    // {
    //     $this->testModel->addMedia($this->getTestJpg())->toMediaCollection();
    //     $this->assertTrue($this->testModel->hasMedia());
    // }
    // /** @test */
    // public function it_returns_true_for_a_non_empty_collection_in_an_unsaved_model()
    // {
    //     $this->testUnsavedModel->addMedia($this->getTestJpg())->toMediaCollection();
    //     $this->assertTrue($this->testUnsavedModel->hasMedia());
    // }
    // /** @test */
    // public function it_returns_true_if_any_collection_is_not_empty()
    // {
    //     $this->testModel->addMedia($this->getTestJpg())->toMediaCollection('images');
    //     $this->assertTrue($this->testModel->hasMedia('images'));
    // }
    // /** @test */
    // public function it_returns_false_for_an_empty_named_collection()
    // {
    //     $this->assertFalse($this->testModel->hasMedia('images'));
    // }
    // /** @test */
    // public function it_returns_true_for_a_non_empty_named_collection()
    // {
    //     $this->testModel->addMedia($this->getTestJpg())->toMediaCollection('images');
    //     $this->assertTrue($this->testModel->hasMedia('images'));
    //     $this->assertFalse($this->testModel->hasMedia('downloads'));
    // }
}