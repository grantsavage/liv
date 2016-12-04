<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Events\RequestWasSent;
use App\Notifications\FriendRequestReceived;

use App\Http\Requests;

class FriendController extends Controller
{
	public function __construct(){
		$this->middleware(['auth']);
	}
    public function friends() {
		return Auth::user()->friends();
	}

	public function getAdd($username) {
		$user = User::where('username',$username)->first();

		// Return Error
		if (!$user) {
			return response(["error"=>"User was not found"],200);
		}

		// Return error
		if (Auth::user()->id === $user->id) {
			return response(["error"=>"Can't add yourself"],200);
		}

		// Return error
		if (Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user())) {
			return response(["error"=>"Friend Request Already Pending"],200);
		}

		// Return error
		if(Auth::user()->isFriendsWith($user)) {
			return response(["error"=>"You're already friends!"],200);
		}

		Auth::user()->addFriend($user);

		// Notify user
		broadcast(new RequestWasSent(Auth::user(),$user));
		$user->notify(new FriendRequestReceived(Auth::user(),$user));

		// Return OK
		return response(null,200);
	}

	public function getAccept($username) {

		$user = User::where('username',$username)->first();

		if (!$user) {
			// Return error
			return response()->json(["error"=>"User not found"]);
		}

		if (!Auth::user()->hasFriendRequestReceived($user)) {
			// Return error
			return response()->json(["error"=>"Something went wrong"]);
		}

		Auth::user()->acceptFriendRequest($user);

		// Return OK
		return response(null,200);
	}

	public function getDecline($username) {
		$user = User::where('username',$username)->first();

		if (!$user) {
			// Return error
			return response()->json(["error"=>"User not found"]);
		}

		// Return error
		if(Auth::user()->isFriendsWith($user)) {
			return response(["error"=>"You're already friends!"],200);
		}
					  
		Auth::user()->declineFriendRequest($user);

		return response(null,200);
	}

	public function postDelete($username) {
		$user = User::where('username', $username)->first();

		// Return error
		if(!Auth::user()->isFriendsWith($user)) {
			return response()->json(["error"=>"There was a problem"]);
		}

		Auth::user()->deleteFriend($user);

		return response(null,200);
	}
}
