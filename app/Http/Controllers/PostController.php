<?php

namespace App\Http\Controllers;

use Auth;
use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Events\PostWasCreated;
use Storage;

class PostController extends Controller
{
    // Protect the page
    public function __construct(){
    	$this->middleware(['auth']);
    }

    // Return posts to the home page
    public function index(){
        //$post->with(['user'])->latestFirst()->get();
    	return Post::where(function($query) {
                return $query->where('user_id', Auth::user()->id)->orWhereIn('user_id', Auth::user()->friends()->pluck('id'));
            })->with(['user'])->latestFirst()->get();
    }

    // Store the submitted post
     public function store(Request $request){
        $this->validate($request, [
            'body' => 'required'
        ]);     
        $post;
        if ($request->hasFile('image')) {
            $path = $request->image->store('public/uploads');
             // Create the post
            $post = $request->user()->posts()->create([
                'body' => $request->body,
                'image_url' => Storage::url($path)
            ]);
        } else {
             // Create the post
            $post = $request->user()->posts()->create([
                'body' => $request->body
            ]);
        }

        // Broadcast Notification
        broadcast(new PostWasCreated($post))->toOthers();

        // Return the post
    	return $post->load(['user']);
    }
}
