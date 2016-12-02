@extends('layouts.app')

@section('content')
<settings :user="{{Auth::user()}}" :email="{{json_encode(Auth::user()->email)}}"></settings>
@endsection