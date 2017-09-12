

<?php $__env->startSection('page_title', 'Add Template'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Add Template</h4>
        </div>
      </div>
      <!-- /row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
            <h3 class="box-title">Basic Information</h3>
            <form class="form-material form-horizontal m-t-30" action="<?php echo e(url('/add-template')); ?>" method="post">
              <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">

              <div class="form-group">
                  <label class="col-md-12" for="example-text">Template Name</label>
                  <div class="col-md-12">
                    <input type="text" id="example-text" name="template_name" class="form-control" value="" placeholder="Template Name" required="">
                  </div>
              </div>
              <div class="form-group">
                <label class="col-md-12" for="example-text">Template AWS Name</label>
                <div class="col-md-12">
                    <input type="text" pattern="^(www?://)?([a-zA-Z0-9]([a-zA-ZäöüÄÖÜ0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,6}$" id="example-text" name="aws_name" class="form-control" value="" placeholder="Template AWS Name" required="">
                    <p style="color:red">NOTE: The AWS link must be URL (Example: www.aws.com)</p>
                </div>
              </div>
              <!---BUCKET REGIONS-->
              <div class="form-group">
                <label class="col-md-12" for="example-text">Bucket Region</label>
                <div class="col-md-12">
                    <select name="template_region" id="template_region" class="form-control" required="">
                        <option value="">Please select Region</option>
                        <?php $__currentLoopData = $bucketRegions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($regions->region_code); ?>"><?php echo e($regions->region_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span></span>
                </div>
              </div>

              <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Next</button>
              <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse waves-effect waves-light">Cancel</button>
            </form>
          </div>
        </div>
      </div>
 </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>