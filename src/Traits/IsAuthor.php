<?php

namespace Wink\Traits;

use Wink\WinkPost;

trait IsAuthor
{
    /**
     * The posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(WinkPost::class, 'author_id');
    }

    /**
     * Get the authors's avatar.
     *
     * @param  string $value
     * @return string
     */
    public function getAvatarAttribute($value)
    {
        return $value ?: 'https://secure.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . '?s=80';
    }
}
