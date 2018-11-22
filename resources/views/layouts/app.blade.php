<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Meet&Greet</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">



    <style type="text/css">
        
             body {
                  
                  background: #3BC0C7;
        }

        .top {
          text-align: left;
        }

        .shadow {
          box-shadow: 0px 0px 5px 0px;
          padding-top: 10px;
        }

        h2, a{
          color: #fff;
        }







    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('home') }}">
                        Meet&Greet
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            
                            
                        @else

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" onclick="getNotifications()">
                              
                              <span class="label label-info">Friend Request </span> <span class="badge n_count">0</span>
                            </a>
                            <ul class="dropdown-menu" id="notifications">
                              
                              
                                  
                           
                              
                            </ul>
                        </li>

                        

                            <li class="dropdown">



                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <script type="text/javascript">



    getNotificationCount();
  
     function getNotificationCount(){




      var  url = '/get_notification_count/';
            $.get({url: url},
                function (data) {
 
                var data = JSON.parse(data);

                

                $('.n_count').text(data);
            
                    
                });

    }

    setInterval(function(){
        getNotificationCount() 
    }, 2000);


    function getNotifications(){



        var  url = '/get_notifications/';
            $.get({url: url},
                function (data) {
 
                var data = JSON.parse(data);

                
                $("#notifications").empty();


                $.each(data, function(key, value) {

                 $("#notifications").append(`<li>
                    <a href="{{ URL::to('user_profile_n') }}/${value.nId}">
                         
                        <p> ${value.name} has send a friend request!</p>
                        
                   </a>
                 </li>
                 
                 `)

                });
            
                    
            });


    }














    </script>
    
</body>
</html>
