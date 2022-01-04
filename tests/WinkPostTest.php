<?php

use Illuminate\Support\HtmlString;
use Wink\Wink;
use Wink\WinkPost;

it('can parse markdown', function () {
    $post = new WinkPost(['body' => '# Hello World', 'markdown' => true]);

    expect($post->content->__toString())->toBe("<h1>Hello World</h1>\n");
});

it('can configure the markdown parser', function () {
    Wink::parseMarkdownUsing(function (string $body) {
        return new HtmlString("<h1>{$body}</h1>");
    });

    $post = new WinkPost(['body' => 'Foo Bar', 'markdown' => true]);

    expect($post->content->__toString())->toBe('<h1>Foo Bar</h1>');
});
