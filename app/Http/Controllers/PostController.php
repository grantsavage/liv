<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Storage;
use App\Post;
use App\Media;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Events\PostWasCreated;
use App\Jobs\ResizePostImage;

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
            'body' => 'required',
        ]);     
        $path = null;
        if ($request->hasFile('image')) {
            $name = uniqid(true) . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs(
                'public/uploads', $name
            );
            $imagePath = storage_path() . '/app/public/uploads/' . $name;
            $job = (new ResizePostImage($imagePath));
            dispatch($job);
            $path = Storage::url($path);
        }
         // Create the post
        $post = $request->user()->posts()->create([
            'body' => $request->body,
            'image_url' => $path
        ]);

        // Broadcast Notification
        broadcast(new PostWasCreated($post))->toOthers();

        // Return the post
    	return $post->load(['user']);
    }

    public function viewPost($id) {
        $post = Post::where('id',$id)->first();
        if (!$post) {
            return redirect('/home');
        }
        $post->load(['user']);
        return view('post.post')->with(['post'=>$post]);
    }
}
