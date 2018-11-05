<?php

namespace Wink\Http\Controllers;

use Wink\WinkTag;
use Wink\WinkPost;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostsController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkPost::orderBy('publish_date', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->with('tags')
            ->paginate(30);

        return response()->json([
            'entries' => $entries
        ]);
    }

    /**
     * Return a single post.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        if ($id == 'new') {
            return response()->json([
                'entry' => WinkPost::make(['id' => Str::uuid(), 'publish_date' => now()->toDateTimeString()])
            ]);
        }

        $entry = WinkPost::with('tags')->findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single post.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $data = [
            'title' => request('title'),
            'excerpt' => request('excerpt', ''),
            'slug' => request('slug'),
            'body' => request('body', ''),
            'published' => request('published'),
            'author_id' => request('author_id'),
            'featured_image' => request('featured_image'),
            'featured_image_caption' => request('featured_image_caption', ''),
            'publish_date' => request('publish_date', ''),
        ];

        validator($data, [
            'publish_date' => 'required|date',
            'author_id' => 'required',
            'title' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_posts', 'slug')->ignore(request('id')),
        ])->validate();

        $entry = $id != 'new' ? WinkPost::findOrFail($id) : new WinkPost(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        $entry->tags()->sync(
            $this->collectTags(request('tags'))
        );

        return response()->json([
            'entry' => $entry
        ]);
    }

    /**
     * Tags incoming from the request.
     *
     * @param  array $incomingTags
     * @return array
     */
    private function collectTags($incomingTags)
    {
        $allTags = WinkTag::all();

        return collect($incomingTags)->map(function ($incomingTag) use ($allTags) {
            $tag = $allTags->where('slug', Str::slug($incomingTag['name']))->first();

            if (! $tag) {
                $tag = WinkTag::create([
                    'id' => $id = Str::uuid(),
                    'name' => $incomingTag['name'],
                    'slug' => Str::slug($incomingTag['name']),
                ]);
            }

            return (string) $tag->id;
        })->toArray();
    }

    /**
     * Return a single post.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $entry = WinkPost::findOrFail($id);

        $entry->delete();
    }
}
