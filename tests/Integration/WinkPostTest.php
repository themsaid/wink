<?php

namespace Wink\Tests\Integration;

use Wink\WinkPost;
use Wink\Tests\TestCase;

class WinkPostTest extends TestCase
{
    public function test_a_guest_may_not_hit_any_post_endpoint()
    {
        $this->getJson(route('wink.posts.index'))->assertStatus(401);
        $this->getJson(route('wink.posts.show'))->assertStatus(401);
        $this->postJson(route('wink.posts.store', 'dumy-id'))->assertStatus(401);
        $this->deleteJson(route('wink.posts.delete', 'dumy-id'))->assertStatus(401);
    }

    public function test_auth_users_can_create_new_post()
    {
        $user = $this->signIn();

        $winkPostData = $this->winkPostData(['author_id' => $user->id]);

        // The id should be `new` in order to create new post.
        // otherwise will be considered as updating the post.
        $this->postJson(route('wink.posts.store', 'new'), $winkPostData)->assertStatus(200);

        $this->assertDatabaseHas('wink_posts', $winkPostData);
    }

    public function test_auth_users_can_edit_an_existing_post()
    {
        $user = $this->signIn();

        $winkPostData = $this->winkPostData(['author_id' => $user->id]);

        // When a user hits edit post that not exist => throw 404
        $this->postJson(route('wink.posts.store', 'not-exist-post-id'), $winkPostData)->assertStatus(404);

        // However, if we have a post
        $oldPost = factory(WinkPost::class)->create();

        // The user now can edit it usign its id.
        $this->postJson(route('wink.posts.store', $oldPost->id), $winkPostData)->assertStatus(200);

        $this->assertDatabaseHas('wink_posts', $winkPostData);
    }

    /**
     * Create dummy data.
     *
     * @param array $data
     * @return array
     */
    protected function winkPostData($data)
    {
        return array_merge([
            'author_id' => 1,
            'title' => 'my amazing post ;p',
            'slug' => 'post-slug',
            'publish_date' => now()->toDateTimeString(),
            'published' => false,
        ], $data);
    }
}
