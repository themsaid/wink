<?php

namespace Wink\Tests\Integration;

use Wink\WinkPost;
use Wink\Tests\TestCase;

class WinkPostTest extends TestCase
{
    /** @test */
    public function a_guest_may_not_hit_any_post_endpoint()
    {
        $this->json('GET', route('wink.posts.index'))->assertStatus(401);
        $this->json('GET', route('wink.posts.show'))->assertStatus(401);
        $this->json('POST', route('wink.posts.store', 'dumy-id'))->assertStatus(401);
        $this->json('DELETE', route('wink.posts.delete', 'dumy-id'))->assertStatus(401);
    }

    /** @test */
    public function auth_users_can_create_new_post()
    {
        $user = $this->signIn();

        $data = [
            'author_id'    => $user->id,
            'title'        => 'my amazing post ;p',
            'slug'         => 'post-slug',
            'publish_date' => date(now()),
            'published'    => false,
        ];

        $this->json('POST', route('wink.posts.store', 'new'), $data)->assertStatus(200);

        $this->assertDatabaseHas('wink_posts', $data);
    }
}
