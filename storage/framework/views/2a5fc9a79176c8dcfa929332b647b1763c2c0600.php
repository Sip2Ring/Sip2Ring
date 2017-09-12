<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <title>Sip2Ring</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
        <!-- Styles -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/app.css">
        <!-- Bootstrap Core CSS -->
        <link href="<?php echo e(URL::asset('/')); ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>css/main.css" />
    </head>
    <body>
        <div class="container-fluid login">
            <div class="col-sm-6 leftContainer">
                <div class="logInContainer">
                    <h1>Welcome to Sip2Ring</h1>
                    <div class="sub-header">Prepare for liftoff</div>
                    <form class="form-horizontal form-material" id="loginform" role="form" method="POST" action="<?php echo e(url('/login')); ?>">
            <?php echo e(csrf_field()); ?>

            <div class="responsiveForm">
                            <div class="col-sm-12">
                                <label class="sr-only" for="email">Email:</label>
                                <input id="email" type="email" class="form-control input-md" name="email" placeholder="E-Mail Address" value="<?php echo e(old('email')); ?>" required="">
            
                            </div>
                            <div class="col-sm-12">
                                <label class="sr-only" for="pwd">Password:</label>
                                <input id="password" type="password" class="form-control input-md" placeholder="Password" name="password" required="">
                            </div>
                            <div class="checkbox col-sm-12">
                                <label><input type="checkbox" name="remember"> Remember me</label>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-primary btn-block">Login</button>
                            </div>
                            <div class="col-sm-12 reg_signup"> 
                                <p>I want to : &nbsp; 
                                    <a href="https://www.ringba.com/signup/" class="home_link">Register</a>
                                    &nbsp;&nbsp;|&nbsp; 
                                    <a class="home_link" href="#/forgot-password">Reset Password</a> 
                                </p> 
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <?php if(count($errors) > 0): ?>
                                <div class="alert alert-danger">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div><?php echo e($error); ?></div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
        </form>
                </div>
            </div>
            <div class="col-sm-6 rightContainer">
                <div class="logoContainer">
                    <div class="logo">
                        <img src="img/logo/eliteadmin-logo.png" alt="Logo" class="img-responsive">
                    </div>
                    <div class="logoText">Enterprise call tracking for professional marketers.</div>
                    <div class="socialBox">
                        <span class="fa fa-lg fa-facebook"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                        <span class="fa fa-lg fa-twitter"></span>
                    </div>
                </div>
            </div>
        </div>


        <!-- Scripts -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="<?php echo e(URL::asset('/')); ?>js/validate.js"></script>
    </body>
</html>
