@extends('layouts.app')

@section('content')
<edit-profile :user="{{ json_encode($user) }}"></edit-profile>
@endsection