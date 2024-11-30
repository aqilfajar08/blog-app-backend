<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount(['commnet', 'like'])->get()
        ], 200);
    }

    public function show($id) 
    {
        return response([
            'posts' => Post::where('id', $id)->withCount(['comment', 'like'])->get(),
        ], 200);
    }

    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string',
        ]);

        $post = Post::create([
            'body' => $attrs['body'],
            // 'user_id' => auth()->user()->id,
        ]);

        // for now skip for post image

        return response([
            'message' => 'Post created.',
            'post' => $post
        ], 200);
    }
}
