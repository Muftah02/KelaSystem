<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

use function Laravel\Prompts\alert;

class PostController extends Controller
{
      function index()
    {
  
      //  $allPosts = Post::all();
        $allPosts = Post::with('user')->get();
        
        return view('posts.index', ['allPosts' => $allPosts]);
    }//
    function show( Post $post)
    {
        // بطريقة ال route model binding
        // $post = Post::where('id', $post)->first();
        // $post = Post::where('id', $post)->firstOrFail();
        // $post = Post::find($post);
        // $Post = Post::findOrFail($post);
        
        return view('posts.show', ['post' => $post]);

    }
    function create()
    {
        $users = User::all();
        return view('posts.create', ['users' => $users]);
    }

    function store(){
        $data = request()->all();
        $title = request()->title;
        $description = request()->description;
        $posted_by = request()->post_creator;
        

        //insert the data into the database
        $post = new Post();

        $post->title =$title;
        $post->description =$description;
        $post->user_id =$posted_by;
        
        $post->save();


        
        return to_route('posts.index');
    }
    function edit(Post $post)
    {
       $users = User::all();
        return view('posts.edit', ['post' => $post, 'users' => $users]);
    }
    function update($post)
    {
        $post = Post::find($post);
        $data = request()->all();
        $title = request()->title;
        $description = request()->description; 
        Post::where('id', $post->id)->update([
            'title' => $title,
            'description' => $description,
        ]);
       
        return to_route('posts.index'); 
    }
    function destroy($post)
    {
        $post = Post::find($post);
        $post->delete();

        return to_route('posts.index'); ;
    }
}


