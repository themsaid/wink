# Upgrade Guide

## Upgrading To 1.0 From 0.x

Notice: The package name has changed from `writingink/wink` to `themsaid/wink`.

To upgrade to version 1.0, you need to run the following commands

```sh
php artisan wink:migrate
php artisan vendor:publish --tag=wink-assets --force
```

In addition to this, make sure you render your posts and pages using `$post->content` and `$page->content` instead of `->body`.
