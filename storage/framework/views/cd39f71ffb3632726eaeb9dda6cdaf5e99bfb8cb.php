

<?php $__env->startSection('page_title', 'Manage Role'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Add Role</h4>
        </div>
      </div>
      <!-- /row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
            <h3 class="box-title">Basic Information</h3>
            <form class="form-material form-horizontal m-t-30" action="<?php echo e(url('/add-user-role')); ?>" method="post">
              <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
			        <div class="form-group">
                <label class="col-md-12" for="example-text">Role</label>
                <div class="col-md-12">
                  <input type="text" id="user_role_name" name="role_name" class="form-control" value="" placeholder="Role Name" required="">
                </div>
              </div>
              
			  <div class="form-group">
                  <label class="col-md-12" for="example-text">Permissions</label>
                  <div class="col-md-12">
                        <?php $__currentLoopData = $module_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module_key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <input type="checkbox" name="modules[]" value="<?php echo e($module_key); ?>"/> <?php echo e($module); ?><br/>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
              </div> 
              
              <button type="submit" class="btn btn-info waves-effect waves-light m-r-10 role_save" >Submit</button>
              <button type="reset"  class="btn btn-inverse waves-effect waves-light" onclick="javascript:history.go(-1)">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>