<?php $__env->startSection('message'); ?>
404 - Page Not Found!
<?php $__env->stopSection(); ?>
<?php echo $__env->make('errors.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>