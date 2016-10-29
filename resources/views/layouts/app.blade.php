<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
            'user' => [
                'id' => Auth::check() ? Auth::user()->id : null
            ]
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                        <ul class="nav navbar-nav navbar-right">
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        </ul>
                        @else
                        <nav-bar-notifications :user="{{json_encode(Auth::user())}}"></nav-bar-notifications>
                        <!--<ul class="nav navbar-nav navbar-right">
                            <li>
                                
                                <a href="url('/user/' . Auth::user()->username)}}">
                                <img class="img-rounded" src="=Auth::user()->avatar}}" alt="" width="25" height="25">
                                 Auth::user()->name }}</a>
                            </li>
                            <li><a href="url('/home')}}">Home</a></li>

                            <li>
                                <a href="#">
                                    <span class="glyphicon glyphicon-user"></span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <span class="glyphicon glyphicon-comment"></span>
                                </a>
                            </li>

                            <li>
                                <a href="#">
                                    <span class="glyphicon glyphicon-globe"></span>
                                    <span class="badge" style="margin-bottom: 4px; background-color: #d9534f; padding-top: 1px;">4</span>
                                </a>

                            </li>

                            <li class="dropdown">
                                <a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="url('/user/' . Auth::user()->username)}}">Profile</a></li>
                                    <li><a href="url('/profile/edit')}}">Edit Profile</a></li>
                                    <li><a href="#">Settings</a></li>
                                    <li>
                                        <a href=" url('/logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action=" url('/logout') }}" method="POST" style="display: none;">
                                             csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>-->
                        @endif
                </div>
            </div>
        </nav>

        @yield('content')
    </div>
    {{csrf_field()}}
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script>
        $(function() {
            $("input").focus();
        });
    </script>
</body>
</html>
