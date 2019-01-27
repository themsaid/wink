<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Wink\Http\Resources\TagsResource;

class TagsController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tagModel = config('wink.models.tag');

        $entries = $tagModel::when(request()->has('search'), function ($q) {
            $q->where('name', 'LIKE', '%'.request('search').'%');
        })
            ->orderBy('created_at', 'DESC')
            ->withCount('posts')
            ->get();

        return TagsResource::collection($entries);
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        $tagModel = config('wink.models.tag');

        if ($id === 'new') {
            return response()->json([
                'entry' => $tagModel::make([
                    'id' => Str::uuid(),
                ]),
            ]);
        }

        $entry = $tagModel::findOrFail($id);

        return response()->json([
            'entry' => $entry,
        ]);
    }

    /**
     * Store a single category.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $tagModel = config('wink.models.tag');

        $data = [
            'name' => request('name'),
            'slug' => request('slug'),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'name' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_tags', 'slug')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? $tagModel::findOrFail($id) : new $tagModel(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry->fresh(),
        ]);
    }

    /**
     * Return a single tag.
     *
     * @param  string  $id
     * @return void
     */
    public function delete($id)
    {
        $tagModel = config('wink.models.tag');

        $entry = $tagModel::findOrFail($id);

        $entry->delete();
    }
}
