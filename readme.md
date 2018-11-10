## Introduction

Wink's only job is to help you write and present your content with style. Wink is built on top of the world's finest PHP framework [Laravel](https://laravel.com), making it easy for everyone to install and maintain on any cloud platform.

<img src="https://themsaid.com/storage/wink/images/PaKOXK0bck5IrbVohbC6zQGxZr4CG31enOUt5n80.png">

## Installation

Wink runs on any Laravel application, and can leverage the default User model and authentication for a seamless integration with your project code.

To install Wink, require it via Composer:

```
composer require writingink/wink
```

If you haven't already, you'll need to run the command that scaffolds the base Laravel authentication logic and assets:

```
php artisan make:auth
```

When it's done, run the Wink install command:

```
php artisan wink:install
```

Finally run `php artisan migrate` to migrate your database and head to `yourproject.test/register` to register an account.

After logging in, go to `yourproject.test/wink` to access Wink's admin panel.

## Themes

Wink is shipped with an admin panel that's simple to use. However, we give you full control of how you present the stored content in your interface. Here's an example of how you'd get a list of your posts for a blog home screen:

```php
public function index()
{
    $posts = WinkPost::with('tags')
        ->where('published', true)
        ->where('publish_date', '<=', now()->toDateTimeString())
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

- [ ] Create an initial theme that people can use right away.
- [ ] Add text search inside listings.
- [ ] Filter posts by status, scheduling, tags, and authors.
- [ ] Adding image galleries to posts and pages.
- [ ] Attach metadata to posts and pages.
- [ ] Customize Twitter/Facebook cards and SEO metadata.
- [ ] Link social accounts and automatic post on new published content.
- [ ] Optimize image uploads and allow cropping.
- [ ] Built-in database backups.
- [ ] Design a better logo.
- [ ] Optimize CSS. Move to Tailwind?
- [ ] Dark mode.
- [ ] Add tests.

And here are some ideas I'm still not sure about:

- [ ] Convert to Tailwind
- [ ] Email Subscription & auto send emails on new content.
- [ ] Built-in comments system.
- [ ] Configure roles (Contributor / Admin)
- [ ] Localization
- [ ] Multi-lingual content


## Contributing

Check our [contribution guide](CONTRIBUTING.md).

## License

Wink is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
