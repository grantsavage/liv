<?php

namespace App\Http\Controllers;

use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;

class SettingsController extends Controller
{
    public function __construct() {
    	$this->middleware(['auth']);
    }
    public function index() {
    	return view('settings.settings');
    }

    public function verifyEmail($email) {
    	if (User::where('email',$email)->count() == 0 || Auth::user()->email == $email) {
    		return response()->json(["verified"=>true]);
    	} else {
    		return response()->json(["verified"=>false]);
    	}
    }
}
