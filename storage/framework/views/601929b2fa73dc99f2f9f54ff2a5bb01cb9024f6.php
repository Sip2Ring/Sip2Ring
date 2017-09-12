

<?php $__env->startSection('page_title', 'Manage Configurations'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Add Configuration</h4>
        </div>
      </div>
      <!-- /row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
            <h3 class="box-title">Basic Information</h3>
            <form class="form-material form-horizontal m-t-30" action="<?php echo e(url('/add-auth')); ?>" method="post">
              <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
			  <div class="form-group">
                <label class="col-md-12" for="example-text">AWS Server Name</label>
                <div class="col-md-12">
                  <input type="text" id="aws_server_name" name="aws_server_name" class="form-control" value="" placeholder="AWS Server Name" required="">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-12" for="example-text">Key</label>
                <div class="col-md-12">
                  <input type="text" id="example-text" name="key" class="form-control" value="" placeholder="Key" required="">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-12" for="example-text">Secret</label>
                <div class="col-md-12">
                  <input type="text" id="example-text" name="secret" class="form-control" value="" placeholder="Secret" required="">
                </div>
              </div>
              <div class="form-group">
                  <label class="col-md-12" for="example-text">Initial Counter</label>
                  <div class="col-md-12">
                      <input type="text" id="example-text" name="aws_counter" class="form-control" value="" placeholder="Secret" required="">
                  </div>
              </div>
              <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Submit</button>
              <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse waves-effect waves-light">Cancel</button>
            </form>
          </div>
        </div>
      </div>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>