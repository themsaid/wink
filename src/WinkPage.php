<?php

namespace Wink;

use Wink\WinkCategory;

class WinkPage extends AbstractWinkModel
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
    protected $table = 'wink_pages';

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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'body' => 'string',
        'meta' => 'array',
    ];

    /**
     * Get the renderable page content.
     *
     * @return HtmlString
     */
    public function getContentAttribute()
    {
        return $this->body;
    }

    public function categories()
    {
        return $this->morphToMany(WinkCategory::class, 'categoriable', 'wink_categoriables', 'categoriable_id', 'category_id');
    }
}
