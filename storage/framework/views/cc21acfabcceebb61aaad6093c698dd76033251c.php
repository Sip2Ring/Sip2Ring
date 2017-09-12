

<?php $__env->startSection('page_title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
<!--======== contact =========-->

<div ng-controller="dashboardController">
    <div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
               Webhooks
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
                    <form  method="post" accept-charset="utf-8" id="add_user" action="<?php echo e(url('/intwebbooks')); ?>">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"   placeholder="My webhook" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Call Token<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="call_id" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Revinue Token<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="call_revinue" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Fire Conversation<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="Optional: sale_succesful=yes" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>


                            </div>


                            <div class="col-sm-12 text-center m-t-40">
                                <button type="submit" class="btn btn-primary">Add</button>

                            <button type="reset" class="btn btn-success">Clear</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>



            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.adminDashboard', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>