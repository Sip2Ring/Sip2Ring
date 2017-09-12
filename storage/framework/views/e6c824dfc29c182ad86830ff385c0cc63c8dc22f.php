<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('crm.site_title')); ?> - <?php echo $__env->yieldContent('page_title'); ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo e(URL::asset('/')); ?>assests/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" />
    <!-- morris CSS -->
    <link href="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- animation CSS -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>css/animate.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo e(URL::asset('/')); ?>css/style.css" />
    <!-- color CSS -->
    <link href="<?php echo e(URL::asset('/')); ?>css/colors/gray-dark.css" id="theme"  rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/1.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />

</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <div id="wrapper">
        <!-- Header -->
        <?php echo $__env->make('layouts.dashboard.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <!-- Left navbar-header -->
        <?php echo $__env->make('layouts.dashboard.leftbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <!-- Page Content -->
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
        <!-- /#page-wrapper -->
        <!-- Footer -->
        <?php echo $__env->make('layouts.dashboard.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </div>
    <!-- jQuery -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>js/jquery.slimscroll.js"></script>
    <!--Morris JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/raphael/raphael-min.js"></script>
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/morrisjs/morris.js"></script>
    <!-- Sparkline chart JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- jQuery peity -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/peity/jquery.peity.min.js"></script>
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/peity/jquery.peity.init.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo e(URL::asset('/')); ?>js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo e(URL::asset('/')); ?>js/custom.min.js"></script>
    <script src="<?php echo e(URL::asset('/')); ?>js/dashboard1.js"></script>
    <!--Style Switcher -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
    <!-- jQuery -->
    <script src="<?php echo e(URL::asset('/')); ?>assests/plugins/bower_components/datatables/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.alert.alert-success').fadeOut(10000);
            $('.alert.alert-danger').fadeOut(10000);
            $('.sidebar').css({
                'position': 'fixed'               
            });
            $('.navbar-header').css({
                    'position': 'fixed'               
            });
            $('#myTable').DataTable({
                responsive: true
            });
            $('#example23').DataTable({
                "pageLength": 20,
                "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]]
            });
            $('#bucketTable').DataTable({
                "columnDefs": [{
                            "targets": 0,
                            "orderable": false,                            
                }],
                "order":[[ 1, "desc" ]],
                "pageLength": 20,
                "lengthMenu": [[10, 20, 30, -1], [10, 20, 30, "All"]]
            });
        });
        
    </script>
    <!-- end - This is for export functionality only -->
    <?php echo $__env->yieldContent('footer'); ?>
    <div id="overlay" style="display: none;">
        <p>Please wait while processing...</p>
    </div>
</body>
</html>