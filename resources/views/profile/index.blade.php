@extends('layouts.app')

@section('content')
	<profile 
		:user="{{ $user }}" 
		:friends="{{Auth::user()->friends()}}" 
		:requests="{{Auth::user()->friendRequestsPending()}}">
	</profile>
@endsection