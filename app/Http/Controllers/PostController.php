<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Events\PostWasCreated;

class PostController extends Controller
{
    // Protect the page
    public function __construct(){
    	$this->middleware(['auth']);
    }

    // Return posts to the hoem page
    public function index(Request $request, Post $post){
    	return $post->with(['user'])->latestFirst()->get();
    }

    // Store the submitted post
     public function store(Request $request){
    	$this->validate($request, [
    		'body' => 'required'
    	]);

        // Create the post
    	$post = $request->user()->posts()->create([
    		'body' => $request->body
    	]);

        // Broadcast Notification
        broadcast(new PostWasCreated($post))->toOthers();

        // Return the post
    	return $post->load(['user']);
    }
}
