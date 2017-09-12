

<?php $__env->startSection('page_title', 'Set Parameters'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Set Parameters</h4>
        </div>
    </div>
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title">Basic Information</h3>
                <form class="form-material form-horizontal m-t-30" action="<?php echo e(url('/add-bucket-params')); ?>" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <!---REGIONS-->
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Country Code</label>
                        <div class="col-md-12">
                            <select name="bucket_region" id="bucket_region" class="form-control" required="">
                                <option value="">Please select Network</option>
                                <?php $__currentLoopData = $bucketRegions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $regions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($regions->region_value); ?>"><?php echo e($regions->region_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <!---SHORT CODES-->
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Network</label>
                        <div class="col-md-12">
                            <select name="bucket_short_code" id="bucket_short_code" class="form-control" required="">
                                <option value="">Please select short code</option>
                                <?php $__currentLoopData = $bucketShortCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shortCode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($shortCode->shortcode_value); ?>"><?php echo e($shortCode->shortcode_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <span></span>
                        </div>
                    </div>
                    <!--DESCRIPTION-->
                    <div class="form-group">
                        <label class="col-md-12" for="example-text">Parameter String</label>
                        <div class="col-md-12">
                            <textarea class="form-control" rows="3" name="bucket_parameters"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info waves-effect waves-light m-r-10">Save</button>
                    <button type="button" onclick="javascript:history.go(-1)" class="btn btn-inverse waves-effect waves-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>