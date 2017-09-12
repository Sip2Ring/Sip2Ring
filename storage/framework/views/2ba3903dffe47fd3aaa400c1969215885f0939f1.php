

<?php $__env->startSection('page_title', 'Templates'); ?>

<?php $__env->startSection('content'); ?>
  <!-- Page Content -->
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Templates</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
          <ol class="breadcrumb">
            <li><a href="#">Dashboard</a></li>
            <li class="active">All Templates</li>
          </ol>
        </div>
      </div>
      <!-- /row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
            <div class="table-responsive">
              <table id="example23" class="table table-striped table_grid">
                <thead>
                <tr>
                  <th></th>
                  <th>Name</th>
                  <th>URL</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php $counter = 1;?>
                <?php $__currentLoopData = $templates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr>
                    <td><?php echo e($counter); ?></td>
                    <td><?php echo e($data->template_name); ?></td>
                    <td><?php echo e((!empty($data->aws_name)) ? $data->aws_name : "-"); ?></td>
                    <td><?php echo e($data->created_at); ?></td>
                    <td class="record_actions">
                      <a href="<?php echo e(url('/upload-template-files/'.$data->id)); ?>" title="edit" class="btn btn-primary-btn"><i class="fa fa-edit"></i></a>
                      <a href="<?php echo e(url('/delete-template/'.$data->id)); ?>" title="delete" class="btn btn-danger-btn" onclick="return confirm('Are you sure you want to delete <?php echo e($data->template_name); ?> ?')"><i class="fa fa-trash"></i></a>
                      <a data-toggle="modal" title="Copy Template To AWS" class="btn btn-danger1 copyBucketToAws" data-target="#copytoaws_bucket_dialog"  onclick=" $('#duplicateForToAws').val('<?php echo e($data['aws_name']); ?>');
                              $('#new_bucket_name').val('<?php echo e($data['aws_name']); ?>');$('#duplicateToAwsRegion').val('<?php echo e($data['template_region']); ?>');$('#template_id').val('<?php echo e($data['id']); ?>');"><i class="fa fa-copy"></i></a>
                    </td>
                  </tr>
                  <?php $counter++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- /.row -->
    </div>
    <!-- /.container-fluid -->

  <div id="copytoaws_bucket_dialog" class="modal fade form-group " role="dialog">
    <div class="modal-dialog">
      <form name="copytoaws_bucket" id="copytoaws_bucket" action="<?php echo e(url('/move-tempalte-to-new-aws')); ?>" method="post">
        <input type="hidden" id="dpass_token_aws" name="_token" value="<?php echo e(csrf_token()); ?>">
        <input type="hidden" id="duplicateForToAws" name="duplicate_for" value="">
        <input type="hidden" id="duplicateToAwsRegion" name="duplicate_aws_region" value="">
        <input type="hidden" id="template_id" name="template_id" value="">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h2>Select AWS Server:</h2>
            <select name="create_count" class="form-control" id="aws_server_id" required>
              <?php $__currentLoopData = $allAwsServer; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $allAwsServerVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($allAwsServerVal['id']); ?>"><?php echo e(ucwords($allAwsServerVal['aws_name'])); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <h2>New Bucket Name:</h2>
            <input type="text" name="new_bucket_name" id="new_bucket_name" required class="form-control">
            <p style="color:red;">i.e. Just change the url or bucket name as per your requirement</p>
          </div>
          <div class="modal-footer">
            <button type="button" id="duplicateBucketToAws" class="btn btn-primary">Create</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <!-- copy to aws popup -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
  <script>
    $(document).ready(function($){
      //duplciate bucket to aws
  $(document).on('click','#duplicateBucketToAws',function(){
    var duplicateFor                = $('#duplicateForToAws').val();
    var duplicateToAwsRegion        = $('#duplicateToAwsRegion').val();
    var awsServerId                 = $('#aws_server_id').val();
    var passToken                   = $('#dpass_token_aws').val();
    var new_bucket_name             = $('#new_bucket_name').val();
    var template_id                 = $('#template_id').val();
    var url = '<?php echo e(url('/move-tempalte-to-new-aws')); ?>';
    // alert(duplicateFor);
    var successRedirect = '<?php echo e(url('/list-crm-templates')); ?>';
    $('div#overlay').show();
    $.ajax({
      type: 'GET',
      'url': url,
      async: false,
      data: {
        '_token': passToken,
        'duplicate_for': duplicateFor,
        'duplicateToAwsRegion': duplicateToAwsRegion,
        'aws_server_id': awsServerId,
        'new_bucket_name': new_bucket_name,
        'template_id': template_id,
      },
      success:function(data){
        $('div#overlay').hide();
        var res = jQuery.parseJSON(data);
        if(res.type=='success'){
          window.location.href = successRedirect;
          return false;
        }
        if(res.type=='error'){
          alert(res.message);
          return false;
        }
      }
    });
    return false;
  });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dataTable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>