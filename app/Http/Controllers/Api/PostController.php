<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;

class PostController extends Controller
{
    public function index(){
        $posts = Post::all();
        return $posts;
    }

    public function show(Post $post){
        return $post;
    }

    public function destroy(Post $post){
        $post->delete();
    }

    public function store(StorePostRequest $request){
        $data = $request->all();
        $data = $request->validated();
        $post = Post::create($data);
        return $post;
    }
}
