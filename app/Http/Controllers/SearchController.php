<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

class SearchController extends Controller
{
	public function __construct(){
    	$this->middleware(['auth']);
    }
    public function search(Request $request) {
    	$query = $request->input("query");

    	if (!$query) {
    		return redirect("/home");
    	}

    	$users = User::where("name",'LIKE',"%{$query}%")->orWhere('username','LIKE',"%{$query}%")->get();

    	return response()->json($users);
    }
}
