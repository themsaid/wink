<?php

namespace Wink\Http\Controllers;

class ImageUploadsController
{
    /**
     * Upload a new image.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $path = str_replace('public/', 'storage/',
            request()->image->store('/public/wink/images', config('wink.storage_disk'))
        );

        return response()->json([
            'url' => '/'.$path
        ]);
    }
}
