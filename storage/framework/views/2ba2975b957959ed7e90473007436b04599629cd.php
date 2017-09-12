<?php $__env->startSection('page_title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
    <?php if($user->isAdmin()): ?>
        <?php echo $__env->make('layouts.dashboard.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('layouts.dashboard.client', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>