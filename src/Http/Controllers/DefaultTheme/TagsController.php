<?php

namespace Wink\Http\Controllers\DefaultTheme;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Wink\WinkTag;

class TagsController extends Controller
{

    public function show(Request $request, string $tag)
    {
        $data = [
            'tag' => WinkTag::with('posts')->where('slug', $tag)->firstOrFail(),
            'posts' => $tag->posts()
                ->where('published', true)
                ->orderBy('publish_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->with('tags')
                ->paginate(12)
        ];

        return view('wink::default.tags.show', $data);
    }
}