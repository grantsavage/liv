@extends('layouts.app')

@section('content')
<edit-profile :user="{{ $user }}"></edit-profile>
@endsection