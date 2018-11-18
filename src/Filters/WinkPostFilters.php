<?php

namespace Wink\Filters;

use Wink\WinkTag;
use Carbon\Carbon;
use Wink\WinkAuthor;

class WinkPostFilters extends Filters
{

    protected $filters = ['published', 'scheduled', 'drafts'];

    /**
     * Published posts filter
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function published()
    {
        return $this->builder
            ->where('published', 1)
            ->where('publish_date', '<=', Carbon::now());
    }

    /**
     * Scheduled posts filter
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function scheduled()
    {
        $this->builder->where('publish_date', '>', Carbon::now());
    }

    /**
     * Draft posts filter
     *
     * @return Illuminate\Database\Eloquent\Builder
     */
    protected function drafts()
    {
        $this->builder->where('published', 0);
    }
}