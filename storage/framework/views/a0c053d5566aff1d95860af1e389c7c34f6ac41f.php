

<?php $__env->startSection('page_title', 'Bucket Parameters'); ?>

<?php $__env->startSection('content'); ?>
<!-- Page Content -->
<div class="container-fluid">
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title">Bucket Parameters</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="#">Dashboard</a></li>
                <li class="active">Bucket Parameters</li>
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
                                <th>No</th>
                                <th>Bucket Region</th>
                                <th>Bucket Short Code</th>
                                <th>Parameter String</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $counter = 1; ?>
                            <?php $__currentLoopData = $bucketParams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bucket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($counter); ?></td>
                                <td><?php echo e((!empty($bucket->bucket_region)) ? strtoupper($bucket->bucket_region) : "-"); ?></td>
                                <td><?php echo e((!empty($bucket->bucket_short_code)) ? strtoupper($bucket->bucket_short_code) : "-"); ?></td>
                                <td><?php echo e((!empty($bucket->bucket_parameters)) ? $bucket->bucket_parameters : "-"); ?></td>
                                <td class="record_actions">
                                    <a href="<?php echo e(url('/edit-bucket-params/'.$bucket->id)); ?>" title="edit" class="btn btn-primary-btn"><i class="fa fa-edit"></i></a>
                                    <a href="<?php echo e(url('/delete-bucket-params/'.$bucket->id)); ?>" title="delete" class="btn btn-danger1" onclick="return confirm('Are you sure you want to delete <?php echo e($bucket->bucket_name); ?> ?')"><i class="fa fa-trash"></i></a>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dataTable', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>