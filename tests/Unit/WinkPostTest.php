<?php

namespace Wink\Tests\Unit;

use Wink\WinkPost;
use Wink\WinkAuthor;
use Wink\Tests\TestCase;
use Illuminate\Database\QueryException;

class WinkPostTest extends TestCase
{
    public function test_it_has_unique_id()
    {
        $winkPost = factory(WinkPost::class)->create([
            'id' => 'uuid',
        ]);
        $this->assertSame('uuid', $winkPost->id);

        $this->expectException(QueryException::class);
        factory(WinkPost::class)->create(['id' => 'uuid']);
    }

    public function test_it_has_title()
    {
        $winkPost = factory(WinkPost::class)->create([
            'title' => 'custom title',
        ]);

        $this->assertSame('custom title', $winkPost->title);
    }

    public function test_it_has_unique_slug()
    {
        $winkPost = factory(WinkPost::class)->create([
            'slug' => 'custom-slug',
        ]);
        $this->assertSame('custom-slug', $winkPost->slug);

        $this->expectException(QueryException::class);
        factory(WinkPost::class)->create(['slug' => 'custom-slug']);
    }

    public function test_it_has_excerpt()
    {
        $winkPost = factory(WinkPost::class)->create([
            'excerpt' => 'custom excerpt',
        ]);

        $this->assertSame('custom excerpt', $winkPost->excerpt);
    }

    public function test_it_has_body()
    {
        $winkPost = factory(WinkPost::class)->create([
            'body' => 'custom body',
        ]);

        $this->assertSame('custom body', $winkPost->body);
    }

    public function test_it_is_not_published_by_default()
    {
        $winkPost = factory(WinkPost::class)->create();

        $this->assertFalse($winkPost->published);
    }

    public function test_it_may_have_featured_image()
    {
        $this->assertNull(factory(WinkPost::class)->create()->featured_image);

        $this->assertSame('custom_featured_image', factory(WinkPost::class)->create([
            'featured_image' => 'custom_featured_image',
        ])->featured_image);
    }

    public function test_it_has_author()
    {
        $winkPost = factory(WinkPost::class)->create();

        $this->assertInstanceOf(WinkAuthor::class, $winkPost->author);
    }
}
