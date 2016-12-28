<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class SearchController extends Controller
{
    /*
     * Authorization middleware
     */
	public function __construct(){
    	$this->middleware(['auth']);
    }

    /*
     * Perform search and return response
     */
    public function search(Request $request) {
        // Get search query from request
    	$query = $request->input("query");

        // If query is null, redirect home
    	if (!$query) {
    		return redirect("/home");
    	}

        // Return a list of users that matches the query
    	$users = User::where("name",'LIKE',"%{$query}%")->orWhere('username','LIKE',"%{$query}%")->get();

        // Response
    	return response()->json($users);
    }
}
