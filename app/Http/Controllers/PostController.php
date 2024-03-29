<?php

namespace App\Http\Controllers;

use Auth;
use Image;
use Storage;
use Input;
use App\Post;
use App\Media;
use Carbon\Carbon; 
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Events\PostWasCreated;
use App\Jobs\ResizePostImage;

class PostController extends Controller
{
    /*
     * Authorization Middleware
     */
    public function __construct(){
    	$this->middleware(['auth']);
    }

    /*
     * Return posts to the home timline
     */
    public function index(){
        // Return posts only from user and user friends
    	return Post::notReply()->where(function($query) {
            return $query
                ->where('user_id', Auth::user()->id)
                ->orWhereIn('user_id', Auth::user()
                ->friends()->pluck('id'));
            })
            ->with(['user','replies'])
            ->latestFirst()
            ->get();
    }

    /*
     * Store a new post
     */
    public function store(Request $request){
        // Validate request
        $this->validate($request, [
            'body' => 'required'
            //"media" => "file|mimes:jpeg,bmp,png,mov,mp4"
        ]);     

        // Initially set path to null
        $path = null;

        $isVideo = null;

        // Check if request has image
        if ($request->hasFile('media')) {

            $isVideo = true;

            $ext = $request->file('media')->getClientOriginalExtension();

            // Generate name for image
            $name = uniqid(true) . '.' . $ext;

            // Store file and get storage path
            $path = $request->file('media')->storeAs(
                'public/uploads', $name
            );

            // Set the media path equal to storage URL path
            $path = Storage::url($path);

            if ($ext == 'mp4' || $ext == 'mov') {
                //
            } else {
                $isVideo = false;

                // Get image path
                $imagePath = storage_path() . '/app/public/uploads/' . $name;

                // Create job to resize image
                // $job = (new ResizePostImage($imagePath));
                // dispatch($job);

                Image::make($imagePath)
                ->resize(1080, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('png',80)
                ->save();
            }

            // If the file is an image
            /*if ($ext !== 'mov' || $ext !== 'mp4') {

                $isVideo = false;

                // Get image path
                $imagePath = storage_path() . '/app/public/uploads/' . $name;

                // Create job to resize image
                // $job = (new ResizePostImage($imagePath));
                // dispatch($job);

                Image::make($imagePath)
                ->resize(1080, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('png',80)
                ->save();
            }*/
        }

        // Create the post
        $post = $request->user()->posts()->create([
            'body' => $request->body,
            'image_url' => $path,
            'is_video' => $isVideo
        ]);

        // Broadcast Notification
        //broadcast(new PostWasCreated($post))->toOthers();
        
        // Return the post
    	return $post->load(['user']);
    }

    public function storeReply(Request $request, $postId) {
        $this->validate($request, [
            "body" => "required",
        ]);

        $post = Post::notReply()->find($postId);

        if (!$post) {
            return response()->json(["error" => "Could not find post"]);
        }

        if (!Auth::user()->isFriendsWith($post->user) && Auth::user()->id !== $post->user->id) {
            return response()->json(["error" => "Can't reply if you're not friends"]);
        }

        $reply = $request->user()->posts()->create([
            'body' => $request->body,
        ]);

        $post->replies()->save($reply);

        return $reply->load(['user']);
    }

    /*
     * Return post for single post view
     */
    public function viewPost($id) {
        // Find post
        $post = Post::where('id',$id)->first();

        // Redirect if post not found
        if (!$post) {
            return redirect('/home');
        }

        // Load post user
        $post->load(['user']);

        // Response
        return view('post.post')->with(['post'=>$post]);
    }

    public function delete($id) {
        $post = Post::find($id);

        if (!$post) {
            return response(null,404);
        }

        if ($post->user->id != Auth::id()) {
            return response(null,409);
        }

        $post->delete();

        return response()->json(["ok"=>true]);
    }

    public function upload(Request $request) {
        dd($request->file("video"));
    }
}
