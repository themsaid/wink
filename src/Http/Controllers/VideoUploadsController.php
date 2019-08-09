<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class VideoUploadsController
{
    /**
     * Upload a new video.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload()
    {
        $path = request()->video->store(config('wink.video_storage_path'), [
                'disk' => config('wink.storage_disk'),
                'visibility' => 'public',
            ]
        );

        return response()->json([
            'url' => Storage::disk(config('wink.storage_disk'))->url($path),
        ]);
    }
}
