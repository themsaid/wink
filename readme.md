
- [Introduction](#introduction)
- [Installation](#installation)
- [Updates](#updates)
- [Themes](#themes)
- [Road map](#road-map)
- [Contributing](#contributing)
- [License](#license)

## Introduction

Wink's only job is to help you write and present your content with style. Wink is built on top of the world's finest PHP framework [Laravel](https://laravel.com), making it easy for everyone to install and maintain on any cloud platform.

<img src="https://themsaid.com/storage/wink/images/PaKOXK0bck5IrbVohbC6zQGxZr4CG31enOUt5n80.png">

## Installation

Wink runs on any Laravel application, it uses a separate database connection and authentication system so that you don't have to modify any of your project code.

To install Wink, require it via Composer:

```sh
composer require writingink/wink
```

Once Composer is done, run the following command:

```sh
php artisan wink:install
```

Check `config/wink.php` and **configure the database connection** wink is going to be using. Then instead of running `php artisan migrate`, run:

```sh
php artisan wink:migrate
```

If you are running Laravel 7, you must install the `laravel/ui` package in order for the authentication system to function properly. Use `composer require laravel/ui "^2.0"`.

Head to `yourproject.test/wink` and use the provided email and password to log in.

Before creating a blog post, make sure you have your image directory set up correctly. The directory is set in the `config/wink.php` and defaults to
`public/wink/images`. If you are installing Wink in a fresh Laravel install, make sure you link your public directory to the storage directory [https://laravel.com/docs/5.7/filesystem#configuration](https://laravel.com/docs/5.7/filesystem#configuration) using this command:

```sh
php artisan storage:link
```

If you want to upload images to S3 with cache-control header, add `CacheControl` option in `config/filesystems.php`. Also, you can specify CDN base url such as CloudFront.

```php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('CDN_URL'),
    'options' => [
        'CacheControl' => 'public, max-age=315360000'
    ],
],
```

(optional) Visit https://unsplash.com/oauth/applications to create a new unsplash app. Grab the 'Access Key' and add it to your `.env` file as `UNSPLASH_ACCESS_KEY`. Lastly, add unsplash to your `config/services.php` file:

```php
'unsplash' => [
    'key' => env('UNSPLASH_ACCESS_KEY'),
],
```

## Updates

Add this command in your deployment script so that wink runs new migrations if any:

```sh
php artisan wink:migrate
```

You may also want to run this command to re-publish the assets:

```sh
php artisan vendor:publish --tag=wink-assets --force
```

## Themes

Wink is shipped with an admin panel that's simple to use. However, we give you full control of how you present the stored content in your interface. Here's an example of how you'd get a list of your posts for a blog home screen:

```php

use Wink\WinkPost;

public function index()
{
    $posts = WinkPost::with('tags')
        ->live()
        ->orderBy('publish_date', 'DESC')
        ->simplePaginate(12);

    return view('blog.index', [
        'posts' => $posts
    ]);
}
```

You can configure your routes in any way you want:

```php
Route::get('/', 'BlogController@index');
// OR
Route::get('/blog', 'BlogController@index');
// OR
Route::domain('blog.mywebsite.com')->get('/', 'BlogController@index');

// And for showing a post
Route::get('/{tag}/{slug}', 'BlogController@post');
// OR
Route::get('/{year}/{month}/{slug}', 'BlogController@post');
```

## Road map

Wink is still under heavy development, I decided to ship it in this early stage so you can help me make it better, however I'm already using it to run multiple websites including my personal blog.

Here's the plan for what's coming:

- [x] Customize Twitter/Facebook cards and SEO metadata.
- [x] Optimize CSS. Move to Tailwind?
- [x] Add text search inside listings.
- [x] Filter posts by status, scheduling, tags, and authors
- [x] Dark mode.
- [ ] Enhance publishing date picker.
- [ ] Create an initial theme that people can use right away. @themsaid
- [ ] Optimize image uploads and allow cropping.
- [ ] Adding image galleries to posts and pages. @themsaid
- [ ] Add tests.

And here are some ideas I'm still not sure about:

- [ ] Email Subscription & auto send emails on new content.
- [ ] Configure roles (Contributor / Admin)
- [ ] Localization
- [ ] Multi-lingual content
- [ ] Attach metadata to posts and pages.


## Contributing

Check our [contribution guide](CONTRIBUTING.md).

## License

Wink is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
