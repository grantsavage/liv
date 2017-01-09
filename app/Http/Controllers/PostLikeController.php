<?php

namespace App\Http\Controllers;

use Setting;
use App\Post;
use App\Events\PostWasLiked;
use Illuminate\Http\Request;
use App\Notifications\PostLiked;

use App\Http\Requests;

class PostLikeController extends Controller
{	
    /*
     * Authorization Middleware
     */
	public function __construct() {
		$this->middleware(['auth']);
	}

    /*
     * Store the like
     */
    public function store(Request $request, Post $post) {
        // Authorize reqeust
    	$this->authorize('like',$post);
    	
        // Create like
    	$like = $post->likes()->firstOrNew([
    		'user_id' => $request->user()->id
    	]);

        // If the like already exists, return a 409 conflict response
    	if ($like->exists) {
    		return response(null, 409);
    	}

        // Save like
    	$like->save();

        // Broadcast and notify like to user
        $post->user->notify(new PostLiked($post,$request->user()));

        if (Setting::get('pushNotifications','true',$request->user()->id) != 'false') {
            broadcast(new PostWasLiked($post, $request->user()))->toOthers();
        }

        // Response
    	return response(null,200);
    }

}
