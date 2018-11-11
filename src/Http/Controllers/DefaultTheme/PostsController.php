<?php

namespace Wink\Http\Controllers\DefaultTheme;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Wink\WinkPost;

class PostsController extends Controller
{

    public function index(Request $request)
    {
        $data = [
            'posts' => WinkPost::query()
                ->where('published', true)
                ->orderBy('publish_date', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->with('tags')
                ->paginate(12)
        ];

        return view('wink::default.posts.index', $data);
    }

    public function show(Request $request, string $slug)
    {
        $data = [
            'post' => WinkPost::with('author', 'tags')->where('slug', $slug)->firstOrFail()
        ];

        return view('wink::default.posts.show', $data);
    }
}