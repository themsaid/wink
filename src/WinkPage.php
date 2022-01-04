<?php

namespace Wink;

use Carbon\CarbonInterface;

/**
 * @property string $id
 * @property string $slug
 * @property string $title
 * @property-write string $body
 * @property-read string $content
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $created_at
 * @property array<mixed>|null $meta
 */
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
     * @return string
     */
    public function getContentAttribute()
    {
        return $this->body;
    }
}
