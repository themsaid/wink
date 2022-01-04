<?php

namespace Wink;

use Closure;
use Illuminate\Support\HtmlString;
use League\CommonMark\GithubFlavoredMarkdownConverter;

class Wink
{
    /**
     * The callback to use for rendering markdown.
     *
     * @var Closure|null
     */
    private static $markdownCallback = null;

    /**
     * Get the default JavaScript variables for Wink.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'unsplash_key' => config('services.unsplash.key'),
            'path' => config('wink.path'),
            'preview_path' => config('wink.preview_path'),
            'author' => auth('wink')->check() ? auth('wink')->user()->only('name', 'avatar', 'id') : null,
            'default_editor' => config('wink.editor.default'),
        ];
    }

    /**
     * Provide a callback that is passed a markdown string and returns
     * a HtmlString to be presented. Setting this callback will
     * override the default markdown renderer.
     */
    public static function renderMarkdownUsing(Closure $callback): void
    {
        static::$markdownCallback = $callback;
    }

    public static function getMarkdownRenderer(): Closure
    {
        return static::$markdownCallback ?? function ($body) {
            $converter = new GithubFlavoredMarkdownConverter([
                'allow_unsafe_links' => false,
            ]);

            return new HtmlString($converter->convertToHtml($body));
        };
    }
}
