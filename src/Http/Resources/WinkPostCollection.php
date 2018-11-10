<?php

namespace Wink\Http\Resources;

use Wink\Http\Resources\WinkPost;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WinkPostCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource->toArray();
        return array_merge(
            $data,
            // Force post formatting through resource formatter to ensure nested
            // items like meta get wrapped up as we need.
            ['data' => WinkPost::collection($this->resource)]
        );
    }
}
