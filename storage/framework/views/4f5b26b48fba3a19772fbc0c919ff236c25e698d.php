

<?php $__env->startSection('page_title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
    <form class="form-horizontal form-material" id="loginform" role="form" method="POST" action="<?php echo e(url('/login')); ?>">
        <h3 class="box-title m-b-20 text-center"><img src="<?php echo e(URL::asset('/')); ?>assests/plugins/images/login-logo.png" alt="AIST"/></h3>
        <?php echo e(csrf_field()); ?>

        <div class="form-group <?php echo e($errors->has('email') ? ' has-error' : ''); ?>">
            <input id="email" type="email" class="form-control" name="email" placeholder="E-Mail Address" value="<?php echo e(old('email')); ?>" required="">
        </div>
        <div class="form-group<?php echo e($errors->has('password') ? ' has-error' : ''); ?>">
            <input id="password" type="password" class="form-control" placeholder="Password" name="password" required="">
        </div>
        <div class="form-group" style="margin-bottom: 0px;">
            <div class="col-md-12">
                <div class="checkbox checkbox-primary pull-left p-t-0">
                    <input type="checkbox" name="remember">
                    <label for="checkbox-signup"> Remember me </label>
                </div>
                <a href="<?php echo e(url('/password/reset')); ?>" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot pwd?</a> </div>
        </div>
        <div class="form-group">
            <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                    
                    <button type="submit" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light">
                        <i class="fa fa-btn fa-sign-in"></i> Login
                    </button>
                </div>
            </div>
            
        </div>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>