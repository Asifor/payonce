<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Perfect Scrollbar -->
    <link type="text/css" href="{{asset('assets/vendor/perfect-scrollbar.css')}}" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="{{asset('assets/css/app.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/app.rtl.css')}}" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="{{asset('assets/css/vendor-material-icons.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/vendor-material-icons.rtl.css')}}" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="{{asset('assets/css/vendor-fontawesome-free.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/vendor-fontawesome-free.rtl.css')}}" rel="stylesheet">

    <!-- Flatpickr -->
    <link type="text/css" href="{{asset('assets/css/vendor-flatpickr.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/vendor-flatpickr.rtl.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/vendor-flatpickr-airbnb.css')}}" rel="stylesheet">
    <link type="text/css" href="{{asset('assets/css/vendor-flatpickr-airbnb.rtl.css')}}" rel="stylesheet">

    <style>
        .emp_create_btn{
            position:fixed;
            bottom:0.3rem;
            right:1.7rem;
        }

        @media (min-width: 1024px) {

            .emp_create_btn {
                bottom: 3rem;
                right: 3.5rem;
            }

        }
        
    </style>

    <!-- Toastr -->
    <link type="text/css" href="{{asset('assets/vendor/toastr.min.css')}}" rel="stylesheet">

</head>

<body class="layout-default">



    <div class="preloader"></div>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">

        <!-- Header -->

        <div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
            <div class="mdk-header__content">

                <div class="navbar navbar-expand-sm navbar-main navbar-dark bg-dark  pr-0" id="navbar" data-primary>
                    <div class="container-fluid p-0">

                        <!-- Navbar toggler -->

                        <button class="navbar-toggler navbar-toggler-right d-block d-lg-none" type="button" data-toggle="sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>


                        <!-- Navbar Brand -->
                        <a href="/" class="navbar-brand ">
                            <i class="material-icons icon-muted icon-30pt mr-2">monetization_on</i>
                            <span>PayOnce</span>
                        </a>
                        


                        <ul class="nav navbar-nav ml-auto d-none d-md-flex">
                            <li class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
                                    <i class="material-icons nav-icon navbar-notifications-indicator">notifications</i>
                                </a>
                            </li>
                        </ul>

                        <ul class="nav navbar-nav d-none d-sm-flex border-left navbar-height align-items-center">
                            <li class="nav-item dropdown">
                                <a href="#account_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
                                    <span class="mr-1 d-flex-inline">
                                        <span class="text-light">{{ ucwords(auth()->user()->full_name) }}</span>
                                    </span>
                                    @if(empty(auth()->user()->profile_pic))
                                    <img src="{{ asset('assets/images/avatar/placeholder.jpg') }}" class="rounded-circle" width="32" alt="Frontted">
                                    @else
                                    <img src="{{ Auth::user()->profile_pic }}" class="rounded-circle" width="32" alt="Frontted">
                                    @endif
                                </a>
                                <div id="account_menu" class="dropdown-menu dropdown-menu-right">
                                    <div class="dropdown-item-text dropdown-item-text--lh">
                                        <div><strong>{{ ucwords(auth()->user()->full_name) }}</strong></div>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="/dashboard"><i class="material-icons">dvr</i> Dashboard</a>
                                    <a class="dropdown-item" href="{{ route('user.profile') }}"><i class="material-icons">account_circle</i> My profile</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="material-icons">exit_to_app</i> Logout</a>
                                </div>
                            </li>
                        </ul>

                    </div>
                </div>

            </div>
        </div>

        <!-- // END Header -->
        @yield('content')
        @include('layouts.user.sidebar')
        @include('layouts.user.footer')
        