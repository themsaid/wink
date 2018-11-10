<?php

namespace Wink\Http\Resources;

use Wink\Http\Resources\WinkMeta;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WinkMetaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $collection = WinkMeta::collection($this->resource);

        // Merge all meta values up to the top level to make key-based access easier
        $meta = collect([]);
        foreach ($collection as $item) {
            $meta[$item->key] = $item->value;
        }

        return $meta;
    }
}
