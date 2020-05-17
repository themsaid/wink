<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class ImageUploadsController
{
    /**
     * Upload a new image.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $path = request()->image->store(config('wink.storage_path'), [
            'disk' => config('wink.storage_disk'),
            'visibility' => 'public',
        ]
        );

        return response()->json([
            'url' => Storage::disk(config('wink.storage_disk'))->url($path),
        ]);
    }
}
