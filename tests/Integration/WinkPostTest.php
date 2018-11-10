<?php

namespace Wink\Tests\Integration;

use Wink\WinkPost;
use Wink\Tests\TestCase;
use Wink\WinkPage;

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

        $winkPostData = $this->winkPostData(['author_id' => $user->id]);

        // the id should be `new` in order to create new post.
        // otherwise will be considered as updating the post.
        $this->json('POST', route('wink.posts.store', 'new'), $winkPostData)->assertStatus(200);

        $this->assertDatabaseHas('wink_posts', $winkPostData);
    }

    /** @test */
    public function auth_users_can_edit_an_existing_post()
    {
        $user = $this->signIn();

        $winkPostData = $this->winkPostData(['author_id' => $user->id]);

        // When a user hit edit post that not exist => throw 404
        $this->json('POST', route('wink.posts.store', 'not-exist-post-id'), $winkPostData)->assertStatus(404);

        // however, if we have a post
        $oldPost = factory(WinkPost::class)->create();

        //the user now can edit it usign its id.
        $this->json('POST', route('wink.posts.store', $oldPost->id), $winkPostData)->assertStatus(200);

        $this->assertDatabaseHas('wink_posts', $winkPostData);
    }
    
    /**
     * create dummy data
     *
     * @return array
     */
    protected function winkPostData($data)
    {
        return array_merge([
            'author_id'    => 1,
            'title'        => 'my amazing post ;p',
            'slug'         => 'post-slug',
            'publish_date' => date(now()),
            'published'    => false,
        ], $data);
    }
}
