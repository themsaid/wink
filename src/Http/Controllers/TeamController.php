<?php

namespace Wink\Http\Controllers;

use Wink\WinkAuthor;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TeamController
{
    /**
     * Return posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = WinkAuthor::withCount('posts')->orderBy('created_at', 'DESC');

        if (request('paginate')) {
            $entries = $entries->paginate(request('paginate'));
        } else {
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
                'entry' => WinkAuthor::make([
                    'id' => Str::uuid()
                ])
            ]);
        }

        $entry = WinkAuthor::findOrFail($id);

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
            'email' => request('email'),
            'bio' => request('bio'),
            'avatar' => request('avatar'),
        ];

        validator($data, [
            'name' => 'required',
            'slug' => 'required|'.Rule::unique(config('wink.database_connection').'.wink_authors', 'slug')->ignore(request('id')),
            'email' => 'required|email|'.Rule::unique(config('wink.database_connection').'.wink_authors', 'email')->ignore(request('id')),
        ])->validate();

        $entry = $id != 'new' ? WinkAuthor::findOrFail($id) : new WinkAuthor(['id' => request('id')]);

        if (request('password')) {
            $entry->password = bcrypt(request('password'));
        }

        if (request('email') !== $entry->email && str_contains($entry->avatar, 'gravatar')) {
            unset($data['avatar']);

            $entry->avatar = null;
        }

        $entry->fill($data);

        $entry->save();

        return response()->json([
            'entry' => $entry->fresh()
        ]);
    }

    /**
     * Return a single author.
     *
     * @param  string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $entry = WinkAuthor::findOrFail($id);

        if ($entry->posts()->count()) {
            return response()->json(['message' => 'Please remove the author\'s posts first.'], 402);
        }

        if ($entry->id == auth('wink')->user()->id) {
            return response()->json(['message' => 'You cannot delete yourself.'], 402);
        }

        $entry->delete();
    }
}
