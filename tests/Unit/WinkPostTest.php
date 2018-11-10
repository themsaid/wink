<?php

namespace Wink\Tests\Unit;

use Wink\WinkPost;
use Wink\Tests\TestCase;

class WinkPostTest extends TestCase
{
    /** @test */
    public function it_has_unique_id()
    {
        $winkPost = factory(WinkPost::class)->create(['id' => 'uuid']);
        $this->assertEquals('uuid', $winkPost->id);

        $this->expectException('\Illuminate\Database\QueryException');
        $winkPost = factory(WinkPost::class)->create(['id' => 'uuid']);
    }

    /** @test */
    public function it_has_title()
    {
        $winkPost = factory(WinkPost::class)->create(['title' => 'custom title']);

        $this->assertEquals('custom title', $winkPost->title);
    }

    /** @test */
    public function it_has_unique_slug()
    {
        $winkPost = factory(WinkPost::class)->create(['slug' => 'custom-slug']);
        $this->assertEquals('custom-slug', $winkPost->slug);

        $this->expectException('\Illuminate\Database\QueryException');
        factory(WinkPost::class)->create(['slug' => 'custom-slug']);
    }

    /** @test */
    public function it_has_excerpt()
    {
        $winkPost = factory(WinkPost::class)->create(['excerpt' => 'custom excerpt']);

        $this->assertEquals('custom excerpt', $winkPost->excerpt);
    }

    /** @test */
    public function it_has_body()
    {
        $winkPost = factory(WinkPost::class)->create(['body' => 'custom body']);

        $this->assertEquals('custom body', $winkPost->body);
    }

    /** @test */
    public function it_is_not_published_by_default()
    {
        $winkPost = factory(WinkPost::class)->create();

        $this->assertFalse($winkPost->published);
    }

    /** @test */
    public function it_may_have_featured_image()
    {
        $this->assertNull(
            factory(WinkPost::class)->create()->featured_image
        );

        $this->assertEquals(
            'custom_featured_image',
            factory(WinkPost::class)->create(['featured_image' => 'custom_featured_image'])->featured_image
        );
    }

    /** @test */
    public function it_has_author()
    {
        $winkPost = factory(WinkPost::class)->create();

        $this->assertInstanceOf(\Wink\WinkAuthor::class, $winkPost->author);
    }
}
