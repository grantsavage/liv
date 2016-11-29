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
    // Protect the page
    public function __construct(){
    	$this->middleware(['auth']);
    }

    // Return user object to vue
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

        // If we're trying to load the user's info from Vue
        if ($request->wantsJson()) {
            return $user;
        // Load the page markup to the user and pass Vue the user's username
        } else {
            return view('profile.index')->with('user',$user);
        }
    }

    // Load the edit view with the current user
    public function edit(){
    	return view('profile.edit')->with('user',Auth::user());
    }

    // Update user's information from request
    public function update(Request $request){
    	$this->validate($request,[
    		'name' => 'max:100',
            'location' => 'max:100',
            'bio' => 'max:250'
    	]);
        $path = null;
        if ($request->hasFile('image')) {
            $name = uniqid(true) . '.' . $request->file('image')->getClientOriginalExtension();
            $path = $request->file('image')->storeAs(
                'public/uploads', $name
            );
            $imagePath = storage_path() . '/app/public/uploads/' . $name;
            Image::make($imagePath)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->crop(128,128)->encode()->save();
            Auth::user()->update([
                'avatar_url' => Storage::url($path)
            ]);
        }

    	Auth::user()->update([
    		'name' => $request->name,
            'location' => $request->location,
            'bio' => $request->bio,
    	]);

        return Auth::user();
    }
}
