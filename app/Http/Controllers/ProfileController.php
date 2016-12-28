<?php

namespace App\Http\Controllers;

use Auth;
use Storage;
use Image;
use File;
use App\Post;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProfileController extends Controller
{
    /*
     * Authorization middleware
     */
    public function __construct(){
    	$this->middleware(['auth']);
    }

    /*
     * Give Vue user data
     */
    public function getProfile(Request $request, $username) {
        // Find user
        $user = User::where('username',$username)->first();

        // If user cannot be found
        if (!$user) {
            return redirect('/home');
        }

        // Load up the posts
        $user->load(['posts']);

        // Attach user object to each post
        $user->posts->load(['user']);

        // If we're trying to load the user's info from ajax
        if ($request->wantsJson()) {

            return $user;

        // Load the page markup to the user and pass Vue the user's username
        } else {
            return view('profile.index')->with('user',$user);
        }
    }

    /*
     * Load the profile edit view with the current user
     */
    public function edit(){
    	return view('profile.edit')->with('user',Auth::user());
    }

    /*
     * Update user info
     */
    public function update(Request $request){
        // Validate request
    	$this->validate($request,[
    		'name' => 'max:100',
            'location' => 'max:100',
            'bio' => 'max:250'
    	]);

        // Initially set profile picture path to null
        $path = null;

        // Check to see if request has image
        if ($request->hasFile('image')) {
            // Generate name for image
            $name = uniqid(true) . '.' . $request->file('image')->getClientOriginalExtension();

            // Store image and get path
            $path = $request->file('image')->storeAs(
                'public/uploads', $name
            );

            // Get path for resizing
            $imagePath = storage_path() . '/app/public/uploads/' . $name;

            // Resize image to max width of 300 and crop to 128 by 128 size
            Image::make($imagePath)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->crop(128,128)->encode()->save();

            // Update user avater URL
            Auth::user()->update([
                'avatar_url' => Storage::url($path)
            ]);
        }

        // Update user info
    	Auth::user()->update([
    		'name' => $request->name,
            'location' => $request->location,
            'bio' => $request->bio,
    	]);

        // Return user data as response
        return Auth::user();
    }
}
