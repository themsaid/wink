const mix = require('laravel-mix');
const webpack = require('webpack');
const tailwindcss = require('tailwindcss');

mix
    .options({
        uglify: {
            uglifyOptions: {
                compress: {
                    drop_console: true,
                }
            }
        },
        processCssUrls: false,
    })
    .webpackConfig({
        plugins: [
            new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/)
        ],
    });

mix
    .setPublicPath('public')
    .js('resources/js/app.js', 'public')
    .sass('resources/sass/light.scss', 'public', {}, [tailwindcss('./light.js')])
    .version();

mix
    .sass('resources/sass/dark.scss', 'public', {}, [tailwindcss('./dark.js')])
    .version()
    .copy('resources/favicon.png', 'public')
    .copy('public', '../winktest/public/vendor/wink');
