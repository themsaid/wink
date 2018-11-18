<?php 

namespace Wink\Filters;

use \Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Applies the filters to the query
     *
     * @param Illuminate\Database\Eloquent\Builder $builder
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        foreach($this->filters as $filter) {
            if (! $this->hasFilter($filter)) continue;
            $this->$filter($this->request->$filter);
        }

        return $this->builder;
    }

    /**
     * Identify if given filter should apply to the query
     *
     * @param String $filter
     * @return boolean
     */
    private function hasFilter($filter)
    {
        return method_exists($this, $filter) && $this->request->has($filter);
    }
}