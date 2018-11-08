# Contributing

All contributions are welcomed. If it's your first time contributing to open source please don't hesitate and just open a Pull Request. Your suggestion/fix isn't stupid, I'll make sure I explain what's wrong with your PR if it wasn't accepted.

## Here are some points to consider:

- Your PR must be making only a single change, if you want to suggest multiple features or fix multiple issues please open separate PRs.
- If you have an idea that will require a lot of work, make sure you suggest it in a new [issue](https://github.com/writingink/wink/issues) first to make sure it's admired before investing time into it.
- If you want to contribute and not sure where to start, check the [road map](https://github.com/writingink/wink#road-map).
- Pull Requests that don't have a clear explanation of what it does will be closed. Sorry :)
- Keep your code clean. Clean means you're proud of how it turned out.

## How to contribute:

Clone wink on your machine, include it in your laravel application via composer using the Path Repository method:

Add this to your composer to JSON

```
"repositories": [
    {
        "type": "path",
        "url": "./../wink"
    }
],
```

And when you require wink, add it like:

```
"writingink/wink": "*@dev"
```

Run `composer update` in your laravel project, then `php artisan wink:install`, and then `php artisan wink:migrate`. Now you have wink running in your laravel project using the files on your machine.

Now head to the `wink` directory and run `yarn` to install the frontend dependencies.

If you make changes to Wink's frontend code, run `yarn run dev`.

Any change you apply should reflect on the test laravel application you setup earlier.