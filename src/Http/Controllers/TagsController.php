<?php

namespace Wink\Http\Controllers;

use Wink\WinkTag;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TagsController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkTag::withCount('posts')->orderBy('created_at', 'DESC');

        if(request('paginate')){
            $entries = $entries->paginate(request('paginate'));
        }else{
            $entries = $entries->get();
        }

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
                'entry' => WinkTag::make([
                    'id' => Str::uuid()
                ])
            ]);
        }

        $entry = WinkTag::findOrFail($id);

        return response()->json([
            'entry' => $entry
        ]);
    }

    /**
     * Store a single category.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function store($id)
    {
        $data = [
            'name' => request('name'),
            'slug' => request('slug'),
        ];

        validator($data, [
            'name' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_tags', 'slug')->ignore(request('id')),
        ])->validate();

        $entry = $id != 'new' ? WinkTag::findOrFail($id) : new WinkTag(['id' => request('id')]);

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry->fresh()
        ]);
    }

    /**
     * Return a single tag.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $entry = WinkTag::findOrFail($id);

        $entry->delete();
    }
}
