## Introduction

Wink's only job is to help you write and present your content with style. Wink is built on top of the world's finest PHP framework [Laravel](https://laravel.com), making it easy for everyone to install and maintain on any cloud platform.

<img src="https://themsaid.com/storage/wink/images/PaKOXK0bck5IrbVohbC6zQGxZr4CG31enOUt5n80.png">

## Installation

Wink runs on any Laravel application, it uses a separate database connection and authentication system so that you don't have to modify any of your project code.

To install Wink, require it via Composer:

```
composer require writingink/wink
```

Once Composer is done, run the following command:

```
php artisan wink:install
```

Check `config/wink.php` and **configure the database connection** wink is going to be using. Then instead of running `php artisan migrate`, run:

```
php artisan wink:migrate
```

Head to `yourproject.test/wink` and use the provided email and password to log in.

## Updates

Add this command in your deployment script so that wink runs new migrations if any:

```
php artisan wink:migrate
```

You may also want to run this command to re-publish the assets:

```
php artisan vendor:publish --tag=wink-assets --force
```

## Themes

Wink is shipped with an admin panel that's simple to use. However, we give you full control of how you present the stored content in your interface. Here's an example of how you'd get a list of your posts for a blog home screen:

```php
public function index()
{
    $posts = WinkPost::with('tags')
        ->published()
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
- [ ] Ability to extract featured image/ social images from images used in post/page.
- [ ] Create an initial theme that people can use right away.
- [ ] Filter posts by status, scheduling, tags, and authors.
- [ ] Adding image galleries to posts and pages.
- [ ] Attach metadata to posts and pages.
- [ ] Link social accounts and automatic post on new published content.
- [ ] Optimize image uploads and allow cropping.
- [ ] Built-in database backups.
- [ ] Dark mode.
- [ ] Add tests.

And here are some ideas I'm still not sure about:

- [ ] Email Subscription & auto send emails on new content.
- [ ] Built-in comments system.
- [ ] Configure roles (Contributor / Admin)
- [ ] Localization
- [ ] Multi-lingual content


## Contributing

Check our [contribution guide](CONTRIBUTING.md).

## License

Wink is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
