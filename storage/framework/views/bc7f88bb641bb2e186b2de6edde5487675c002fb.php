<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('crm.site_title')); ?> - <?php echo $__env->yieldContent('page_title'); ?></title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo e(URL::asset('/')); ?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>css/animate.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>css/style.css" />
    <!-- Login Slider -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>css/style4.css" />
    <!-- color CSS -->

</head>
<body id="app-layout">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="overlay" style="display: none;">
        <p>Please wait while processing...</p>
    </div>
    <!--HEADERS-->
    <?php if( Auth::user() && (Auth::user()->isSuperAdmin()) ): ?>
    <nav class="navbar navbar-light bg-faded navbar-static-top navbar-toggleable-sm">
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#nav-content" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo e(url('/')); ?>"><?php echo e(config('crm.site_title')); ?></a>
        <div id="nav-content" class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto mt-2 mt-md-0">
                <li class="nav-item"><a href="<?php echo e(url('/')); ?>" class="nav-link">Home</a></li>
                <?php if( Auth::user() && (Auth::user()->isSuperAdmin()) ): ?>
                    <li class="nav-item"><a href="<?php echo e(url('/admins')); ?>" class="nav-link">Admins</a></li>
                <?php endif; ?>
                <?php if( Auth::user() && (Auth::user()->isAdmin()) ): ?>
                    <li class="nav-item"><a href="<?php echo e(url('/clients')); ?>" class="nav-link">Buckets</a></li>
                <?php elseif( Auth::user() && (!Auth::user()->isAdmin()) ): ?>
                    <li class="nav-item"><a href="<?php echo e(url('/invoices')); ?>" class="nav-link">Invoices</a></li>
                    <li class="nav-item"><a href="<?php echo e(url('/projects')); ?>" class="nav-link">Projects</a></li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav my-2 my-md-0">
                <?php if( Auth::guest() ): ?>
                    <li class="nav-item"><a href="<?php echo e(url('/login')); ?>" class="nav-link">Login</a></li>
                <?php else: ?>
                    <li class="dropdown nav-item">
                        <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown" role="button"aria-expanded="false">
                            <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li class="dropdown-item"><a href="<?php echo e(url('/update-password')); ?>"><i class="fa fa-btn fa-cog"></i>Update Password</a></li>
                            <li class="dropdown-item">
                                <a href="<?php echo e(url('/logout')); ?>" data-method="post" data-token="<?php echo e(csrf_token()); ?>"><i class="fa fa-btn fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <?php endif; ?>
    <!--HEADERS-->
    <div id="wrapper" class="login-register">
        <ul class="cb-slideshow">
            <li><span>Image 01</span><div></div></li>
            <li><span>Image 02</span><div></div></li>
            <li><span>Image 03</span><div></div></li>
            <li><span>Image 04</span><div></div></li>
        </ul>
        <div class="login-box ">
            <div class="white-box login-bg">
                <?php if(session('status')): ?>
                    <div class="alert alert-<?php echo e(session('status_level') ?: "success"); ?>">
                        <div><?php echo e(session('status')); ?></div>
                    </div>
                <?php endif; ?>
                <?php if(count($errors) > 0): ?>
                    <div class="alert alert-danger">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo e(URL::asset('/')); ?>js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>js/custom.min.js"></script>
    <!--Style Switcher -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <?php echo $__env->yieldContent('footer'); ?>
</body>
</html>
