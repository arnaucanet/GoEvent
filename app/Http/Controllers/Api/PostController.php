<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return $posts;
    }

    public function show(Post $post)
    {
        $post->load('user:id,name,surname1', 'categories');
        return $post;
    }

    public function destroy(Post $post)
    {
        $post->delete();
    }

    public function store(Request $request)
    {
        $this->authorize('post-edit');

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255', 'min:2'],
            'content' => ['required', 'string', 'min:2'],
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        $data['user_id'] =  auth()->user()->id;
        $post = Post::create($data);
        $post->categories()->attach($data['categories']);
        return $post;
    }
}
