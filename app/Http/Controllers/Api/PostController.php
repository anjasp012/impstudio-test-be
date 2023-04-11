<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(5);
        return new PostResource(true, 'List Data Posts', $posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['thumbnail'] = $request->file('thumbnail')->store('assets/thumbnail', 'public');
        $post = Post::create($data);
        return new PostResource(true, 'Data Post Berhasil Ditambahkan!', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource(true, 'Data Post Ditemukan!', $post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, Post $post)
    {
        // dd('asa');
        $data = $request->all();

        $data['slug'] = Str::slug($request->name);
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('assets/thumbnail', 'public');
            Storage::delete('assets/thumbnail/' . $post->thumbnail);
        } else {
            $data['thumbnail'] = $post->thumbnail;
        }

        $post->update($data);

        return new PostResource(true, 'Data Post Berhasil Diubah!', $post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        Storage::delete('assets/thumbnail' . $post->thumbnail);
        $post->delete();
        return new PostResource(true, 'Data Post Berhasil Dihapus!', null);
    }
}
