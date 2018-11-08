<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wink Database Connection
    |--------------------------------------------------------------------------
    | This is the database connection you want Wink to use while storing &
    | reading your content. By default Wink assumes you've prepared a
    | new connection called "wink". However, you can change that
    | to anything you want.
    */

    'database_connection' => env('WINK_DB_CONNECTION', 'wink'),

    /*
    |--------------------------------------------------------------------------
    | Wink Uploads Disk
    |--------------------------------------------------------------------------
    | This is the storage disk Wink will use to put file uploads, you can use
    | any of the disks defined in your config/filesystems.php file.
    */

    'storage_disk' => env('WINK_STORAGE_DISK', 'local'),

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
];
