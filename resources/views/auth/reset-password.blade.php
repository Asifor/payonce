<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>PayOnce | Reset Password</title>

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

</head>

<body class="layout-login-centered-boxed">
    

    <div class="layout-login-centered-boxed__form card">

        <x-auth-validation-errors class="mb-4 text-danger" :errors="$errors" />

        <div class="d-flex flex-column justify-content-center align-items-center mt-2 mb-5 navbar-light">

            <a href="/" class="navbar-brand flex-column mb-2 align-items-center mr-0" style="min-width: 0">
                <span>PayOnce</span>
            </a>
        </div>

        <form action="{{ route('password.update') }}" method="post">
        
            @csrf

            @if( !empty( request('token') ) )
                <input type="hidden" value="{{ request('token') }}" name="token">
            @endif

            <div class="form-group">
                <label class="text-label" for="email_2">Email Address:</label>
                <div class="input-group input-group-merge">
                    <input id="email_2" type="email" required name="email" class="form-control form-control-prepended" placeholder="john@doe.com" value="{{ old('email', $request->email) }}">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="far fa-envelope"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="text-label" for="password_2">Password:</label>
                <div class="input-group input-group-merge">
                    <input id="password_2" type="password" name="password" required class="form-control form-control-prepended" placeholder="Enter your password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="text-label" for="password_2">Confirm Password:</label>
                <div class="input-group input-group-merge">
                    <input id="password_2" type="password" name="password_confirmation" required class="form-control form-control-prepended" placeholder="Confirm password">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <span class="fa fa-key"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-block btn-primary" type="submit">Reset Password</button>
            </div>
        </form>
    </div>


    <!-- jQuery -->
    <script src="{{asset('assets/vendor/jquery.min.js')}}"></script>

    <!-- Bootstrap -->
    <script src="{{asset('assets/vendor/popper.min.js')}}"></script>
    <script src="{{asset('assets/vendor/bootstrap.min.js')}}"></script>

    <!-- Perfect Scrollbar -->
    <script src="{{asset('assets/vendor/perfect-scrollbar.min.js')}}"></script>

    <!-- DOM Factory -->
    <script src="{{asset('assets/vendor/dom-factory.js')}}"></script>

    <!-- MDK -->
    <script src="{{asset('assets/vendor/material-design-kit.js')}}"></script>

    <!-- App -->
    <script src="{{asset('assets/js/toggle-check-all.js')}}"></script>
    <script src="{{asset('assets/js/check-selected-row.js')}}"></script>
    <script src="{{asset('assets/js/dropdown.js')}}"></script>
    <script src="{{asset('assets/js/sidebar-mini.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>

    <!-- App Settings (safe to remove) -->
    <script src="{{asset('assets/js/app-settings.js')}}"></script>


</body>

</html>