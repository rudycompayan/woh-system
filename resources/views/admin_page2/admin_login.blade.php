<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login - WOH Admin</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link href="admin_page/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="admin_page/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" />

    <link href="admin_page/css/font-awesome.css" rel="stylesheet">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600" rel="stylesheet">

    <link href="admin_page/css/style.css" rel="stylesheet" type="text/css">
    <link href="admin_page/css/pages/signin.css" rel="stylesheet" type="text/css">

</head>

<body>

<div class="navbar navbar-fixed-top">

    <div class="navbar-inner">

        <div class="container">

            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>

            <a class="brand" href="index.html">
                WOH Admin Login
            </a>

            <div class="nav-collapse">
                <ul class="nav pull-right">

                    <li class="">
                        <a href="signup.html" class="">
                            Don't have an account?
                        </a>

                    </li>

                    <li class="">
                        <a href="index.php" class="">
                            <i class="icon-chevron-left"></i>
                            Back to Homepage
                        </a>

                    </li>
                </ul>

            </div><!--/.nav-collapse -->

        </div> <!-- /container -->

    </div> <!-- /navbar-inner -->

</div> <!-- /navbar -->



<div class="account-container">

    <div class="content clearfix">

        <form action="{!! action('AdminController@post_admin_login') !!}" method="post">

            <h1>Admin Login</h1>

            <div class="login-fields">

                <p>Please provide your details</p>
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(!empty($error_msg))
                    <div class="alert alert-danger">
                        <ul>
                            <li>{!! $error_msg !!}</li>
                        </ul>
                    </div>
                @endif

                <div class="field">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="" placeholder="Username" class="login username-field" />
                </div> <!-- /field -->

                <div class="field">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" value="" placeholder="Password" class="login password-field"/>
                </div> <!-- /password -->

            </div> <!-- /login-fields -->

            <div class="login-actions">

				<span class="login-checkbox">
					<input id="Field" name="Field" type="checkbox" class="field login-checkbox" value="First Choice" tabindex="4" />
					<label class="choice" for="Field">Keep me signed in</label>
				</span>

                <button class="button btn btn-success btn-large" onclick="form.submit()">Sign In</button>

            </div> <!-- .actions -->



        </form>

    </div> <!-- /content -->

</div> <!-- /account-container -->



<div class="login-extra">
    <a href="#">Reset Password</a>
</div> <!-- /login-extra -->


<script src="admin_page/js/jquery-1.7.2.min.js"></script>
<script src="admin_page/js/bootstrap.js"></script>

<script src="admin_page/js/signin.js"></script>

</body>

</html>
