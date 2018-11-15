<?php

namespace Wink;

use Illuminate\Database\Eloquent\Model;

class WinkPost extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wink_posts';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    public $dates = [
        'publish_date'
    ];

    /**
     * The tags the post belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tags()
    {
        return $this->belongsToMany(WinkTag::class, 'wink_posts_tags', 'post_id', 'tag_id');
    }

    /**
     * The post author.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function author()
    {
        return $this->belongsTo(WinkAuthor::class, 'author_id');
    }

    /**
     * Get the current connection name for the model.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return config('wink.database_connection');
    }

    /**
     * Get the featured image.
     *
     * @param  string $value
     * @return string
     */

    public function getFeaturedImageAttribute($value)
    {
        if (is_null($value)) {
            return $value;
        }

        return \Storage::disk(config('wink.storage_disk'))->url($value);
    }

    /**
     * Set the feature image of the post
     *
     * @param  string $value
     * @return void
     */
    public function setFeaturedImageAttribute($value)
    {
        $prefixUrl = \Storage::disk('public_dev')->getDriver()->getConfig()->get('url') .'/';

        $this->attributes['featured_image'] = $value ? str_replace($prefixUrl, '', $value) : null;
    }
}
