

<?php $__env->startSection('page_title', 'Admins'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">Manage Configurations</h4>
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <ol class="breadcrumb">
                    <li><a href="#">Dashboard</a></li>
                    <li class="active">All Configurations</li>
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
                                <th>Aws Server Name</th>
                                <th>Key</th>
                                <th>Secret</th>
                                <th>Initial Counter</th>
                                <th>Is Primary Network?</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $regionArray = array('jp'=>'Japan (Tokyo)', 'fr'=>'France', 'au'=>'Australia') ;
                            $counter = 1;
                            ?>
                            <?php $__currentLoopData = $configAuth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    //status
                                    if($config->status=='active'){
                                    $statusOptions = '<a href="#" style="color:green;"><b>Active</b></a> /  <span>In-active</span>';
                                    }else{
                                        $statusOptions = '<a href="'.url('/config/'.$config->id.'/active/').'" style="color:green;">Active</a> /  <a href="'.url('/config/'.$config->id.'/inactive/').'" style="color:red;">In-active</a>';
                                    }
                                    //primary network
                                    if($config->primary_network=='yes'){
                                        $primaryNetwork = '<a href="#" style="color:green;"><b>Yes</b></a> /  <span>No</span>';
                                    }else{
                                        $primaryNetwork = '<a href="'.url('/primary/'.$config->id.'/active/').'" style="color:green;">Yes</a> /  <a href="'.url('/primary/'.$config->id.'/inactive/').'" style="color:red;">No</a>';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo e($counter); ?></td>
                                    <td><?php echo e($config->aws_name); ?></td>
                                    <td><?php echo e($config->key); ?></td>
                                    <td><?php echo e($config->secret); ?></td>
                                    <td><?php echo e((!empty($config->aws_counter)) ? $config->aws_counter : '-'); ?></td>
                                    <td><?php echo $primaryNetwork; ?></td>
                                    <td><?php echo $statusOptions; ?></td>
                                    <td class="record_actions">
                                        <a href="<?php echo e(url('/config/'.$config->id.'/edit/')); ?>" title="edit" class=""><i class="fa fa-edit"></i></a>
                                        <a href="<?php echo e(url('/config/'.$config->id.'/delete/')); ?>" title="delete" class="" onclick="return confirm('Are you sure you want to delete <?php echo e($config->key); ?> ?')"><i class="fa fa-trash"></i></a>
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