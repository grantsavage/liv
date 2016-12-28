<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail;
use App\Http\Requests;

class NotificationController extends Controller
{
	/*
     * Authorization Middleware   
     */
    public function __construct(){
    	$this->middleware(['auth']);
    }

    /*
     * Return unread notifications
     */
    public function getUserUnreadNotifications(Request $request) {
		if ($request->wantsJson()) {
			return Auth::user()->unreadNotifications;
		} else {
			return "hello";
		}
    }

    /*
     * Mark notifications as read
     */
    public function setNotificationsAsRead() {
    	if (Auth::check()) {
    		foreach (Auth::user()->unreadNotifications as $notification) {
    			$notification->markAsRead();
    		}
    		return response(null,200);
    	} else {
    		return response(null, 403);
    	}
    }
}
