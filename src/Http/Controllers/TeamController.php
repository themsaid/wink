<?php

namespace Wink\Http\Controllers;

use Illuminate\Validation\Rule;

class TeamController
{
    protected $author;

    public function __construct()
    {
        $this->author = app(config('wink.author_model'));
    }

    /**
     * Return posts.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $entries = $this->author->withCount('posts')->orderBy('created_at', 'DESC');

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
        if ($id === 'new') {
            return response()->json([
                'entry' => app(config('wink.author_model'))
            ]);
        }

        $entry = $this->author->findOrFail($id);

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
            'slug' => 'required|'.Rule::unique(config('wink.author_table'), 'slug')->ignore(request('id')),
            'email' => 'required|email|'.Rule::unique(config('wink.author_table'), 'email')->ignore(request('id')),
        ])->validate();

        $entry = $id !== 'new' ? $this->author->findOrFail($id) : app(config('wink.author_model'), ['id' => request('id')]);

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
        $entry = $this->author->withCount('posts')->findOrFail($id);

        if ($entry->id == auth()->user()->id) {
            return response()->json(['message' => 'You cannot delete yourself.'], 402);
        }

        if ($entry->posts_count > 0) {
            return response()->json(['message' => 'Please remove the author\'s posts first.'], 402);
        }

        $entry->delete();
    }
}
