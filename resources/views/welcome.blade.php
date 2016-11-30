@extends('layouts.app')

@section('content')
<div id="welcome" class="welcome flex-center">
    <div class=" position-ref full-height">
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
            <br>
            @if(Auth::guest())
            <a href="/register" class="btn btn-primary btn-block" style="font-size: 30px;">Sign Up</a>
            <br>
            <a href="/login" class="btn btn-link">Already Have an Acccount? Sign In</a>
            @endif
        </div>
    </div>
</div>
<div class="welcome" style="background-color: #fff;">
    <div class="container full-height">
         <h1 class="text-center">Why Liv is Different</h1>
         <div class="content">
             <div class="row" style="font-size: 20px;">
                 <div class="col-md-4">
                    <h2>No News</h2>
                    <span class="glyphicon glyphicon-filter" style="font-size: 60px;"></span>
                    <p>One of the core reasons on why Liv was developed was that news was becoming overwhelming on other social networks. The amount of fake news cluttered the network. Liv filters out news so that you can focus on what matters.</p>
                </div>
                 <div class="col-md-4">
                    <h2>Slim</h2>
                    <span class="glyphicon glyphicon-phone" style="font-size: 60px;"></span>
                    <p>Liv was built with compatibility and flexiblity in mind. Using the latest web technology, Liv is lightning fast and ridiculously responsive. Liv works on mobile devices, laptops and everywhere in between.</p>
                </div>
                 <div class="col-md-4">
                    <h2>Elegant</h2>
                    <span class="glyphicon glyphicon-leaf" style="font-size: 60px;"></span>
                    <p>Compared to other social networks, Liv was designed so that users have a sense of space. There is nothing more distracting than unecessary information. No clutter, no extraneous information, just the things you want to see. </p>
                </div>
             </div>
             <br>
             <br>
             <div class="row flex-center">
                 <div class="col-md-6 col-md-3-offset">
                     <a href="/register" class="btn btn-primary btn-block" style="font-size: 30px;">Convinced?</a>
                 </div>
             </div>
         </div>
    </div>
</div>
<div class="container" id="footer">
    <footer>
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2016 Liv, Inc. &middot; <a href="#">Privacy</a> &middot; <a href="#">Terms</a></p>
    </footer>
</div>

@endsection