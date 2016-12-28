<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Setting;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class SettingsController extends Controller
{
    /*
     * Authorization middleware
     */
    public function __construct() {
    	$this->middleware(['auth']);
    }

    /*
     * Return settings view with user's current settings
     */
    public function index() {
    	return view('settings.settings',['settings'=> json_encode(Setting::all())]);
    }

    /*
     * Verify email address
     */
    public function verifyEmail($email) {
        // If no current email matches submitted email
    	if (User::where('email',$email)->count() == 0) {
            
    		return response()->json(["verified"=>true]);

        // If the submitted email already exists
    	} else {
    		return response()->json(["verified"=>false]);
    	}
    }

    /*
     * Save the settings
     */
    public function saveSettings(Request $request) {
        // Set notifications
    	Setting::set(['emailNotifications'=>$request->emailNotifications]);

        // Save to database
    	Setting::save();

        // Return the new settings
    	return Setting::all();
    }

    /*
     * Change the user's password
     */
    public function changePassword(Request $request) {
        // Perform extra authorization check
    	if (Auth::check()) {

            // Check if current password check out
    		if (Hash::check($request->current,Auth::user()->password)) {

                // Check if confirm password field matches new password
                if ($request->new == $request->confirm) {

                    // Get current user
                    $user = Auth::user();

                    // Update password column with hashed passowrd
                    Auth::user()->forceFill([
                        'password' => bcrypt($request->confirm),
                    ])->save();

                    // Login the user since password changed
                    Auth::guard()->login($user);

                // If confirm field does not match new password
                } else {
                    return response()->json(['error'=>'New Passwords Do Not Match']);
                }

            // If user doesn't correctly enter current password
	    	} else {
                return response()->json(['error'=>'Incorrect Current Password']);
            }
    	}
    	
    }

    /*
     * Account Deletion
     */
    public function removeAccount(Request $request) {
        // Check if submitted password matches current password
        if ($request->wantsJson() && Hash::check($request->password, Auth::user()->password)) {
            // Get user
            $user = User::find(Auth::user()->id);

            // Logout user
            Auth::logout();

            // Destroy the user in database
            $user->destroy($user->id);

            // Return success response
            return response()->json(["removal"=>true]);

        // Return error response
        } else {
            return response()->json(['error' => 'Incorrect Password. Try again.']);
        }
    }
}
