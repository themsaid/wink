<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wink Database Connection
    |--------------------------------------------------------------------------
    |
    | This is the database connection you want Wink to use while storing &
    | reading your content. By default Wink assumes you've prepared a
    | new connection called "wink". However, you can change that
    | to anything you want.
    |
    */

    'database_connection' => env('WINK_DB_CONNECTION', 'wink'),

    /*
    |--------------------------------------------------------------------------
    | Wink Uploads Disk
    |--------------------------------------------------------------------------
    |
    | This is the storage disk Wink will use to put file uploads, you can use
    | any of the disks defined in your config/filesystems.php file. You may
    | also configure the path where the files should be stored.
    |
    */

    'storage_disk' => env('WINK_STORAGE_DISK', 'local'),

    'storage_path' => env('WINK_STORAGE_PATH', 'public/wink/images'),

    /*
    |--------------------------------------------------------------------------
    | Wink Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Wink will be accessible from. By default it
    | will be accessible on the same domain as your app.
    |
    */

    'domain' => env('WINK_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Wink Path
    |--------------------------------------------------------------------------
    |
    | This is the URI prefix where Wink will be accessible from. Feel free to
    | change this path to anything you like.
    |
    */

    'path' => env('WINK_PATH', 'wink'),

    /*
    |--------------------------------------------------------------------------
    | Wink Middleware Group
    |--------------------------------------------------------------------------
    |
    | This is the middleware group that Wink uses.
    |
    */

    'middleware_group' => env('WINK_MIDDLEWARE_GROUP', 'web'),

    /*
    |--------------------------------------------------------------------------
    | Wink Post Preview Path
    |--------------------------------------------------------------------------
    |
    | Wink uses this path to display a preview link in the editor. While
    | building the link tag, the {postSlug} placeholder will be replaced
    | by the actual post slug.
    |
    */

    'preview_path' => '/{postSlug}',

    'table_name' => [
        'wink_authors' => 'wink_authors',
        'wink_pages' => 'wink_pages',
        'wink_posts' => 'wink_posts',
        'wink_posts_tags' => 'wink_posts_tags',
        'wink_tags' => 'wink_tags',
    ],
    'column_names' => [
        'author_id' => 'author_id'
    ],
    'models' => [
        'author' => 'Wink\WinkAuthor'
    ]
];
