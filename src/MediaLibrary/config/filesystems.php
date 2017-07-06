<?php
return [
    'media' => [
        'driver' => 'local',
        'root'   => public_path().'/media',
    ],

    'media-protected' => [
        'driver' => 'local',
        'root'   => storage_path().'/app/media',
    ],
];