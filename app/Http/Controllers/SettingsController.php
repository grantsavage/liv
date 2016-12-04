<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;
use Setting;
use Hash;


use App\Http\Requests;

class SettingsController extends Controller
{
    public function __construct() {
    	$this->middleware(['auth']);
    }
    public function index() {
    	return view('settings.settings',['settings'=> json_encode(Setting::all())]);
    }

    public function verifyEmail($email) {
    	if (User::where('email',$email)->count() == 0) {
    		return response()->json(["verified"=>true]);
    	} else {
    		return response()->json(["verified"=>false]);
    	}
    }

    public function saveSettings(Request $request) {
    	Setting::set(['emailNotifications'=>$request->emailNotifications]);
    	Setting::save();
    	return Setting::all();
    }

    public function changePassword(Request $request) {
    	if (Auth::check()) {
    		if (Hash::check($request->current,Auth::user()->password)) {
                if ($request->new == $request->confirm) {
                    $user = Auth::user();
                    Auth::user()->forceFill([
                        'password' => bcrypt($request->confirm),
                    ])->save();
                    Auth::guard()->login($user);
                } else {
                    return response()->json(['error'=>'New Passwords Do Not Match']);
                }
	    	} else {
                return response()->json(['error'=>'Incorrect Current Password']);
            }
    	}
    	
    }

    public function removeAccount(Request $request) {
        //if (Auth::check()) {  
            if ($request->wantsJson() && Hash::check($request->password, Auth::user()->password)) {
                $user = User::find(Auth::user()->id);
                Auth::logout();
                $user->destroy($user->id);
                return response()->json(["removal"=>true]);
            } else {
                return response()->json(['error' => 'Incorrect Password. Try again.']);
            }
        //}
    }
}
