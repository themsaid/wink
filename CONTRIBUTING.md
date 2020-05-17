# Contributing

All contributions are welcomed. If it's your first time contributing to open source please don't hesitate and just open a Pull Request. Your suggestion/fix isn't stupid, I'll make sure I explain what's wrong with your PR if it wasn't accepted.

## Here are some points to consider:

- Your PR must be making only a single change, if you want to suggest multiple features or fix multiple issues please open separate PRs.
- If you have an idea that will require a lot of work, make sure you suggest it in a new [issue](https://github.com/themsaid/wink/issues) first to make sure it's admired before investing time into it.
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
"themsaid/wink": "*@dev"
```

Run `composer update` in your laravel project, then `php artisan wink:install`, and then `php artisan wink:migrate`. Now you have wink running in your laravel project using the files on your machine.

Now head to the `wink` directory and run `yarn` to install the frontend dependencies.

If you make changes to Wink's frontend code, run `yarn run dev`. By default, `webpack.mix.js` will compile the assets into a laravel project named `winktest`. If your project has a different name, make sure to modify the path on the `webpack.mix.js` file:

```
    .copy('public', '../winktest/public/vendor/wink')
```
```
    .copy('public', '../NAME_OF_YOUR_PROJECT_FOLDER/public/vendor/wink')
```


Any change you apply should reflect on the test laravel application you setup earlier.
