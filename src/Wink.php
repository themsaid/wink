<?php

namespace Wink;

class Wink
{
    /**
     * Get the default JavaScript variables for Wink.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'unsplash_key' => config('services.unsplash.key'),
            'author' => auth('wink')->check() ? auth('wink')->user()->only('name', 'avatar', 'id') : null,
        ];
    }
}
