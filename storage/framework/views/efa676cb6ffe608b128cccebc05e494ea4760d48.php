

<?php $__env->startSection('page_title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Add User</h4>
        </div>
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title">Basic Information</h3>
                <form class="form-material form-horizontal m-t-30" action="<?php echo e(url('/add-users')); ?>" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Name</label>
                        <div class="col-md-12">
                            <input type="text" id="name" name="name" class="form-control" value="" placeholder="Name" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Email</label>
                        <div class="col-md-12">
                            <input type="email" id="email" name="email" class="form-control" value="" placeholder=Email required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Password</label>
                        <div class="col-md-12">
                            <input type="password" id="password" name="password" class="form-control" value="" placeholder="Password" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Confirm Password</label>
                        <div class="col-md-12">
                            <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" value="" placeholder="Confirm Password" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Role</label>
                        <div class="col-md-12">
                            <select class="form-control" name="role" id="role" required="required">
                                <option value="">Select</option>
                                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($role->id); ?>"><?php echo e($role->role_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>                           


                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Submit</button>
                    <button type="reset" class="btn btn-inverse waves-effect waves-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
    $(document).on('blur', '#confirmPassword', function () {
        var password = $('#password').val();
        var confirmpassword = $('#confirmPassword').val();

        if (password != confirmpassword) {
            $('#password').val('');
            $('#confirmPassword').val('');
            alert("Password doesnot match. please Check");
            return false;

        }
    });
});
</script>

<style>
    .top-left-part{
        height:50px !important;
    }
</style>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>