@extends('layouts.app')

@section('content')
<div id="welcome" class="welcome">
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="title m-b-md">
                Liv
            </div>
            <div class="sub-title m-b-md">
                A new type of social network.
            </div>
            <div class="m-b-md author">
                Created and Developed by Grant Savage
            </div>
            @if(Auth::guest())
            <a href="/register" class="btn btn-primary btn-block" style="font-size: 30px;">Sign Up</a>
            @endif
        </div>
    </div>
</div>
<div class="welcome" style="background-color: #fff;">
    <div class="container">
         <h1 class="text-center">Why Liv is different</h1>
         <div class="content">
             <div class="row">
                 <div class="col-md-4">
                    <h2>Reason 1</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem sapiente corrupti eaque ab obcaecati nulla temporibus hic laudantium nihil excepturi non pariatur, optio libero magnam ullam? Harum dolore commodi, impedit!</p>
                </div>
                 <div class="col-md-4">
                    <h2>Reason 2</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem sapiente corrupti eaque ab obcaecati nulla temporibus hic laudantium nihil excepturi non pariatur, optio libero magnam ullam? Harum dolore commodi, impedit!</p>
                </div>
                 <div class="col-md-4">
                    <h2>Reason 3</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem sapiente corrupti eaque ab obcaecati nulla temporibus hic laudantium nihil excepturi non pariatur, optio libero magnam ullam? Harum dolore commodi, impedit!</p>
                </div>
             </div>
         </div>
    </div>
</div>
@endsection