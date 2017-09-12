

<?php $__env->startSection('page_title', 'Manage Templates'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
      <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
          <h4 class="page-title">Upload Template Files</h4>
        </div>
      </div>
      <!-- /row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="white-box">
              <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
              <input type="hidden" name="template_key" value="<?php echo e($templateId); ?>">
              <div class="form-group">
                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" id="addFiles">Add Files</button>
                <button type="button" class="btn btn-primary waves-effect waves-light m-r-10" id="uploadFilesBtn" style="display: none;" > Upload Files</button>
                <button type="button" class="btn btn-info waves-effect waves-light m-r-10" id="createFolder">Create Folder</button>
                <a href="<?php echo e(url('/list-crm-templates')); ?>"><button type="submit" class="btn btn-inverse waves-effect waves-light m-r-10">Complete Template</button></a>
                <?php if(!empty($folderID)){ echo '<br><a href="javascript:window.history.go(-1);" class="btn btn-info waves-effect waves-light m-r-10" style="margin-top: 10px;"><i class="fa fa-level-up"></i> Up</a>';} ?>
              </div>
              <div class="table-responsive">
                <table id="example23" class="table table-striped table_grid">
                  <thead>
                  <tr>
                    <th>Name</th>
                    <th>Created at</th>
                    <th>Modified at</th>
                    <th>Size</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $__currentLoopData = $templateFolders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folderDetail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><a href="<?php echo e(url()->current().DIRECTORY_SEPARATOR.$folderDetail['folder_name']); ?>" class="waves-effect" style="color: #333333"><i class="fa fa-folder-open"></i> <span class=""> <?php echo e($folderDetail->folder_name); ?> </span></a></td>
                      <td>-</td>
                      <td>-</td>
                      <td>-</td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  <?php $__currentLoopData = $templateFiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fileDetails): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                      <td><a href="<?php echo e(url('/template_data').DIRECTORY_SEPARATOR.$templateId.DIRECTORY_SEPARATOR.$fileDetails->file_path); ?>" target="_blank"><?php echo e($fileDetails->file_name); ?></a></td>
                      <td><?php echo e($fileDetails->created_at); ?></td>
                      <td><?php echo e((!empty($fileDetails->modified_at)) ? $fileDetails->modified_at : '-'); ?></td>
                      <td><?php echo e(File::size(public_path('template_data').DIRECTORY_SEPARATOR.$templateId.DIRECTORY_SEPARATOR.$fileDetails->file_path).' bytes'); ?></td>
                    </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tbody>
                </table>
              </div>
            
          </div>
        </div>
      </div>
 </div>

<!--create folder popup-->
<div id="createFolderModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <form name="add_folder" id="add_folder" action="<?php echo e(url('/add-folder')); ?>" method="post">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h2>Please provide folder name</h2>
          <input type="hidden" id="pass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
          <input type="hidden" id="templateID" name="template_id" value="<?php echo e($templateId); ?>">
          <input type="hidden" id="parentFolder" name="parent_folder" value="<?php echo e($folderID); ?>">
          <input type="hidden" id="parentFolderName" name="parent_folder_name" value="<?php echo e($folderName); ?>">
          <div class="row" style="padding: 0 0 10px 10px;">
            Folder Name : <input type="text" name="folder_name" id="folderName" required><span></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="addFolder" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!--add files form-->
<form name="add_files" id="addFileForm" action="<?php echo e(url('/add-files')); ?>" method="post" enctype="multipart/form-data" style="display: none;">
  <input name="templateFiles[]" type="file" id="underBucket" multiple style="display: none;" />
  <input type="hidden" id="pass_token" name="_token" value="<?php echo e(csrf_token()); ?>">
  <input type="hidden" id="templateID" name="template_id" value="<?php echo e($templateId); ?>">
  <input type="hidden" id="parentFolder" name="parent_folder" value="<?php echo e($folderID); ?>">
  <input type="hidden" id="uploadFilePath" name="upload_file_path" value="<?php echo e($folderIN); ?>">
  <input type="hidden" id="parentFolderName" name="parent_folder_name" value="<?php echo e($folderName); ?>">
</form>

<script>
  $(document).ready(function(){
    //add file event
    $('#addFiles').click(function () {
      $('#underBucket').trigger('click');
    });
    //create folder event
    $('#createFolder').click(function () {
      $('#folderName').val('');
      $('#createFolderModal').modal('show');
    });
    //upload files event
    $('#uploadFilesBtn').click(function () {
      $('#addFileForm').submit();
    });
    //add folder bucket
    $(document).on('click','#addFolder',function(){
      var templateID = $('#templateID').val();
      var folderName = $('#folderName').val();
      var parentFolder = $('#parentFolder').val();
      var parentFolderName = $('#parentFolderName').val();
      var uploadFilePath = $('#uploadFilePath').val();
      var passToken = $('#pass_token').val();
      var url = '<?php echo e(url('/add-folder')); ?>';
      var successRedirect = '<?php echo e(url()->current()); ?>';
      customValid = true;

      var totalError  = [];
      var totalSucess = [];
      if(folderName==""){
        totalError.push('folderName');
      }else{
        totalSucess.push('folderName');
      }
      if(totalError.length>0)
      {
        for (count = 0; count <= totalError.length; count++) {
          $('#' + totalError[count]).css({"background-color": "#F2C1C1", "border": "1px solid #FF0000"});
          $('#' + totalError[count]).parent().find('span').html("This field can not be empty!");
          customValid = false
        }
      }
      if(totalSucess.length>0)
      {
        for (count = 0; count <= totalSucess.length; count++) {
          $('#' + totalSucess[count]).css({"background-color": "#FFFFFF", "border": "1px solid #D7D7D7"});
          $('#' + totalSucess[count]).parent().find('span').html('');
        }
      }
      if (customValid == false){
        return false;
      }else{
        $('#overlay').show();
        $.ajax({
          type: 'POST',
          'url': url,
          async: false,
          data: {
            '_token': passToken,
            'template_id': templateID,
            'folder_name': folderName,
            'parent_folder': parentFolder,
            'parent_folder_name': parentFolderName,
            'upload_file_path': uploadFilePath,
          },
          success:function(data){
            $('#overlay').hide();
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
      }
    });
    //show uplaod button after selected files
    $(function($) {
      $('input[type="file"]').change(function() {
        if ($(this).val()) {
          $('#uploadFilesBtn').show();
        }
      });
    });
  });

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>