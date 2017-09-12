

<?php $__env->startSection('page_title', 'Add User'); ?>

<?php $__env->startSection('content'); ?>
<!--======== contact =========-->
<?php
if(!empty($getuserList)){
    $name = $getuserList->name;
    $email = $getuserList->email;
    $mobile_no = $getuserList->mobile_no;

}

?>
<div ng-controller="dashboardController">
    <div class="panel-container col-sm-12">
        <div class="panels panels-default collapsible">
            <div class="panel_header">
                <span class="fa fa-bolt panel-icon"></span>
                Add Address

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
                    <form  method="post" accept-charset="utf-8" id="add_user" action="<?php echo e(url('/update-profile')); ?>">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Name <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <?php if(!empty($name)){?>
                                    <input type="text" name="name" class="form-control" value="<?php echo e($name); ?>"  placeholder="" id="name">
                                    <?php }else{?>
                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">
                                    <?php }?>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Mobile No <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">
                                    <?php if(!empty($mobile_no)){?>
                                    <input type="text" name="name" class="form-control" value="<?php echo e($mobile_no); ?>"  placeholder="" id="name">
                                    <?php }else{?>
                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">
                                    <?php }?>
                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Skype handle<span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>


                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>


                            <div class="col-sm-12 text-center m-t-40">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        <div class="clearfix"></div>
                    </form>
                </div>


                <div class="panel_body">
                    <form  method="post" accept-charset="utf-8" id="add_user" action="<?php echo e(url('/update-profile')); ?>">
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Current Password <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                        </div>
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">New Password <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                        </div>
                        <div class="form responsiveForm col-sm-12">
                            <div class="col-sm-12 formInputContainer">
                                <div class="col-sm-3 labelContainer">
                                    <label for="email">Conform Password <span class="fa fa-question-circle"></span></label>
                                </div>
                                <div class="col-sm-5">

                                    <input type="text" name="name" class="form-control"  placeholder="" id="name">

                                </div>
                                <div class="col-sm-4">
                                    <span class="error"></span>
                                </div>
                            </div>

                        </div>

                        <div class="col-sm-12 text-center m-t-40">
                            <button type="submit" class="btn btn-primary">Update</button>
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