
<?php $__env->startSection('page_title', 'Template Backup'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Template Backup</h4>
        </div>
          <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
              <ol class="breadcrumb">
                  <li><a href="#">Dashboard</a></li>
                  <li class="active">Template Backup</li>
              </ol>
          </div>
      </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="white-box clearfix">
                    <div class="m-b-10 pull-left">
                        <a style="cursor: pointer;">
                            <span class="label label-info" id="createBucketBackup">Create backup</span>
                        </a>
                    </div>
                    <div class="table-responsive col-sm-12">
                        <table id="example23" class="table table-striped table_grid">
                            <thead>
                            <tr>
                                <th><input type="checkbox" class="bulkCheckbox"></th>
                                <th>S.No.</th>
                                <th>Template Name</th>
                                <th>Bucket Region</th>
                                <th>Bucket URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $counter = 1; ?>
                            <?php $__currentLoopData = $templatesArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $templatesVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td> <input type="checkbox" name="awsAccounts[]" value="<?php echo e($templatesVal['id']); ?>" class="bucket_checkbox"></td>
                                    <td><?php echo e($counter); ?></td>
                                    <td><?php echo e($templatesVal['template_name']); ?></td>
                                    <td><?php echo e($templatesVal['template_region']); ?></td>
                                    <td><?php echo e($templatesVal['aws_name']); ?></td>
                                </tr>
                                <?php $counter++; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
 </div>
    <form method="POST" id="backup_form_submit" action="<?php echo e(url('/export-template')); ?>">
        <input type="hidden" id="template_id" name="template_id" id="aws"/>
        <input type="hidden" id="dpass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
    </form>
    <script>
        $(document).ready(function () {
            $(".bucket_checkbox,.bulkCheckbox").attr('checked', false);
            //select all checkbox at single click
            $('.bulkCheckbox').click(function() {
                if ($(this).prop('checked')) {
                    $('.bucket_checkbox').prop('checked', true);
                } else {
                    $('.bucket_checkbox').prop('checked', false);
                }
            });
        });

        //create buckets backup
        $(document).on('click','#createBucketBackup',function() {
            var awsAccounts = [];
            var atLeastOneIsChecked = $('input[name=\"awsAccounts[]\"]:checked').length;
            if (atLeastOneIsChecked>0) {
                $('#template_id').val ($('input[name=\"awsAccounts[]\"]:checked').map(function() {
                    return this.value; }).get().join(','));
                 $('#overlay').show();
                 $('#backup_form_submit').submit();
            } else {
                alert('Please Select at least one aws template!');
                return false;
            }
        });


    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>