<?php

namespace App\Http\Controllers;
use App\Post;
use Illuminate\Http\Request;
use App\Events\PostWasLiked;
use App\Notifications\PostLiked;

use App\Http\Requests;

class PostLikeController extends Controller
{	
    // Protect the page
	public function __construct() {
		$this->middleware(['auth']);
	}

    // Store the like
    public function store(Request $request, Post $post) {
    	$this->authorize('like',$post);
    	
    	$like = $post->likes()->firstOrNew([
    		'user_id' => $request->user()->id
    	]);

        // If the like already exists, return a conflict code
    	if ($like->exists) {
    		return response(null, 409);
    	}

        // Save like
    	$like->save();

        //Broadcast like to user
        broadcast(new PostWasLiked($post, $request->user()))->toOthers();
        $post->user->notify(new PostLiked($post,$request->user()));

    	return response(null,200);
    }
}
