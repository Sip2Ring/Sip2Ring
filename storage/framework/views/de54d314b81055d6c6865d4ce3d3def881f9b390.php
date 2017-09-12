

<?php $__env->startSection('page_title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
<div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
                Add Group
                <div class="pull-right iconBar">
                    <span class="minimise cntrlBtnGrp">
                        <span class="fa fa-minus"></span>
                    </span>
                    <span class="closeme cntrlBtnGrp">
                        <span class="fa fa-close"></span>
                    </span>
                </div>
            </div>
            <div class="panel_warper">
                <div class="panel_body">
                    <form  method="post" accept-charset="utf-8" id="form_id" action="<?php echo e(url('/add-buyer')); ?>">
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Email Address <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="email"  name="email" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                             <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="confirm_email">Confirm Email <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="confirm_email"  name="confirm_email" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="company">Company <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="company"  name="company" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="first_name">First Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="first_name"  name="first_name" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="last_name">Last Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control input-md" id="last_name"  name="last_name" autofocus >
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 text-center m-t-40">
                                <button type="submit" class="btn btn-primary">Add Buyer</button>
                            </div>

                            
                        <div class="clearfix"></div>
                    </form>
                </div>                     
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

  
<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>