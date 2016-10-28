@extends('layouts.app')

@section('content')
	<profile :username="{{ $username }}"></profile>
@endsection