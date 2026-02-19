<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();

        return view('welcome', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request){
        
        if(!auth()->check()){
            return back();
        }
        
        $request->validate([
            'text' => ['required', 'string', 'max:280'],
        ]);

        Post::create([
            'user_id' => auth()->id(),
            'text' => $request->text,
        ]);

        return to_route('posts.index');
    }

    public function show(Post $post){
        return view('posts.show', ['post' => $post]);
    }

    public function edit(Post $post)
    {
        if($post->user_id !== auth()->id()){
            return back();
        }
        
        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post){
          
        if($post->user_id !== auth()->id()){
            return back();
        }
        
        $request->validate([
            'text' => ['required', 'string', 'max:280'],
        ]);

        $post->update([
            'text' => $request->text,
        ]);

        return to_route('posts.index');
    }

    public function destroy(Post $post){
        
        if($post->user_id !== auth()->id()){
            return back();
        }
        
        $post->delete();

        return to_route('posts.index');
    }
}
