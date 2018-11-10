<?php

namespace Wink\Http\Resources;

use Wink\Http\Resources\WinkMetaCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class WinkPost extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data = $this->resource->toArray();
        $data['meta'] = new WinkMetaCollection($this->meta);
        return $data;
    }
}
