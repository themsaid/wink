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
}
