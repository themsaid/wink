<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Wink User Model
    |--------------------------------------------------------------------------
    | This is the Eloquent model Wink will use to retrieve posts and other
    | author related resources. It's often the Laravel's User model, but
    | you can change to whatever you want. It must, however, use the
    | IsAuthor trait.
    */

    'author_model' => App\User::class,

    /*
    |--------------------------------------------------------------------------
    | Wink User Table
    |--------------------------------------------------------------------------
    | This is the users table Wink will interact in the database. You can
    | change this to whatever table you need.
    */

    'author_table' => 'users',

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
