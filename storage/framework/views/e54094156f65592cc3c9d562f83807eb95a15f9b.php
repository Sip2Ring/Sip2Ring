

<?php $__env->startSection('page_title', 'Manage Configurations'); ?>

<?php $__env->startSection('content'); ?>

<?php //print_r($editmodule); exit; ?>
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">View Roles</h4>
        </div>
      </div>
      <!-- /row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
            <h3 class="box-title">Basic Information</h3>
            <form class="form-material form-horizontal m-t-30" action="<?php echo e(url('/edit-user-role').DIRECTORY_SEPARATOR.$viewUserRoles['id']); ?>" method="post">
              <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
			   <div class="form-group">
                <label class="col-md-12" for="example-text">Role Name</label>
                <div class="col-md-12">
                  <?php echo e($viewUserRoles->role_name); ?>

                </div>
              </div>
			  
			  <div class="form-group">
                  <label class="col-md-12" for="example-text">Permissions</label>
                  <div class="col-md-12">
                        <?php $__currentLoopData = $module_arr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $module_key => $module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <input type="checkbox" name="modules[]" value="<?php echo e($module_key); ?>" <?php if(in_array($module_key,$editmodule)) echo "checked='checked'" ;?> onclick="return false;" /> <?php echo e($module); ?><br/>
                        
						<?php //if(in_array($module_key,$editmodule)) echo '<span style="color:green;">'.$module.'<span>,'; ?>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
              </div>
              
              
              <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse waves-effect waves-light">Back</button>
            </form>
          </div>
        </div>
      </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>