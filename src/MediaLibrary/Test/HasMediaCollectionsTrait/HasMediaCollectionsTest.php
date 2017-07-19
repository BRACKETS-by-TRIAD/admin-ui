<?php

namespace Brackets\Admin\MediaLibrary\Test\HasMediaTrait;

use Brackets\Admin\MediaLibrary\Test\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;

class HasMediaCollectionsTest extends TestCase
{

    /** @test */
    public function it_returns_a_media_collection_as_a_laravel_collection()
    {
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $this->testModel->getMediaCollections());
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $this->testModelWithCollections->getMediaCollections());
    }

    /** @test */
    public function not_authorized_user_can_get_public_media() 
    {
        $this->assertCount(0, $this->testModelWithCollections->getMedia('gallery'));

        $request = $this->getRequest([
            'files' => [
                [
                    'collection' => 'gallery',
                    'name'       => 'test',
                    'width'      => 200,
                    'height'     => 200,
                    'model'      => 'Brackets\Admin\MediaLibrary\Test\TestModelWithCollections',
                    'path'       => 'test.jpg'
                ]
            ]
        ]);

        $this->testModelWithCollections->processMedia(collect($request->get('files')));
        $this->testModelWithCollections = $this->testModelWithCollections->fresh();

        $media = $this->testModelWithCollections->getMedia('gallery');

        $this->assertCount(1, $media);

        $response = $this->call('GET', $media->first()->getUrl());

        //FIXME: always 404
        $response->assertStatus(200);
    }

    /** @test */
    public function not_authorized_user_cant_get_protected_media() 
    {
        $this->assertCount(0, $this->testModelWithCollections->getMedia('documents'));

         $request = $this->getRequest([
            'files' => [
                [
                    'collection' => 'documents',
                    'name'       => 'test',
                    'width'      => 200,
                    'height'     => 200,
                    'model'      => 'Brackets\Admin\MediaLibrary\Test\TestModelWithCollections',
                    'path'       => 'test.jpg'
                ]
            ]
        ]);

        $this->testModelWithCollections->processMedia(collect($request->get('files')));
        $this->testModelWithCollections = $this->testModelWithCollections->fresh();

        $media = $this->testModelWithCollections->getMedia('documents');

        $this->assertCount(1, $media);

        $response = $this->call('GET', $media->first()->getUrl());
        $response->assertStatus(403);
    }

    /** @test */
    public function check_media_collections_count()
    {   
        $this->assertCount(0, $this->testModel->getMediaCollections()); 
        $this->assertCount(2, $this->testModelWithCollections->getMediaCollections()); 
    }

    private function getRequest($data) { 
        return Request::create('test', 'GET', $data);        
    }

    private function getRequestWithFile($data) { 
        $file = new UploadedFile($data['path'], $data['name'], 'image/jpeg', filesize($data['path']), null, true);
        return Request::create('test', 'GET', $data, [], [$file], [], []);        
    }
}