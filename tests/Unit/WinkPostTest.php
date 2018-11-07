<?php

namespace Wink\Tests\Unit;

use Wink\WinkPost;
use Wink\Tests\TestCase;

class WinkPostTest extends TestCase
{
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
}
