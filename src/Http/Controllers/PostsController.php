<?php

namespace Wink\Http\Controllers;

use Wink\WinkTag;
use Wink\WinkMeta;
use Wink\WinkPost;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Wink\Http\Resources\WinkPostCollection;
use Wink\Http\Resources\WinkPost as WinkPostResource;

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
            ->with(['tags', 'meta'])
            ->paginate(30);

        return response()->json([
            'entries' => new WinkPostCollection($entries)
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

        $entry = WinkPost::with(['tags', 'meta'])->findOrFail($id);

        return response()->json([
            'entry' => new WinkPostResource($entry),
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

        $this->saveMeta(request('meta', []), $entry);

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
     * Save meta fields. We need this rather than a straight sync() call as the meta
     * data comes back from the frontend in a nice key->value format, but for flexibility
     * in the database, we are storing in a more generic meta table format.
     *
     * @param array    $meta
     * @param WinkPost $post
     *
     * @return \Wink\WinkPost
     */
    private function saveMeta(array $meta, WinkPost $post)
    {
        if (empty($meta)) {
            return $post;
        }

        if (array_get($meta, 'opengraph_image', false)) {
            // Special case for opengraph image, get width & height to avoid the facebook
            // "first share" problem (https://developers.facebook.com/docs/sharing/best-practices#precaching)
            list($width, $height) = getimagesize(url($meta['opengraph_image']));
            $meta['opengraph_image_height'] = $height;
            $meta['opengraph_image_width'] = $width;
        }

        foreach ($meta as $key => $value) {
            $post->meta()->save(
                WinkMeta::updateOrCreate([
                    'wink_post_id' => $post->id,
                    'key' => $key
                ],
                [
                    'key' => $key,
                    'value' => $value
                ])
            );
        }

        return $post;
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
