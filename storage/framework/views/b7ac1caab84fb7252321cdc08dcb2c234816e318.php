

<?php $__env->startSection('page_title', 'Admins'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Roles</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">All Roles</li>
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
                                <th>Roles</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            
                            $counter = 1;
                            ?>
                            <?php $__currentLoopData = $userRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                
                                <tr>
                                    <td><?php echo e($counter); ?></td>
                                    <td><?php echo e($role->role_name); ?></td>                                 
                                    <td class="record_actions">
                                        <a href="<?php echo e(url('/view-user-role/'.$role->id)); ?>" title="view" class="btn btn-primary-btn">View</a>
										<?php if($role->id != 1): ?>
                                        <a href="<?php echo e(url('/edit-user-role/'.$role->id)); ?>" title="edit" class="btn btn-primary-btn"><i class="fa fa-edit"></i></a>
										<a href="<?php echo e(url('/delete-user-role/'.$role->id)); ?>" title="delete" class="btn btn-danger1" onclick="return confirm('Are you sure you want to delete <?php echo e($role->role_name); ?> ?')"><i class="fa fa-trash"></i></a>
                                        <?php endif; ?>
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