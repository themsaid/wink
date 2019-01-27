<?php

namespace Wink\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Wink\Http\Resources\TeamResource;

class TeamController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection|\Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $authorModel = config('wink.models.author');

        $entries = $authorModel::when(request()->has('search'), function ($q) {
            $q->where('name', 'LIKE', '%'.request('search').'%');
        })
            ->orderBy('created_at', 'DESC')
            ->withCount('posts')
            ->get();

        return TeamResource::collection($entries);
    }

    /**
     * Return a single post.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id = null)
    {
        $authorModel = config('wink.models.author');

        if ($id === 'new') {
            return response()->json([
                'entry' => $authorModel::make([
                    'id' => Str::uuid(),
                ]),
            ]);
        }

        $entry = $authorModel::findOrFail($id);

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
        $authorModel = config('wink.models.author');

        $data = [
            'name' => request('name'),
            'slug' => request('slug'),
            'email' => request('email'),
            'bio' => request('bio'),
            'avatar' => request('avatar'),
            'meta' => request('meta', (object) []),
        ];

        validator($data, [
            'meta.theme' => 'in:dark,light',
            'name' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_authors', 'slug')->ignore(request('id')),
            'email' => 'required|email|'.Rule::unique(config('wink.database_connection').'.wink_authors', 'email')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? $authorModel::findOrFail($id) : new $authorModel(['id' => request('id')]);

        if (request('password')) {
            $entry->password = Hash::make(request('password'));
        }

        if (request('email') !== $entry->email && Str::contains($entry->avatar, 'gravatar')) {
            unset($data['avatar']);

            $entry->avatar = null;
        }

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry->fresh(),
        ]);
    }

    /**
     * Return a single author.
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function delete($id)
    {
        $authorModel = config('wink.models.author');

        $entry = $authorModel::findOrFail($id);

        if ($entry->posts()->count()) {
            return response()->json(['message' => 'Please remove the author\'s posts first.'], 402);
        }

        if ($entry->id == auth('wink')->user()->id) {
            return response()->json(['message' => 'You cannot delete yourself.'], 402);
        }

        $entry->delete();
    }
}
