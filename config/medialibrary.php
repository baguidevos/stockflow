<?php
// config/medialibrary.php

return [
    'disk_name' => env('MEDIA_DISK', 'public'),

    'media_model' => Spatie\MediaLibrary\MediaCollections\Models\Media::class,

    'collections' => [
        'images' => [
            'disk' => 'public',
            'directory' => 'products/images',
            'media_name_regex' => '/^[a-zA-Z0-9]+(-\w+)*$/',
            'conversions' => ['thumb', 'medium', 'large'],
            'responsive-images' => true,
        ],

        'documents' => [
            'disk' => 'private',
            'directory' => 'products/documents',
        ],

        'avatar' => [
            'disk' => 'public',
            'directory' => 'avatars',
            'single_file' => true,
        ],
    ],

    'image_optimization' => [
        'thumb' => [
            'format' => 'jpg',
            'quality' => 80,
            'max_width' => 300,
            'max_height' => 300,
        ],
        'medium' => [
            'format' => 'jpg',
            'quality' => 85,
            'max_width' => 800,
        ],
        'large' => [
            'format' => 'jpg',
            'quality' => 90,
            'max_width' => 1200,
        ],
    ],
];